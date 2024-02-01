<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PostAttachment extends Model
{
    use HasFactory;

    const UPDATED_AT = null;  //SQLSTATE[42S22]: Column not found: 1054 Unknown column 'updated_at' in 'field list'

    protected $fillable = [
        'post_id',
        'name'   ,
        'path'  ,
        'mime' ,
        'size'  ,
        'created_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleted(function (self $model) {
            Storage::disk('public')->delete($model->path);
        });
    }
}
