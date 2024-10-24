<?php

namespace App\Services\Comment;

use App\Models\Comment;
use App\Helpers\ApiResponse;
use App\DTOs\Comment\CommentDTO;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    /**
     * Summary of index
     * @param mixed $request
     * @return array[]
     */
    public function index($request)
    {
        //handled in validation if slug exists
        $commentableId = $request->commentableType::where('slug', $request->slug)->first()->id;
        return ['data' =>  Comment::where('commentable_type', $request->commentableType)
            ->when($commentableId, function ($query) use ($commentableId) {
                return $query->where('commentable_id', $commentableId);
            })->when($request->commentId, function ($query) use ($request) {
                return $query->where('id', $request->commentId);
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
    public function store($request)
    {
        try {
            $user = Auth::user(); // Get the authenticated user
            /** @var \App\Models\User|null $user */
            $data = $user->comments()->create((new CommentDTO($request))->toArray())->toArray();
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
