<?php

namespace App\Services\Comment;

use App\Models\Comment;
use App\Helpers\ApiResponse;
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
        return ['success' => true, 'data' =>  Comment::where('commentable_type', $request->commentableType)
            ->when($request->commentableId, function ($query) use ($request) {
                $query->where('id', $request->commentableId);
            })
            ->get()
            ->toArray()];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCommentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store($request)
    {
        try {
            $user = Auth::user(); // Get the authenticated user
            $commentDTO = new CommentDTO($request);
            /** @var \App\User|null $user */
            $data = $user->comments()->create($commentDTO->toArray())->toArray();
            return ['success' => true, 'data' => $data];
        } catch (\Exception $e) {
            return ApiResponse::error(errors: ['create' => $e->getMessage()], request: $request, exception: $e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCommentRequest  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update($request)
    {
        try {
            $data = $comment = Comment::find($request->commentId);
            $comment->status = $request->status;
            $result = $comment->save();
            return ['success' => true, 'message' => $result ? 'successfully updated' : 'failed to update', 'data' => $data->toArray()];
        } catch (\Exception $e) {
            return ['success' => false, 'errors' => ['status' => $e->getMessage()], 'request' => $request, 'exception' => $e, 'statusCode' => 500];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy($request)
    {
        try {
            $comment = Comment::find($request->commentId);
            $result = $comment->delete();
            return ['success' => true, 'message' => $result ? 'Commented deleted successfully.' : 'Failed to delete comment.'];
        } catch (\Exception $e) {
            return ApiResponse::error(errors: ['destroy' => $e->getMessage()], request: $request, exception: $e);
        }
    }
}
