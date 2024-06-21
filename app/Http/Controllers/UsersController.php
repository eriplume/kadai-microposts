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
    
    /**
     * ユーザーのフォロー一覧ページを表示するアクション。
     *
     */
    public function followings($id) 
    {
        $user = User::findOrFail($id);
        
        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();
        
        $followings = $user->followings()->paginate(10);
        
        return view('users.followings', [
            'user' => $user,
            'users' => $followings,
        ]);
    }
    
    /**
     * ユーザーのフォロワー一覧ページを表示するアクション。
     *
     */
    public function followers($id) 
    {
        $user = User::findOrFail($id);
        
        $user->loadRelationshipCounts();
        
        $followers = $user->followers()->paginate(10);
        
        return view('users.followers', [
            'user' => $user,
            'users' => $followers,
        ]);
    }
}
