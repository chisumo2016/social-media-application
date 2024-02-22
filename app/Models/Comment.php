<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['post_id','user_id','comment','parent_id'];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post():BelongsTo
    {
        return $this->belongsTo(Post::class);
    }


    public  function reactions():MorphMany
    {
        return $this->morphMany(Reaction::class,'object'); //hasMany
    }

    public function comments(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
