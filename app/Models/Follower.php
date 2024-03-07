<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;

    const  UPDATED_AT = NULL;

    protected $fillable =[
        'user_id',
        'follower_id'
    ];
}
