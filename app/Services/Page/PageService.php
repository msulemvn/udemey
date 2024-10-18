<?php

namespace App\Services\Page;

use Exception;
use Throwable;
use App\Models\Page;
use App\DTOs\Page\PageDTO;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PageService
{
    public function createPage($data)
    {
        try {
            $response = Page::create(
                (new PageDTO($data))->toArray()
            );
            return [
                'success' => true,
                'data' => $response
            ];
        } catch (Throwable $th) {
            return [
                'success' => false,
                'errors' => [
                    'page' => [
                        'An error occurred while creating the page. Please try again.'
                    ]
                ],
                'exception' => $th
            ];
           
        }
    }
    public function updatePage($id, $data)
    {
        try {
            $response = Page::findOrFail($id);
            $updateData = [];
            if (array_key_exists('body', $data) && !is_null($data['body'])) {
                $updateData['body'] = $data['body'];
            }
    
            if (!empty($updateData)) {
                $response->update($updateData);
            }
    
            return [
                'success' => true,
                'data' => $response
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'success' => false,
                'errors' => [
                    'page' => ['No page found.']
                ],
                'exception' => $e
            ];
        } catch (Throwable $th) {
            return [
                'success' => false,
                'errors' => [
                    'page' => ['An error occurred while updating the page.']
                ],
                'exception' => $th
            ];
        }
    }
    
    public function getPageBySlug(string $slug)
    {
        $response = Page::where('slug', $slug)->first();
        return $response  ? ['success' => true, 'data' => $response] : [
            'success' => false,
            'message' => 'Page not found!',
            'errors' => ['page' => ['The requested page does not exist.']],
            'statusCode' => Response::HTTP_NOT_FOUND,
        ];
    }

    public function getPageById($pageId)
    {
        $response = Page::where('id', $pageId)->first();
        return $response  ? ['success' => true, 'message' => '', 'data' => $response] : [
            'success' => false,
            'message' => 'Page not found!',
            'errors' => ['page' => ['The requested page does not exist.']],
            'statusCode' => Response::HTTP_NOT_FOUND,
        ];
    }

    public function getPages()
    {
        try {
            $response = Page::all();
            return [
                'success' => true,
                'data' => $response
            ];
        } catch (Throwable $th) {
            return [
                'success' => false,
                'message' => 'No pages found!',
                'errors' => ['page' => [
                    'The requested page does not exist.'
                    ]
                ],
                'statusCode' => Response::HTTP_NOT_FOUND,
                'exception' => $th
            ];
        }
    }

    public function deletePage(int $pageId)
    {
        try {
            $response = Page::findOrFail($pageId);
            if (!$response) {
                return [
                    'success' => false,
                    'message' => 'Page not found!',
                    'errors' => ['page' => [
                        'The requested page does not exist.'
                        ]
                    ],
                    'statusCode' => Response::HTTP_NOT_FOUND
                ];
            }
            $response->delete();
            return [
                'success' => true,
                'data' => $response
            ];
        } catch (Throwable $th) {
            return [
                'success' => false,
                'message' => 'Page deletion failed',
                'errors' => ['page' => [
                    'An error occurred while deleting the page.'
                    ]
                ],
                'statusCode' => Response::HTTP_NOT_FOUND,
                'exception' => $th
            ];
        }
    }

    public function restorePage($pageId)
    {
        try {
            $page = Page::onlyTrashed()->find($pageId);
            if (!$page) {
                throw new Exception("No settings found for the Id " . $pageId);
            }
            $page->restore();
            return [
                'success' => true,
                'data' => $page
            ];
        } catch (Throwable $th) {
            return [
                'success' => false,
                'message' => 'Page restoration failed',
                'errors' => ['page' => [
                    'An error occurred while restoring the page'
                    ]
                ],
                'statusCode' => Response::HTTP_NOT_FOUND,
                'exception' => $th
            ];
        }
    }
}
