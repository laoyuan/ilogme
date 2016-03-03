<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'type_id', 'spend', 'content', 'addition'];
    protected $touches = ['user'];
    protected $morphClass = 6;

    public function commentable()
    {
        return $this->morphTo();
    }


}
