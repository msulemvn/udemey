<?php

namespace App\Services\Comment;

use App\Models\Comment;
use App\DTOs\Comment\CommentDTO;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\Comment\CommentServiceInterface;

class CommentService implements CommentServiceInterface
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($request)
    {
        if ($request->commentableType) {
            if (!class_exists($request->commentableType)) {
                return ['success' => false, 'errors' =>  ['commentableType' => ['The model does not exist.']]];
            } else {
                if ($request->commentableId) {
                    return ['success' => true, 'data' =>  Comment::where('commentable_type', $request->commentableType)->find($request->commentableId)->toArray()];
                } else {
                    return ['success' => true, 'data' =>  Comment::where('commentable_type', $request->commentableType)->get()->toArray()];
                }
            }
        } else if ($request->commentableId) {
            return ['success' => true, 'data' =>  Comment::find($request->commentableId)->toArray()];
        } else {
            return ['success' => true, 'data' =>  Comment::get()->toArray()];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCommentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store($request)
    {
        $user = Auth::user(); // Get the authenticated user
        $commentDTO = new CommentDTO($request);
        /** @var \App\User|null $user */
        $result = $user->comments()->create($commentDTO->toArray());

        if ($result) {
            return ['success' => true, 'data' => $result->toArray()];
        } else {
            return ['success' => false, 'errors' => $result->errors()->all()];
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCommentRequest  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update($request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }

    /**
     * Approve the specified comment.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function approve(Comment $comment)
    {
        //
    }

    /**
     * Approve the specified comment.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function disapprove(Comment $comment)
    {
        //
    }
}
