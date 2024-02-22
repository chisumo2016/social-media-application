<?php

namespace App\Http\Controllers;

use App\Http\Enum\ReactionEnum;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostAttachment;
use App\Models\Reaction;
use GuzzleHttp\Psr7\UploadedFile;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostController extends Controller
{

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
        //

        $id = Auth::id();
        if ($post->user_id != $id){
            return response(" You don't have permission to delete this post",403);
        }
        $post->delete();

        return  back();
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

    public function deleteComment(Comment $comment)
    {
        if ($comment->user_id != Auth::id()){
            return response("You don't have permission to delete this comment", 403);
        }

        $comment->delete();
        return  response('', 204);
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

            $reactions =  Reaction::where('object_id',  $comment->id)->where('object_type', Comment::class)->count();
            //$reactions =  Reaction::where('object_id',  $post->id)->count();

            return response([
                'success'  => true,
                'num_of_reactions'=> $reactions, //no of reactions on the post,
                'current_user_has_reaction' =>$hasReaction
            ]);
        }
    }
}
