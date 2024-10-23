<?php

namespace App\DTOs\Comment;

use App\DTOs\BaseDTO;

class CommentDTO extends BaseDTO
{
    public ?int $parent_comment_id;
    public int $commentable_id;
    public string $commentable_type;
    public ?string $body;
    public string $status;

    public function __construct($request)
    {
        $commentableId = $request->commentableType::where('slug', $request->slug)->first()->id;
        $this->parent_comment_id = $request['parentCommentId'];
        $this->commentable_id = $commentableId;
        $this->commentable_type = $request['commentableType'];
        $this->body =  $request['body'];
    }
}
