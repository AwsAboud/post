<?php

namespace App\Service;

use App\Models\Post;

class PostService
{

    /**
     * Retrieve a paginated list of published blog posts
     *
     * @return LengthAwarePaginator
     */
    public function getAll()
    {
        $posts = Post::Published()->paginate(10);

        return $posts;
    }

     /**
     * Retrieve  a spesific post
     * @param \App\Models\Post $post
     * @return Post
     */
    public function getOne(Post $post):Post
    {
        return $post;
    }

    /**
     * Create a new post
     * @param array $data
     * @return Post
     */
    public function create(array $data): Post
    {
        return Post::create($data);
    }

    /**
     * Update an existing post
     * @param \App\Models\Post $post
     * @param array $data
     * @return Post
     */
    public function update(Post $post, array $data): Post
    {
        $post->update($data);

        return $post;
    }

    /**
     * Delete a post 
     * @param \App\Models\Post $post
     * @return bool
     */
    public function delete(Post $post): bool
    {
        return $post->delete();
    }

}
