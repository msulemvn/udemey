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

    public function __construct(mixed $data)
    {
        $this->parent_comment_id = $data['parentCommentId'];
        $this->commentable_id = $data['commentableId'];
        $this->commentable_type = $data['commentableType'];
        $this->body =  $data['body'];
        // $this->status = $data['status'] ?? 'pending';
    }
}
