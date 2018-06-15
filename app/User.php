<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];


public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
    protected $hidden = [
        'password', 'remember_token',
    ];
   

public function follow($userId)
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

public function unfollow($userId)
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

public function is_following($userId) {
    return $this->followings()->where('follow_id', $userId)->exists();
}

public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    
public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
  





public function feed_microposts()
    {
        $follow_user_ids = $this->followings()-> pluck('users.id')->toArray();
        $follow_user_ids[] = $this->id;
        return Micropost::whereIn('user_id', $follow_user_ids);
    }




public function favorites($userId)
{
    
    $exist = $this->is_favoriting($userId);
   
    $its_me = $this->id == $userId;

    if ($exist || $its_me) {
        
        return false;
    } else {
       
        $this->favoritings()->attach($userId);
        return true;
    }
}


public function unfavorite($userId)
{
    
    $exist = $this->is_favoriting($userId);
   
    $its_me = $this->id == $userId;

    if ($exist && !$its_me) {
        
        $this->favoritings()->detach($userId);
        return true;
    } else {
        
        return false;
    }
}


public function is_favoriting($userId) {
    return $this->favoritings()->where('micropost_id', $userId)->exists();
}

public function favoritings()
    {
        return $this->belongsToMany(User::class, 'favorites', 'user_id', 'micropost_id')->withTimestamps();

}

}