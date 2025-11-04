<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function store(Request $request) 
    {
        try {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => $validation->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $post = new Post();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();

        return response()->json([
            'data' => $post,
            'message' => 'Succesfully store a new post!'
        ], Response::HTTP_ACCEPTED);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

    }

    public function storeComments(Request $request, $id) 
    {

        // return response()->json([
        //     'data' => $request->comments,
        //     'id' => $id
        // ]);

        try {
        $validation = Validator::make($request->all(), [
            'comments' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => $validation->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Cannot find the ID'
            ], Response::HTTP_BAD_REQUEST);
        }

        $storeComments = new Comments();
        $storeComments->comments = $request->comments;
        $storeComments->post_id = $id;
        $storeComments->save();

        return response()->json([
            'data' => $storeComments,
            'message' => 'Succesfully store a new Comments!'
        ], Response::HTTP_ACCEPTED);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

    }

    public function index () {
        $posts = Cache::remember('feed:latest', now()->addMinutes(1), function () {
            return Post::get()->take(10);
        });

        return response()->json([
            'data' => $posts,
            'total' => $posts->count()
        ], Response::HTTP_OK);
    }
}
