<?php

namespace App\Http\Controllers\Comment;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Comment\CommentService;
use App\Http\Requests\Comment\IndexCommentRequest;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Requests\Comment\DestroyCommentRequest;

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
    public function index(IndexCommentRequest $request)
    {
        $response = $this->commentService->index($request);
        return $response['success'] ? ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? []) : ApiResponse::failure(message: $response['message'] ?? null, errors: $response['errors'] ?? null);
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
        return $response['success'] ? ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? []) : ApiResponse::failure(message: $response['message'] ?? null, errors: $response['errors'] ?? null);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCommentRequest  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCommentRequest $request)
    {
        $response = $this->commentService->update($request);
        return $response['success'] ? ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? []) : ApiResponse::failure(message: $response['message'] ?? null, errors: $response['errors'] ?? null);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyCommentRequest $request)
    {
        $response = $this->commentService->destroy($request);
        return $response['success'] ? ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? []) : ApiResponse::failure(message: $response['message'] ?? null, errors: $response['errors'] ?? null);
    }
}
