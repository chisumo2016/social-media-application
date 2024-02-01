<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\PostAttachment;
use GuzzleHttp\Psr7\UploadedFile;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $post->update($request->validated());

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
}
