<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'user_type_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function avatar(){
        return $this->hasOne('App\File', 'id', 'avatar_id');
    }

    public function candidateScore($id){
        return $this->hasMany('App\ModeratorQuestion', 'moderator_id', 'id')->where('candidate_id', $id)->sum('score');
    }
}
