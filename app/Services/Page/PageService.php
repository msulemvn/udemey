<?php

namespace App\Services\Page;

use Exception;
use App\Models\Page;
use App\DTOs\Page\PageDTO;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Page\PageResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PageService
{
    public function store($request)
    {
        
        try {
            $response = Page::create((new PageDTO($request))->toArray());
            $resource  = new PageResource($response);
            return ['data' => $resource->toArray($request), 'message' => 'Page created successfully!'];
        } catch (\Exception $e) {
            return ApiResponse::error(request:$request, exception:$e);
        }
    }

    public function update($request, $id)
    {
        try {
            $response = Page::findOrFail($id);
            $pageDTO = new PageDTO($request);
            $updateData = array_filter($pageDTO->toArray(), function ($value) {
                return !is_null($value);
            });
            $response->update($updateData);
            $resource  = new PageResource($response);
            return ['message' => 'Page updated successfully!', 'data' => $resource->toArray($request)];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }

    public function destroy(int $id)
    {
        try {
            $page = Page::findOrFail($id);
            if ($page->trashed()) {
                return [
                    'message' => 'Page already deleted',
                    'errors' => ['page' => ['The requested page has already been deleted.']],
                    'statusCode' => Response::HTTP_BAD_REQUEST,
                ];
            }

            DB::transaction(function () use ($page) {
                $page->delete();
            });
            
            return ['message' => 'Page deleted successfully!','statusCode' => Response::HTTP_OK];

        } catch (ModelNotFoundException $e) {
            return [
                'message' => 'Page not found',
                'errors' => ['page' => ['The requested page does not exist.']],
                'statusCode' => Response::HTTP_NOT_FOUND,
            ];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $id, exception: $e);
        }
    }

    public function restore($id)
    {
        try {
            $response = Page::withTrashed()->find($id);
            if (!$response) {
                return [
                    'errors' => ['page' => ['The requested page does not exist.']],
                    'message' => 'Page not found',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                ];
            }
            if (!$response->trashed()) {
                return [
                    'message' => 'Page is already active and does not need restoration.',
                    'errors' => ['page' => ['The requested page is already active.']],
                    'statusCode' => Response::HTTP_BAD_REQUEST,
                ];
            }
            $response->restore();

            return [
                'message' => 'Page restored successfully!',
                'statusCode' => Response::HTTP_OK,
            ];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $id, exception: $e);
        }
    }

    public function index($request)
    {
        try {
            if ($request->id && $request->slug) {
                return ['message' => 'Both ID and slug cannot be provided','data' => [],];
            }
            $query = Page::query();
            $query->when($request->id, function ($query) use ($request) {
                $query->where('id', $request->id);
            })->when($request->slug, function ($query) use ($request) {
                $query->where('slug', $request->slug);
            });

            $response = $request->id || $request->slug
            ? new PageResource($query->firstOrFail())
            : PageResource::collection($query->get());
            return ['message' => 'Page(s) retrieved successfully','data' => $response];
        } catch (Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }

}
