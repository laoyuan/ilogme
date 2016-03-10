<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $morphClass = 1;


    public function spans()
    {
        return $this->hasMany('App\Span');
    }

    public function todos()
    {
        return $this->hasMany('App\Todo');
    }

    public function types()
    {
        return $this->belongsToMany('App\Type');
    }

    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }

    public function days()
    {
        return $this->spans()->groupBy('date');
    }
}
