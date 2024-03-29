<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'comment'     => $this->comment,
            'created_at'  => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'  => $this->updated_at->format('Y-m-d H:i:s'),
            'num_of_reactions' =>$this->reactions_count,
            'num_of_comments' =>$this->numOfComments,  //$this->comments_count,
             'current_user_has_reaction' =>$this->reactions->count() > 0,
             'comments' => $this->childComments, //each comment  has childComments
            //'comments' => CommentResource::collection($this->comments), //each comment  has childComments
            'user'        => [
                "id"       => $this->user->id,
                "name"     => $this->user->name,
                "username" => $this->user->username,
                "avatar_url" => $this->user->avatar_path ? Storage::url($this->user->avatar_path) : '/image/default_cover.webp',    //$this->avatar_path

            ] // new UserResource($this->user), //user relation on the comments
        ];
    }
}
