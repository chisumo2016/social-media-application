<?php

namespace App\Http\Controllers;

use App\Http\Enum\GroupUserStatus;
use App\Http\Resources\GroupResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
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

        $user = $request->user();

        $posts = Post::postsForTimeLine($userId)

            ->select('posts.*') //select the only post , which is made by the users I am following
            ->leftJoin('followers AS f', function ($join) use ($userId) {
                $join->on('posts.user_id', '=', 'f.user_id')
                    ->where('f.follower_id', '=', $userId);
            })
            ->leftJoin('group_users AS gu', function ($join) use ($userId) {
                $join->on('gu.group_id', '=', 'posts.group_id')
                    ->where('gu.user_id', '=', $userId)
                    ->where('gu.status', GroupUserStatus::APPROVED->value);
            })

            ->where(function($query) use ($userId) {
                /** @var \Illuminate\Database\Query\Builder $query */
                $query->whereNotNull('f.follower_id')
                    ->orWhereNotNull('gu.group_id')
                    ->orWhere('posts.user_id', $userId)
                ;
            })
            //->where('f.follower_id', $userId)

            ->paginate(10);

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
        /**User I am followings*/

        //$user->followings;

        return Inertia::render('Home', [
            'posts' => $posts,
            'groups' => GroupResource::collection($groups),
            'followings' =>UserResource::collection($user->followings)

        ]);
    }
}
