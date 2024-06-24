<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public function microposts()
    {
        return $this->hasMany(Micropost::class);   
    }
    
    /**
     * このユーザーに関係するモデルの件数をロードする。
     * 'リレーションメソッドの名前' を記載する
     */
    public function loadRelationshipCounts() 
    { 
        $this->loadCount(['microposts', 'followings', 'followers', 'favorites']);   
    }
    
     /**
     * このユーザーがフォロー中のユーザー。（Userモデルとの関係を定義）
     */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();    
    }
    
    /**
     * このユーザーをフォロー中のユーザー。（Userモデルとの関係を定義）
     */
    public function followers() 
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();    
    }
    
    
    /**
     * $userIdで指定されたユーザーをフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function follow(int $userId) 
    {
        $exist = $this->is_following($userId);
        $its_me = $this->id == $userId;
        
        if ($exist || $its_me) {
            return false;
        } else {
            $this->followings()->attach($userId);
            return true;
        }
    }
    
     /**
     * $userIdで指定されたユーザーをアンフォローする。
     * 
     * @param  int $usereId
     * @return bool
     */
    public function unfollow(int $userId)
    {
        $exist = $this->is_following($userId);
        $its_me = $this->id == $userId;
        
        if ($exist && !$its_me) {
            $this->followings()->detach($userId);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 指定された$userIdのユーザーをこのユーザーがフォロー中であるか調べる。フォロー中ならtrueを返す。
     * 
     * @param  int $userId
     * @return bool
     */
    public function is_following(int $userId)
    {
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    public function feed_microposts() 
    {
        //ユーザーがフォローしているユーザーのidを配列にする
        $userIds = $this->followings()->pluck('users.id')->toArray();
        
        //その配列の末尾に自分自身のidも追加する
        $userIds[] = $this->id;
        
        //user_idカラムが $userIds配列の値と一致するものを絞り込む
        return Micropost::whereIn('user_id', $userIds);
    }
    
    public function favorites()
    {
        return $this->belongsToMany(Micropost::class, 'favorites', 'user_id', 'micropost_id')->withTimestamps();    
    }
    
    /**
     * $postIdで指定されたmicropostをお気に入りする。
     * 
     * @param  int $usereId
     * @return bool
     */
    public function favorite(int $postId) 
    {
        $exist = $this->is_favorite($postId);
        
        if ($exist) {
            return false;
        } else {
            $this->favorites()->attach($postId);
            return true;
        }
    }
    
     /**
     * $postIdで指定されたmicropostをお気に入り解除する。
     * 
     * @param  int $usereId
     * @return bool
     */
    public function unfavorite(int $postId)
    {
        $exist = $this->is_favorite($postId);
        
        if ($exist) {
            $this->favorites()->detach($postId);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 指定されたmicropostをこのユーザーがお気に入りしているか調べる。
     * 
     * @param  int $userId
     * @return bool
     */
    public function is_favorite(int $postId)
    {
        return $this->favorites()->where('micropost_id', $postId)->exists();
    }
}
