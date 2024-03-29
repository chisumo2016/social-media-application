<?php

namespace App\Models;

use App\Http\Enum\GroupUserRole;
use App\Http\Enum\GroupUserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;



class Group extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'user_id',
        'auto_approval',
        'about',
        'cover_path',
        'thumbnail_path',
        'pinned_post_id'
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public  function currentUserGroup(): HasOne
    {
        // Return the authenticated user status/role
        return $this->HasOne(GroupUser::class)->where('user_id', Auth::id());
    }

    public function isAdmin($userId):bool
    {
       return GroupUser::query()
            ->where('user_id', $userId)
            ->where('group_id',$this->id)
            ->where('role', GroupUserRole::ADMIN->value)
            ->exists(); //if exist , the user is admin

//        return $this->currentUserGroup?->user_id == $userId
//            &&  $this->currentUserGroup?->role === GroupUserRole::ADMIN->value; //is admin
    }

    public function isOwner($userId):bool
    {
        return  $this->user_id == $userId;
    }


    public  function adminUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_users')
            ->wherePivot('role', GroupUserRole::ADMIN->value);
    }

    public  function pendingUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_users')
            ->wherePivot('status', GroupUserStatus::PENDING->value);
    }

    public  function approvedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_users')
            ->wherePivot('status', GroupUserStatus::APPROVED->value);
    }

    public function hasApprovedUser($userId):bool
    {
        return GroupUser::query()
            ->where('user_id', $userId)
            ->where('group_id',$this->id)
            ->where('status', GroupUserStatus::APPROVED->value)
            ->exists();

    }

}
