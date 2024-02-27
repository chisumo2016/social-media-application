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
        $posts = Post::query()  /**SELECT * FROM post*/
            /** SELECT COUNT(*)  from reactions**/
            ->withCount('reactions')
            ->with([
                /** SELECT * FROM comments WHERE post_id IN (1,2,3..)**/
                'comments' => function ($query) use ($userId) {
                    /** SELECT COUNT(*)  from reactions**/
                    $query ->withCount('reactions');
                   },

                'reactions' => function ($query) use ($userId) {
                    /**SELECT *   from reactions WHERE user_id = ? */
                    $query->where('user_id', $userId);
            }])
            ->latest()
            ->paginate(5);

     /**Changed because of Read more**/
       $posts = PostResource::collection($posts);

       if ($request->wantsJson()){
           return  $posts;
       }

     /**All the groups currently authenticated**/
        $groups = Group::query()
            ->select(['groups.*','gu.status', 'gu.role'])
            ->join('group_users AS gu','gu.group_id','groups.id')
            ->where('gu.user_id', Auth::id())
//            ->where('gu.status',GroupUserStatus::APPROVED->value)
            ->orderBy('gu.role')
            ->orderBy('name','desc')
            ->get();


        return Inertia::render('Home', [
            'posts' => $posts,
            'groups' => GroupResource::collection($groups)

        ]);
    }
}
