<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'body',
        'user_id'
    ];

    protected $dates = ['deleted_at'];
}
