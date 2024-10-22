<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
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
     * @return mixed
     */
    public function index(IndexCommentRequest $request)
    {
        $response = $this->commentService->index($request);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  mixed
     * @return mixed
     */
    public function store(StoreCommentRequest $request)
    {
        $response = $this->commentService->store($request);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  mixed  $request
     * @param  mixed
     * @return mixed
     */
    public function update(UpdateCommentRequest $request)
    {
        $response = $this->commentService->update($request);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  mixed
     * @return mixed
     */
    public function destroy(DestroyCommentRequest $request)
    {
        $response = $this->commentService->destroy($request);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }
}
