<?php

namespace App\Http\Controllers;

use App\Http\Enum\ReactionEnum;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostAttachment;
use App\Models\Reaction;
use App\Models\User;
use App\Notifications\CommentCreated;
use App\Notifications\CommentDeleted;
use App\Notifications\PostCreated;
use App\Notifications\PostDeleted;
use App\Notifications\ReactionAddedOnComment;
use App\Notifications\ReactionAddedOnPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use OpenAI\Laravel\Facades\OpenAI;

class PostController extends Controller
{
    public function view(Post $post)
    {
        $post->loadCount('reactions');
        $post->load([
            /** SELECT * FROM comments WHERE post_id IN (1,2,3..)**/
            'comments' => function ($query)  {
                /** SELECT COUNT(*)  from reactions**/
                $query ->withCount('reactions');
            },
        ]);

        return inertia('Post/View', [
            'post' => new PostResource($post)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {

        //dd($request->all());

         $data = $request->validated();
         $user = $request->user();

        DB::beginTransaction();
         $allFilePaths = [];

        try {

            $post = Post::create($data);

            /** @var \Illuminate\Http\UploadedFile[] $files */
        $attachments = $data['attachments'] ?? [] ;
        foreach ($attachments as $file){

            $filePath = $file->store('attachments/' . $post->id, 'public');  //'avatars/'.$user->id
            $allFilePaths[] = $filePath ;

            /**Create Attachment pivot table*/
            PostAttachment::create([
                'post_id' => $post->id,
                'name' => $file->getClientOriginalName(),
                'path' => $filePath,
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
                'created_by' => $user->id

            ]);

        }
            DB::commit();

                 /***/
            $group = $post->group;

            if ($group){
                $users = $group->approvedUsers()->where('users.id', '!=', $user->id)->get();
               Notification::send($users , new PostCreated($post, $user, $group));

               /**Globally**/

                $followers = $user->followers;

                Notification::send($followers , new PostCreated($post, $user, null));
                //dd($followers);
            }

        }catch (\Exception $e){
            foreach ($allFilePaths as $filePath){
                Storage::disk('public')->delete($filePath);
            }
            DB::rollBack();

            throw $e;
        }

         return  back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $user = $request->user();

        DB::beginTransaction();
        $allFilePaths = [];

        try {
            $data = $request->validated();
            $post->update($data);

            $deleted_ids = $data['deleted_file_ids'] ?? [];

            $attachments = PostAttachment::query()
                ->where('post_id', $post->id)
                ->whereIn('id', $deleted_ids)
                ->get();

            foreach ($attachments as $attachment){
                $attachment->delete();
            }

            /** @var \Illuminate\Http\UploadedFile[] $files */
            $attachments = $data['attachments'] ?? [] ;

            foreach ($attachments as $file){

                $filePath = $file->store('attachments/' . $post->id, 'public');  //'avatars/'.$user->id
                $allFilePaths[] = $filePath ;

                /**Create Attachment pivot table*/
                PostAttachment::create([
                    'post_id' => $post->id,
                    'name' => $file->getClientOriginalName(),
                    'path' => $filePath,
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'created_by' => $user->id

                ]);

            }
            DB::commit();
        }catch (\Exception $e){
            foreach ($allFilePaths as $filePath){
                Storage::disk('public')->delete($filePath);
            }
            DB::rollBack();

            throw $e;
        }
        return  back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {

        $id = Auth::id();

        if ($post->isOwner($id) || $post->group && $post->group->isAdmin($id)){
           // dd($post , $post->isOwner($id));

            $post->delete();

            /*Notification **/
            if(!$post->isOwner($id)){
                $post->user->notify(new PostDeleted($post->group));
            }

            return  back();
        }
            return response(" You don't have permission to delete this post",403);
    }

    public function downloadAttachment(PostAttachment $attachment)
    {
        /*TODO Check if user has permission to download that attachment*/

        return response()->download(Storage::disk('public')->path($attachment->path), $attachment->name);
    }

    public function postReaction(Request $request, Post $post)
    {
        $data = $request->validate([
            'reaction' => [Rule::enum(ReactionEnum::class)]
        ]);

      $userId = Auth::id();

      $reaction =  Reaction::where('user_id', $userId)
          ->where('object_id',  $post->id)
          ->where('object_type', Post::class)
          ->first();
      //$reaction =  Reaction::where('user_id', $userId)->where('post_id',  $post->id)->first();

      if ($reaction){

          /*Delete the reaction*/
          $hasReaction = false;
          $reaction->delete();

      }else{
          /*Create the reaction*/
          $data = $request->validate([
              'reaction' => [Rule::enum(ReactionEnum::class)],
          ]);
          $hasReaction = true;

          Reaction::create([
              'object_id' =>   $post->id,  //post_id
              'object_type' => Post::class,
              'user_id' =>   $userId,
              'type'   =>    $data['reaction']
          ]);

          /**Notify user**/
          if (!$post->isOwner($userId)) {
              $user = User::where('id', $userId)->first();
              $post->user->notify(new ReactionAddedOnPost($post, $user));
          }

          $reactions =  Reaction::where('object_id',  $post->id)->where('object_type', Post::class)->count();
          //$reactions =  Reaction::where('object_id',  $post->id)->count();

          return response([
              'success'  => true,
              'num_of_reactions'=> $reactions, //no of reactions on the post,
              'current_user_has_reaction' =>$hasReaction
          ]);
      }

    }


    public function createComment(Request $request, Post $post)
    {
        $data = $request->validate([
            'comment' => ['required'],
            'parent_id' => ['nullable','exists:comments,id']
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'comment' => nl2br($data['comment']),
            'user_id' => Auth::id(),
            'parent_id' =>  $data['parent_id'] ? : null
        ]);

        /**Notify the owner of the comment**/
        $post = $comment->post;

        $post->user->notify(new CommentCreated($comment, $post));

        return response(new CommentResource($comment), 201);
    }

    public function updateComment(UpdateCommentRequest $request, Comment $comment)
    {
        $data = $request->validated();

        $comment->update([
            'comment' => nl2br($data['comment'])
        ]);

        return new CommentResource($comment) ;
    }

    /**$comment->isOwner($id) || $post->isOwner($id)  ||  $post->group && $post->group->isAdmin($id) **/
    public function deleteComment(Comment $comment)
    {
        $post = $comment->post;

        $id = Auth::id();
        if ($comment->isOwner($id) || $post->isOwner($id) ){
            $comment->delete();

            /*Notification  to user**/
            if(!$comment->isOwner($id)){
                $comment->user->notify(new CommentDeleted($comment, $post));
            }
            return  response('', 204);
        }
        return response("You don't have permission to delete this comment", 403);

       /* if ($comment->user_id != Auth::id()){
            return response("You don't have permission to delete this comment", 403);
        }     }

        return  response('', 204);*/
    }

    public function commentReaction(Request $request, Comment $comment)
    {
        $data = $request->validate([
            'reaction' => [Rule::enum(ReactionEnum::class)]
        ]);

        $userId = Auth::id();

        $reaction =  Reaction::where('user_id', $userId)
            ->where('object_id',  $comment->id)
            ->where('object_type', Comment::class)
            ->first();
        //$reaction =  Reaction::where('user_id', $userId)->where('post_id',  $post->id)->first();

        if ($reaction){

            /*Delete the reaction*/
            $hasReaction = false;
            $reaction->delete();

        }else{
            /*Create the reaction*/
            $data = $request->validate([
                'reaction' => [Rule::enum(ReactionEnum::class)],
            ]);
            $hasReaction = true;

            Reaction::create([
                'object_id' =>   $comment->id,  //post_id
                'object_type' => Comment::class,
                'user_id' =>   $userId,
                'type'   =>    $data['reaction']
            ]);


            /**Sent the notification to commennt owner**/
            if (!$comment->isOwner($userId)) {
                $user = User::where('id', $userId)->first();
                $comment->user->notify(new ReactionAddedOnComment($comment->post, $comment, $user));
            }

            $reactions =  Reaction::where('object_id',  $comment->id)->where('object_type', Comment::class)->count();
            //$reactions =  Reaction::where('object_id',  $post->id)->count();

            return response([
                'success'  => true,
                'num_of_reactions'=> $reactions, //no of reactions on the post,
                'current_user_has_reaction' =>$hasReaction
            ]);
        }
    }

    public function aiPostContent(Request  $request)
    {
        //dd($request);

        $prompt = $request->get('prompt');

        $result = OpenAI::chat()->create([
            'model' => 'gpt-4', //gpt-3.5-turbo
            'messages' => [
                [
                    'role' => 'user',
                    'content' => "Please generate social media post content based on the following prompt. Generated formatted content with multiple paragraphs. Put hashtags after 2 lines from the main content" . PHP_EOL . PHP_EOL . "Prompt: " . PHP_EOL
                        . $prompt

                ],
            ],
        ]);

        return response([
            'content' => $result->choices[0]->message->content
//            'content' => "\"🎉 Exciting news! We're thrilled to announce that we just released a brand new feature on our app/website! 💥 Get ready to experience the next level of convenience and efficiency with this game-changing addition. 🚀 Try it out now and let us know what you think! 😍 #NewFeatureAlert #UpgradeYourExperience\""
        ]);
    }

    public function fetchUrlPreview(Request $request)
     {
        $data = $request->validate([
            'url' => 'url'
        ]);

        $url = $data['url'];

        /**Make a request and fetch information*/
         $html = file_get_contents($url);

         /**Create Dom HTML*/
         $dom = new \DOMDocument();

         // Suppress warnings for malformed HTML
         libxml_use_internal_errors(true);

         // Load HTML content into the DOMDocument
         $dom->loadHTML($html);

         // Suppress warnings for malformed HTML
         libxml_use_internal_errors(false);

         $ogTags = [];
         $metaTags = $dom->getElementsByTagName('meta');
         foreach ($metaTags as $tag) {
             $property = $tag->getAttribute('property');
             if (str_starts_with($property, 'og:')) {
                 $ogTags[$property] = $tag->getAttribute('content');
             }
         }

         return $ogTags;
     }

    public function pinUnpin(Request $request,  Post $post)
    {

        $forGroup = $request->get('forGroup', false);
        $group = $post->group;

        if ($forGroup && !$group) {
            return response("Invalid Request", 400);
        }

        if ($forGroup && !$group->isAdmin(Auth::id())) {
            return response("You don't have permission to perform this action", 403);
        }

        $pinned = false;
        if ($forGroup && $group->isAdmin(Auth::id())) {
            if ($group->pinned_post_id === $post->id) {
                $group->pinned_post_id = null;
            } else {
                $pinned = true;
                $group->pinned_post_id = $post->id;
            }
            $group->save();
        }

        if (!$forGroup) {
            $user = $request->user();
            if ($user->pinned_post_id === $post->id) {
                $user->pinned_post_id = null;
            } else {
                $pinned = true;
                $user->pinned_post_id = $post->id;
            }
            $user->save();
        }

        return back()->with('success', 'Post was successfully ' . ( $pinned ? 'pinned' : 'unpinned' ));


    }
}
