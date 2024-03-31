<?php

namespace App\Http\Controllers\API;

use App\Services\Post\IPostService;
use Illuminate\Http\Request;
use App\Helpers\SeoHelper;
use Illuminate\Support\Facades\Validator;
use Exception;

class PostController extends BaseApiController
{

    protected $postService;


    public function __construct(
        IPostService  $postService,
    ) {
        $this->postService = $postService;
    }
    /**
     * Load more sim for homepage.
     *
     */
    public function loadMore(Request $request)
    {
        try {
            $inputs = $request->input();
            $this->validateLoadMore($inputs);
            $posts = $this->postService->searchAndPaginate([
                'limit' => 12,
                'page' => $request->query('page', 2),
                'public' => 1,
                'status' => config('constant.post_status.publish'),
            ]);

            foreach ($posts as &$p) {
                $p->published_by = SeoHelper::getPublishedUser();
                $p->created_username = SeoHelper::getPublishedUser();
                $p->image = $p->configSeo->image ?? '';
                $p->slug = $p->configSeo->url ?? '';
            }

            return $this->responseSuccess($posts);
        } catch (\Exception $e) {
            return $this->responseFailure($e->getMessage());
        }
    }

    private function validateLoadMore($inputs)
    {
        $validator = Validator::make(
            $inputs,
            [
                'page' => [
                    'integer',
                    'min:1',
                    'nullable'
                ],
            ]
        );
        if ($validator->fails()) {
            throw new Exception($validator->messages()->first(), 0);
        }
    }
}
