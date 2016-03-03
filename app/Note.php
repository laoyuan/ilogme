<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'content'];
    protected $dates = ['deleted_at'];
    protected $morphClass = 5;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
