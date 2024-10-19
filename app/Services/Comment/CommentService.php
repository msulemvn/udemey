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
            $comment = Comment::find($request->commentId);
            dd($comment);
            $comment->status = $request->status;
            $res = $comment->save();
            return ['success' => true, 'message' => $res ? 'successfully deleted' : 'failed to delete'];
        } catch (\Exception $e) {
            return ApiResponse::error(errors: ['destroy' => $e->getMessage()], request: $request, exception: $e);
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
            $res = $comment->delete();
            return ['success' => true, 'message' => $res ? 'successfully deleted' : 'failed to delete'];
        } catch (\Exception $e) {
            return ApiResponse::error(errors: ['destroy' => $e->getMessage()], request: $request, exception: $e);
        }
    }
}
