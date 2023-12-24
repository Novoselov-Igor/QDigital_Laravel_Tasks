<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index($authorId, $bookId)
    {
        $book = Book::find($bookId);

        return view('book', compact('book'));
    }

    public function show(Request $request)
    {
        $request->validate([
            'authorId' => 'required'
        ]);

        $books = Book::where('author_id', $request->input('authorId'))->get();
        return response()->json($books);
    }

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

    public function change(Request $request)
    {
        $request->validate([
            'bookId' => 'required',
            'name' => 'required|max:60',
            'text' => 'required',
        ]);

        $book = Book::find($request->input('bookId'));

        if (!is_null($book) && $book->author_id === $request->user()->id) {
            $book->name = $request->input('name');
            $book->text = $request->input('text');

            $book->save();
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'bookId' => 'required'
        ]);

        $book = Book::find($request->input('bookId'));

        if (!is_null($book) && $book->author_id === $request->user()->id) {
            $book->delete();
        }
    }

    public function giveLinkAccess(Request $request)
    {
        $request->validate([
            'bookId' => 'required'
        ]);

        $book = Book::find($request->input('bookId'));

        if (!is_null($book) && $book->author_id === $request->user()->id) {
            $book->link_access = true;

            $book->save();
        }
    }

    public function removeLinkAccess(Request $request)
    {
        $request->validate([
            'bookId' => 'required'
        ]);

        $book = Book::find($request->input('bookId'));

        if (!is_null($book) && $book->author_id === $request->user()->id) {
            $book->link_access = false;

            $book->save();
        }
    }
}
