<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function index()
    {
        $userProfiles = User::with('library')->select('id', 'name')->get();
        return view('userProfiles', ['users' => $userProfiles]);
    }

    public function showUserProfile($id)
    {
        $user = User::with('library')->findOrFail($id);
        return view('home', compact('user'));
    }
}
