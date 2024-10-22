<?php

namespace App\Services\Page;

use App\DTOs\Page\PageDTO;
use App\Helpers\ApiResponse;
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
            return $response;
        } catch (\Exception $e) {
            return ApiResponse::error(request:$request, exception:$e);
        }
    }

    public function updatePage($pageId, array $request)
    {
        try {
            $response = Page::find($pageId);

            if (!$response) {
                return null;
            }

            $pageDTO = new PageDTO($request);

            $updateData = array_filter($pageDTO->toArray(), function ($value) {
                return !is_null($value);
            });

            $response->update($updateData);
            return $response;
        } catch (\Exception $e) {
            return ApiResponse::error(request:$request, exception:$e);
        }
    }

    public function getPageBySlug(string $slug)
    {
        $response = Page::where('slug', $slug)->first();
        return $response  ? ['success' => true, 'message' => '', 'data' => $response] : [
            'success' => false,
            'message' => 'Page not found!',
            'errors' => ['error' => ['The requested page does not exist.']],
            'statusCode' => Response::HTTP_NOT_FOUND,
        ];
    }

    public function getPageById($pageId)
    {
        $response = Page::where('id', $pageId)->first();
        return $response  ? ['success' => true, 'message' => '', 'data' => $response] : [
            'success' => false,
            'message' => 'Page not found!',
            'errors' => ['error' => ['The requested page does not exist.']],
            'statusCode' => Response::HTTP_NOT_FOUND,
        ];
    }

    public function getPages()
    {
        try {
            $response = Page::all();
            return $response;
        } catch (\Exception $e) {
            return ApiResponse::error(request:null, exception:$e);
        }
    }

    public function deletePage(int $id)
    {
        try {
            $response = Page::find($id);

            if (!$response) {
                return null;
            }
            return $response->delete();
        } catch (\Exception $e) {
            return ApiResponse::error(request:null, exception:$e);
        }
    }

    public function restorePage($id)
    {
        try {
            $response = Page::withTrashed()->find($id);

            if (!$response) {
                return null;
            }
            $response->restore();
            return $response;
        } catch (\Exception $e) {
            return ApiResponse::error(request:null, exception:$e);
        }
    }
}
