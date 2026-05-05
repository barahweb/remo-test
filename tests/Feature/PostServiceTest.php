<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Services\PostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostServiceTest extends TestCase
{
    use RefreshDatabase;

    private PostService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PostService();
    }

    public function test_can_create_post(): void
    {
        $post = $this->service->createPost(['title' => 'Hello', 'content' => 'World']);

        $this->assertDatabaseHas('posts', ['title' => 'Hello']);
        $this->assertInstanceOf(Post::class, $post);
    }

    public function test_can_get_latest_posts(): void
    {
        Post::factory()->count(3)->create();

        $posts = $this->service->getLatestPosts();

        $this->assertCount(3, $posts);
    }

    public function test_can_update_post(): void
    {
        $post = Post::factory()->create(['title' => 'Old Title']);

        $this->service->updatePost($post, ['title' => 'New Title']);

        $this->assertDatabaseHas('posts', ['title' => 'New Title']);
    }

    public function test_can_delete_post(): void
    {
        $post = Post::factory()->create();

        $this->service->deletePost($post);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_can_store_comment(): void
    {
        $post = Post::factory()->create();

        $comment = $this->service->storeComment($post, 'Nice post!');

        $this->assertDatabaseHas('comments', ['comments' => 'Nice post!', 'post_id' => $post->id]);
    }
}
