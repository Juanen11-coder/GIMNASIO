<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        $comments = $post->comments()->with('user')->get();
        return response()->json($comments);
    }

    public function store(Request $request, Post $post)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        $request->validate([
            'content' => 'required|string|max:500'
        ]);

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content
        ]);

        $post->increment('comments_count');

        return response()->json($comment->load('user'));
    }
}
