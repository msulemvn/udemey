<?php

namespace App\Http\Controllers\Comment;

use App\Models\Comment;
use App\Helpers\ApiResponse;
use App\Services\Comment\CommentService;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Controllers\Controller;


class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        ApiResponse::success(data: Comment::get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCommentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCommentRequest $request)
    {
        $response = $this->commentService->store($request);
        return $response['success'] ? ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? []) : ApiResponse::error(message: $response['message'] ?? null, errors: $response['errors'], request: $response['request'] ?? null, exception: $response['exception'] ?? null, statusCode: $response['statusCode'] ?? null);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCommentRequest  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $response = $this->commentService->update($request, $comment);
        return $response['success'] ? ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? []) : ApiResponse::error(message: $response['message'] ?? null, errors: $response['errors'], request: $response['request'] ?? null, exception: $response['exception'] ?? null, statusCode: $response['statusCode'] ?? null);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $response = $this->commentService->destroy($comment);
        return $response['success'] ? ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? []) : ApiResponse::error(message: $response['message'] ?? null, errors: $response['errors'], request: $response['request'] ?? null, exception: $response['exception'] ?? null, statusCode: $response['statusCode'] ?? null);
    }

    /**
     * Approve the specified comment.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function approve(Comment $comment)
    {
        $response = $this->commentService->approve($comment);
        return $response['success'] ? ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? []) : ApiResponse::error(message: $response['message'] ?? null, errors: $response['errors'], request: $response['request'] ?? null, exception: $response['exception'] ?? null, statusCode: $response['statusCode'] ?? null);
    }

    /**
     * Approve the specified comment.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function disapprove(Comment $comment)
    {
        $response = $this->commentService->disapprove($comment);
        return $response['success'] ? ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? []) : ApiResponse::error(message: $response['message'] ?? null, errors: $response['errors'], request: $response['request'] ?? null, exception: $response['exception'] ?? null, statusCode: $response['statusCode'] ?? null);
    }
}
