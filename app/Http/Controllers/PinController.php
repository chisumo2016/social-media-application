<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PinController extends Controller
{

    public function pinUnpin(Request $request,  Post $post)
    {
        //return 'benn';
        if ($post->isOwner(Auth::id()) ||  $post->group && $post->group->isAdmin(Auth::id())){

            $post->pinned = !$post->pinned;

            $post->save();
            return response(new PostResource($post));
        }
            return  response("You don't have permission to perform this action", 403);

    }
}
