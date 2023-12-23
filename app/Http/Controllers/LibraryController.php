<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    public function index($authorId)
    {
        $books = Book::where('author_id', $authorId)->get();

        return view('bookLibrary', compact('books', 'authorId'));
    }

    public function giveAccess(Request $request)
    {
        $request->validate([
            'userId' => 'required|int',
        ]);

        $user = User::findOrFail($request->input('userId'));
        $user->library()->attach($request->input('userId'), ['author_id' => Auth::user()->id]);
    }

    public function removeAccess(Request $request)
    {
        $request->validate([
            'userId' => 'required|int',
        ]);

        $user = User::findOrFail($request->input('userId'));
        $user->library()->detach($request->input('authorId'));
    }
}
