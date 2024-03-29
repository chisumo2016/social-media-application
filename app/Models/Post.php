<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Post
 *
 * @package  App\Models
 * @property  Group $group
 */

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected  $fillable = [
        'body',
        'user_id',
        'group_id',
        'preview',
        'preview_url',
        //'pinned',
    ];

    protected $casts =[
        'preview' => 'json'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(PostAttachment::class)->latest();
    }

    public  function reactions():MorphMany
    {
        return $this->morphMany(Reaction::class,'object'); //hasMany
    }

    public  function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public  function latest5comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public static function postsForTimeLine($userId ,  $getLatest = true): Builder
    {
        $query =  Post::query()  /**SELECT * FROM post*/
        /** SELECT COUNT(*)  from reactions**/
        ->withCount('reactions')
            ->with([
                'user',
                'group',
                'group.currentUserGroup',
                'attachments',
                /** SELECT * FROM comments WHERE post_id IN (1,2,3..)**/
                'comments' => function ($query)  {
                    /** SELECT COUNT(*)  from reactions**/
                    $query ->withCount('reactions');
                },
                'comments.user',
                'comments.reactions' => function ($query) use ($userId) {
                            /**SELECT *   from reactions WHERE user_id = ? (current user)*/
                        $query->where('user_id', $userId);
                },

                'reactions' => function ($query) use ($userId) {
                    /**SELECT *   from reactions WHERE user_id = ? (current user)*/
                    $query->where('user_id', $userId);
                }]);

                if ($getLatest){

                        $query->latest();
                }

                return  $query;
    }

    public function isOwner($userId)
    {
        return $this->user_id == $userId;
    }
}
//->latest()->limit(5)
