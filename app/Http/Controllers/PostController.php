<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function index()
    {
        $posts = Cache::remember('feed:latest', 60, fn () => Post::take(10)->get());

        return response()->json([
            'data'  => $posts,
            'total' => $posts->count(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required',
            'content' => 'required',
        ]);

        $post = Post::create($validated);

        return response()->json([
            'data'    => $post,
            'message' => 'Successfully stored a new post!',
        ], Response::HTTP_CREATED);
    }

    public function storeComments(Request $request, $id)
    {
        $validated = $request->validate([
            'comments' => 'required',
        ]);

        $post = Post::findOrFail($id);

        $comment = Comments::create([
            'comments' => $validated['comments'],
            'post_id'  => $post->id,
        ]);

        return response()->json([
            'data'    => $comment,
            'message' => 'Successfully stored a new comment!',
        ], Response::HTTP_CREATED);
    }
}
