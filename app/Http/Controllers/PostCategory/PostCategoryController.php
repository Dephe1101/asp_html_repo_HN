<?php

namespace App\Http\Controllers\PostCategory;

use App\Http\Controllers\Controller;
use App\Services\Post\IPostService;
use App\Services\Tag\ITagService;
use App\Services\Config\IConfigService;
use App\Services\PostCategory\IPostCategoryService;
use App\Models\ConfigSeo;

class PostCategoryController extends Controller
{
    protected $configService, $postService, $tagService, $postCategoryService;

    /**
     * Constructor
     * @param Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(
        IConfigService $configService,
        IPostService $postService,
        ITagService $tagService,
        IPostCategoryService $postCategoryService
    ) {
        $this->configService = $configService;
        $this->postService = $postService;
        $this->tagService = $tagService;
        $this->postCategoryService = $postCategoryService;
        $slug = request()->route('slug') ? request()->route('slug') : 'tin-tuc';
        $this->getConfigSeo($slug, 0, ConfigSeo::POST_CATEGORY);

        // Share view's data
        $viewFactory = view();
        $viewFactory->share('configSeo', $this->configSeo);
    }

    public function index($slug) {
        $postCategory = $this->postCategoryService->getPostCategoryBySlug($slug);
        if (empty($postCategory)) {
            return abort(404);
        }

        $perPage = 12;
        $perPageOnService = $this->configService->getConfigByKey(
            'general_config',
            'pagination-per-page'
        );
        $perPage = isset($perPageOnService)
            && isset($perPageOnService->value)
            && !empty($perPageOnService->value)
            ? $perPageOnService->value
            : $perPage;

        $posts = $this->postService->searchAndPaginate([
            'category_id' => $postCategory->id,
            'limit' => $perPage,
            'public' => 1,
            'status' => config('constant.post_status.publish'),
        ]);

        $tags = $this->tagService->all();
        $topics = $this->postCategoryService->all();

        $this->postCategoryService->increaseView($postCategory->id);

        $view = $this->view('pages.news.index', ['posts' => $posts, 'tags' => $tags, 'topics' => $topics])->render();
        return $view;
    }
}
