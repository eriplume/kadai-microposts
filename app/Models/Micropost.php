<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    use HasFactory;
    
    protected $fillable = ['content'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function favorite_users()
    {
        return $this->belogsToMany(User::class, 'favorites', 'micropost_id', 'user_id')->withTimestamps();    
    }
}
