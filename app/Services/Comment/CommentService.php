<?php

namespace App\Services\Comment;

use App\Models\Comment;
use App\Helpers\ApiResponse;
use App\DTOs\Comment\CommentDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    /**
     * Get data
     * @param mixed $request
     * @return array[]
     */
    public function index($request)
    {
        return ['data' =>  Comment::where('commentable_type', $request->commentableType)
            ->when($request->commentableId, function ($query) use ($request) {
                $query->where('id', $request->commentableId);
            })
            ->get()
            ->toArray()];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  mixed  $request
     * @return mixed
     */
    public function store($request): array|JsonResponse
    {
        try {
            $user = Auth::user(); // Get the authenticated user
            $commentDTO = new CommentDTO($request);
            /** @var \App\Models\User|null $user */
            $data = $user->comments()->create($commentDTO->toArray())->toArray();
            return ['data' => $data];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  mixed  $request
     * @return mixed
     */
    public function update($request)
    {
        try {
            $data = $comment = Comment::find($request->commentId);
            $comment->status = $request->status;
            $result = $comment->save();
            return ['message' => $result ? 'successfully updated' : 'failed to update', 'data' => $data->toArray()];
        } catch (\Exception $e) {
            return ['errors' => ['status' => $e->getMessage()], 'request' => $request, 'exception' => $e, 'statusCode' => 500];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  mixed
     * @return mixed
     */
    public function destroy($request)
    {
        try {
            $comment = Comment::find($request->commentId);
            $result = $comment->delete();
            return ['message' => $result ? 'Commented deleted successfully.' : 'Failed to delete comment.'];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }
}
