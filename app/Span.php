<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Span extends Model
{
    protected $fillable = ['user_id', 'type_id', 'spend', 'date', 'content', 'addition'];
    protected $touches = ['user'];
    protected $morphClass = 3;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function type()
    {
        return $this->belongsTo('App\Type');
    }

    //适合人类阅读的时段长度
    public function spend_fine()
    {
        if ($this->spend === -1) {
            $spend_fine = time() - $this->created_at->getTimestamp();
        }
        else {
            $spend_fine = $this->spend;
        }
        
        if ($spend_fine > 3600) {
            $spend_fine = floor($spend_fine / 3600) . '小时' . ceil($spend_fine % 3600 / 60) . '分';
        }
        else {
            $spend_fine = ceil($spend_fine / 60) . '分钟';
        }
        return $spend_fine;
    }
}
