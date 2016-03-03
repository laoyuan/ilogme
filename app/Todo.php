<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todo extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'title'];
    protected $dates = ['deleted_at'];
    protected $morphClass = 4;

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
