<?php

namespace App\Services;

use App\Models\Comments;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class PostService
{
    public function getLatestPosts(): Collection
    {
        return Cache::remember('feed:latest', 60, fn () => Post::take(10)->get());
    }

    public function createPost(array $data): Post
    {
        return Post::create($data);
    }

    public function updatePost(Post $post, array $data): Post
    {
        $post->update($data);

        return $post;
    }

    public function deletePost(Post $post): void
    {
        $post->delete();
    }

    public function storeComment(Post $post, string $comment): Comments
    {
        return Comments::create([
            'comments' => $comment,
            'post_id'  => $post->id,
        ]);
    }
}
