<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pic extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'image'];
    protected $dates = ['deleted_at'];
    protected $morphClass = 6;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
