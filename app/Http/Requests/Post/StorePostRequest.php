<?php

namespace App\Http\Requests\Post;

use App\Rules\ValidSlug;
use App\Rules\FutureDate;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    
    public function prepareForValidation()
    {
        // Automatically generate a slug from the title if it is not provided in the request
        if(!$this->has('slug') && $this->input('title')){

            $slug = Str::slug($this->input('title'));
            $this->merge([
                'slug' => $slug
            ]);
        }

        // Generate meta_description from title and body if not provided
        if (!$this->has('meta_description')) {
            $title = $this->input('title', '');
            $body = Str::limit(strip_tags($this->input('body', '')), 20);
            
            $metaDescription = Str::limit("$title - $body", 160);
            
            $this->merge([
                'meta_description' => $metaDescription
            ]);
        }
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug'  => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('posts', 'slug'),
                new ValidSlug(),
            ],
            'body' => ['required', 'string', 'min:20','max:10000' ],
            'meta_description' => ['sometimes', 'string', 'min:10' , 'max:200'],
            'is_published' => ['sometimes', 'boolean'],
            'publish_date' => [
                'sometimes',
                'date',
                new FutureDate(),
            ],
            'tags'              => ['sometimes', 'array', 'min:2', 'max:20'],
            'tags.*'            => ['required', 'string', 'max:30'],
            'keywords'          => ['sometimes', 'array', 'min:2', 'max:20'],
            'keywords.*'        => ['required', 'string', 'max:30'],
        ];
    }

    public function messages()
    {
        return[
            'title.required' => 'The title field is required.',
            'title.max'      => 'The title field may not be greater than :max characters.',
            'slug.unique' => 'This slug (:input) is already in use. Please choose another one or leave it blank for automatic generation.',
            'slug.max' => 'The slug field may not be greater than :max characters.',
            'body.required' => 'The article content is required.',
            'is_published.boolean' => 'The is_published field must be true or false.',
            'publish_date.date' => 'The publish date must be a valid date.',
            'meta_description.max' => 'The meta description may not be greater than :max characters.',
        ];       
     }

     public function attributes()
     {
        return [
            'title' => 'Title',
            'slug' => 'Slug',
            'body' => 'Body',
            'meta_description' => 'Meta Description',
            'is_published' => 'Is Published',
            'publish_date' => 'Publish Date',
            'tags' => 'Tags',
            'keywords' => 'Keywords',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'status' => 'error',
            'errors' => $validator->errors(),
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
