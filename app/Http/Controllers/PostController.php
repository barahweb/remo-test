<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller
{
    public function __construct(private PostService $postService) {}

    public function index()
    {
        $posts = $this->postService->getLatestPosts();

        return response()->json([
            'data'  => $posts,
            'total' => $posts->count(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => 'required',
            'content'   => 'required',
            'author_id' => 'nullable|exists:authors,id',
        ]);

        $post = $this->postService->createPost($validated);

        return response()->json([
            'data'    => $post,
            'message' => 'Successfully stored a new post!',
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title'     => 'sometimes|required',
            'content'   => 'sometimes|required',
            'author_id' => 'sometimes|nullable|exists:authors,id',
        ]);

        $post = $this->postService->updatePost($post, $validated);

        return response()->json([
            'data'    => $post,
            'message' => 'Successfully updated the post!',
        ]);
    }

    public function destroy(Post $post)
    {
        $this->postService->deletePost($post);

        return response()->noContent();
    }

    public function storeComments(Request $request, Post $post)
    {
        $validated = $request->validate([
            'comments' => 'required',
        ]);

        $comment = $this->postService->storeComment($post, $validated['comments']);

        return response()->json([
            'data'    => $comment,
            'message' => 'Successfully stored a new comment!',
        ], Response::HTTP_CREATED);
    }
}
