<?php

namespace App\Http\Controllers\V1;

use App\Models\Post;
use App\Service\PostService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;

class PostController extends Controller
{

    public function __construct(protected PostService $service){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $res = $this->service->getAll();

        return $this->successResponse(PostResource::collection($res));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data =  $request->validated();
        $res = $this->service->create($data);

        return $this->successResponse(new PostResource($res));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $res = $this->service->getOne($post);

        return $this->successResponse(new PostResource($res));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();
        $res = $this->service->update($post, $data);

        return $this->successResponse(new PostResource($res));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $res = $this->service->delete($post);

        return $this->successResponse(new PostResource($res), 20);
    }
}
