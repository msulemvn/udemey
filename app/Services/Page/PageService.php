<?php

namespace App\Services\Page;

use App\DTOs\Page\PageDTO;
use App\Helpers\ApiResponse;
use App\Http\Resources\Page\PageResource;
use App\Models\Page;
use Symfony\Component\HttpFoundation\Response;

class PageService
{
    public function createPage($request)
    {
        try {
            $response = Page::create(
                (new PageDTO($request))->toArray()
            );
            $resource  = new PageResource($response);
            return [
                'success' => true,
                'data' => $resource->toArray($request),
            ];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }

    public function updatePage($pageId, array $request)
    {
        try {
            $response = Page::find($pageId);

            if (!$response) {
                return [
                    'success' => false,
                    'message' => 'page not found',
                    'errors' => [
                        'page' => [
                            'There is no page with this id.'
                        ]
                    ],
                    'statusCode' => Response::HTTP_NOT_FOUND,
                ];
            }

            $pageDTO = new PageDTO($request);

            $updateData = array_filter($pageDTO->toArray(), function ($value) {
                return !is_null($value);
            });
            $response->update($updateData);
            $resource  = new PageResource($response);
            return [
                'success' => true,
                'message' => 'updated successfully!',
                'data' => $resource->toArray($request)
            ];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }

    public function getPageBySlug(string $slug)
    {
        $response = Page::where('slug', $slug)->first();
        $resource  = new PageResource($response);
        return $response  ? [
            'success' => true,
            'data' => $resource->toArray($slug)
        ] :
        [
            'success' => false,
            'message' => 'Page not found',
            'errors' => ['page' => ['The requested page does not exist.']],
            'statusCode' => Response::HTTP_NOT_FOUND,
        ];
    }

    public function getPageById($id)
    {
        $response = Page::where('id', $id)->first();
        $resource  = new PageResource($response);

        return $response  ? [
            'success' => true,
            'data' => $resource->toArray($id)
        ] :
        [
            'success' => false,
            'errors' => ['page' => ['The requested page does not exist.']],
            'statusCode' => Response::HTTP_NOT_FOUND,
        ];
    }

    public function getPages()
    {
        try {
            $response = Page::all();
            $resource = PageResource::collection($response);
            return $response  ? [
                'success' => true,
                'data' => $resource
            ] :
            [
                'success' => false,
                'errors' => ['page' => ['The requested page does not exist.']],
                'statusCode' => Response::HTTP_NOT_FOUND,
            ];
        } catch (\Exception $e) {
            return ApiResponse::error(request: null, exception: $e);
        }
    }

    public function deletePage(int $id)
    {
        try {
            $response = Page::find($id);

            if (!$response) {
                return [
                    'success' => false,
                    'errors' => ['page' => ['The requested page does not exist.']],
                    'message' => 'page not found',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                ];
            }
            $response->delete();
            return [
                'success' => true,
                'message' => 'page deleted successfully!'
            ];
        } catch (\Exception $e) {
            return ApiResponse::error(request: null, exception: $e);
        }
    }

    public function restorePage($id)
    {
        try {
            $response = Page::withTrashed()->find($id);

            if (!$response) {
                return [
                    'success' => false,
                    'errors' => ['page' => ['The requested page does not exist.']],
                    'message' => 'Page not found',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                ];
            }
            if (!$response->trashed()) {
                return [
                    'success' => false,
                    'message' => 'Page is already active and does not need restoration.',
                    'errors' => ['page' => ['The requested page does not exist.']],
                    'statusCode' => Response::HTTP_BAD_REQUEST,
                ];
            }
            $response->restore();

            return [
                'success' => true,
                'message' => 'Page restored successfully!',
            ];
        } catch (\Exception $e) {
            return ApiResponse::error(request: null, exception: $e);
        }
    }
}
