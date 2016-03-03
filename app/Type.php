<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = ['title'];
    protected $morphClass = 2;

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function spans()
    {
        return $this->hasManyThrough('App\Span', 'App\User');
    }
}
