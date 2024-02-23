<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
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
            ->paginate(20);

        return Inertia::render('Home', [
            'posts' => PostResource::collection($posts)
        ]);
    }
}
