<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //Функция отображения комментариев на странице
    public function show(Request $request)
    {
        $comments = Comment::with('author', 'reply')
            ->where('user_id', $request->input('userId'))
            ->orderBy('updated_at', 'desc')
            ->get();


        if ($comments->count() > 5 && $request->input('type') === 'compact') {
            $response = [
                'comments' => $comments->take(5),
                'size' => 'large'
            ];
        } else {
            $response = [
                'comments' => $comments,
                'size' => 'small'
            ];
        }
        return response()->json($response);
    }

    //Функция добавления новых комментариев
    public function add(Request $request)
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

    public function delete(Request $request)
    {
        $comment = Comment::find($request->input('commentId'));
        $userId = $request->user()->id;

        if ($userId === $comment->author_id || $userId === $comment->user_id) {
            Comment::destroy($request->input('commentId'));
        }
    }
}
