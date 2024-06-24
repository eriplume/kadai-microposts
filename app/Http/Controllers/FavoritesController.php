<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function store(string $id) 
    {
        // 認証済みユーザー（閲覧者）が、 idのmicropostをお気に入りする
        \Auth::user()->favorite(intval($id));
        // 前のURLへリダイレクトさせる
        return back();
    }
    
    public function destroy(string $id) 
    {
        // 認証済みユーザー（閲覧者）が、 idのmicropostをお気に入り解除する
        \Auth::user()->unfavorite(intval($id));
        return back();
    }
}
