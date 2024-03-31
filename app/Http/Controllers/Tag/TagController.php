<?php

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\Controller;
use App\Services\Post\IPostService;
use App\Services\Tag\ITagService;
use App\Services\PostCategory\IPostCategoryService;
use App\Models\ConfigSeo;

class TagController extends Controller
{
    protected $postService, $tagService, $postCategoryService;

    /**
     * Constructor
     * @param Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(
        IPostService $postService,
        ITagService $tagService,
        IPostCategoryService $postCategoryService
    ) {
        $this->postService = $postService;
        $this->tagService = $tagService;
        $this->postCategoryService = $postCategoryService;
        $slug = request()->route('slug') ? request()->route('slug') : 'tin-tuc';
        $this->getConfigSeo($slug, 0, ConfigSeo::TAG);
        // Share view's data
        $viewFactory = view();
        $viewFactory->share('configSeo', $this->configSeo);
    }

    public function index($slug) {
        $tag = $this->tagService->getTagBySlug(['slug' => $slug]);
        if (empty($tag)) {
            return abort(404);
        }
        $posts = $this->postService->searchAndPaginate([
            'tag' => $tag->id,
            'limit' => 13,
            'public' => 1,
            'status' => config('constant.post_status.publish'),
        ]);

        $tags = $this->tagService->all();
        $topics = $this->postCategoryService->all();

        $this->tagService->increaseView($tag->id);

        return $this->view('pages.news.index', [
            'posts' => $posts,
            'tags' => $tags,
            'topics' => $topics
        ]);
    }
}
