<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UsersController extends Controller
{
    public function index() 
    {
        $users = User::orderBy('id', 'desc')->paginate(10);
        
        return view('users.index', [
            'users' => $users,
        ]);
    }
    
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        
        $user->loadRelationshipCounts();
        
        $microposts = $user->microposts()->orderBy('creates_at', 'desc')->paginate(10);
        
        return view('users.show', [
            'user' => $user,
            'microposts' => $microposts
        ]);
    }
}
