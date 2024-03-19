<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $comments = $this->comments;

        return [
            'id'   => $this->id,
            'body' => $this->body,
            'preview' => $this->preview,
            'preview_url' => $this->preview_url,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'user' => new UserResource($this->user), //relationship
            'group' =>  new GroupResource($this->group) ,//$this->group ,//relationship
            'attachments' => PostAttachmentResource::collection($this->attachments) ,
            //'attachments' => $this->attachments ,//relationship
            'num_of_reactions' =>$this->reactions_count,
            'num_of_comments' =>count($comments),      //$this->comments_count,
            'current_user_has_reaction' =>$this->reactions->count() > 0,
            'comments' => self::convertCommentsIntoTree($comments) //latest5comments
            //'comments' => CommentResource::collection($this->comments) //latest5comments
        ];
    }

    /**
     *
     *
     * @param \App\Models\Comment[] $comments
     * @param                       $parentId
     * @return array
     *
     */
    private function convertCommentsIntoTree($comments, $parentId = null): array
    {
        $commentTree = [];

        foreach ($comments as $comment) {
            if ($comment->parent_id === $parentId) { //1st level comment
                /*Find all comment which has parentId as $comment->id*/
                $children = self::convertCommentsIntoTree($comments, $comment->id);

                $comment->childComments = $children ; //array

                $comment->numOfComments = collect($children)->sum('numOfComments') + count($children);

                $commentTree[] = new CommentResource($comment);
            }
        }

        return $commentTree;
    }
}

//var_dump($comment->id, collect($children)->sum('num_of_comments'), count($children));
