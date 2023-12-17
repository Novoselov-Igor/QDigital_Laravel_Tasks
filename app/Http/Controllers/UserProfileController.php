<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function index()
    {
        $userProfiles = User::select('id', 'name')->get();
        return view('userProfiles', ['users' => $userProfiles]);
    }

    public function showUserProfile($id)
    {
        $user = User::where('id', $id)->select('id', 'name')->first();
        return view('home', ['user' => $user]);
    }
}
