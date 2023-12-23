<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required|max:60',
            'text' => 'required',
            'authorId' => 'required'
        ]);

        $book = Book::create([
            'name' => $request->input('name'),
            'text' => $request->input('text'),
            'author_id' => $request->input('authorId'),
        ]);

        return response()->json($book);
    }
}
