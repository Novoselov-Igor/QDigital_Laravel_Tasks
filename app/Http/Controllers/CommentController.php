<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //Функция отображения комментариев на странице
    public function showComments(Request $request)
    {
        $comments = Comment::with('author', 'reply')
            ->where('user_id', $request->input('userId'))
            ->orderBy('updated_at', 'desc')
            ->get();

        if ($comments->count() > 5 && $request->input('type') === 'compact') {
            return response()->json(['comments' => $comments->take(5), 'size' => 'large']);
        }
        return response()->json(['comments' => $comments, 'size' => 'small']);
    }

    //Функция добавления новых комментариев
    public function addComment(Request $request)
    {
        $request->validate([
            'title' => 'required|max:60',
            'text' => 'required|max:255',
        ]);

        $comment = Comment::create([
            'user_id' => $request->input('userId'),
            'comment_id' => $request->input('commentId'),
            'title' => $request->input('title'),
            'text' => $request->input('text'),
            'author_id' => $request->user()->id
        ]);

        return response()->json($comment);
    }

    public function deleteComment(Request $request)
    {
        Comment::destroy($request->input('commentId'));
    }
}
