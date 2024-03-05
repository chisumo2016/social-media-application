<?php

namespace App\Http\Controllers;

use App\Http\Enum\GroupUserStatus;
use App\Http\Resources\GroupResource;
use App\Http\Resources\PostResource;
use App\Models\Group;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $posts = Post::postsForTimeLine($userId)
            ->paginate(5);

     /**Changed because of Read more**/
       $posts = PostResource::collection($posts);

       if ($request->wantsJson()){
           return  $posts;
       }

     /**All the groups currently authenticated**/
        $groups = Group::query()
            ->with('currentUserGroup')
            ->select(['groups.*'])  //'gu.status', 'gu.role'

            ->join('group_users AS gu','gu.group_id','groups.id')
            ->where('gu.user_id', Auth::id())
//            ->where('gu.status',GroupUserStatus::APPROVED->value)
            ->orderBy('gu.role')
            ->orderBy('name','desc')
            ->get();

        //dd($groups[0], $groups[0]->currentUserGroup);

        return Inertia::render('Home', [
            'posts' => $posts,
            'groups' => GroupResource::collection($groups)

        ]);
    }
}
