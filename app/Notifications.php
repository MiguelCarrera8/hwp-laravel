<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $table = "notifications";
    protected $fillable = ['user_id', 'user_noti_id', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
