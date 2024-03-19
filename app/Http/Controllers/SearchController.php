<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public  function  search(Request $request, string $search = null)
    {
        //$search = $keywords; //$request->get('keywords');

        if (!$search){
            return  redirect(route('dashboard'));
        }

        $users = User::query()
            ->where('name','like', "%$search%")
            ->orWhere('username','like', "%$search%")
            ->latest()
            ->get();

        $groups = Group::query()
            ->where('name','like', "%$search%")
            ->orWhere('about','like', "%$search%")
            ->latest()
            ->get();


        $posts = Post::postsForTimeLine(Auth::id())
            ->where('body','like', "%$search%")
//            ->latest()
            ->paginate(20);

        //dd($posts->toRawSSql);

        /**Changed because of Read more**/
        $posts = PostResource::collection($posts);

        if ($request->wantsJson()){
            return  $posts;
        }

        return inertia('Search',[
            'posts' => $posts,
            'search' => $search,
            'users' => UserResource::collection($users),
            'groups' => GroupResource::collection($groups),
        ]);

    }
}