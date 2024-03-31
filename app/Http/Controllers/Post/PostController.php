<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Services\Post\IPostService;
use Illuminate\Http\Request;
use App\Services\Tag\ITagService;
use App\Services\PostCategory\IPostCategoryService;
use App\Models\ConfigSeo;
use App\Repositories\Post\IPostRepository;
use App\Helpers\SeoHelper;

class PostController extends Controller
{
    protected $postRepository, $postService, $tagService, $postCategoryService;

    /**
     * Constructor
     * @param Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(
        IPostRepository $postRepository,
        IPostService $postService,
        ITagService $tagService,
        IPostCategoryService $postCategoryService
    ) {
        $this->postRepository = $postRepository;
        $this->postService = $postService;
        $this->tagService = $tagService;
        $this->postCategoryService = $postCategoryService;
        $slug = request()->route('slug') ? request()->route('slug') : 'tin-tuc';
        if ($slug === 'tin-tuc') {
            $this->getConfigSeo($slug, 0, ConfigSeo::POST_CATEGORY);
        } else {
            $this->getConfigSeo($slug, 0, ConfigSeo::POST);
        }
        // Share view's data
        $viewFactory = view();
        $viewFactory->share('configSeo', $this->configSeo);
    }

    public function index(Request $request)
    {
        $posts = $this->postService->searchAndPaginate([
            'limit' => 12,
            'public' => 1,
            'status' => config('constant.post_status.publish'),
        ]);
        foreach ($posts as &$p) {
            $p->published_by = SeoHelper::getPublishedUser();
            $p->created_username = SeoHelper::getPublishedUser();
        }

        $tags = $this->tagService->all();
        $topics = $this->postCategoryService->all();

        $view = $this->view('pages.news.index', ['posts' => $posts, 'tags' => $tags, 'topics' => $topics])->render();
        return $view;
    }

    public function detail($slug)
    {
        $post = $this->postService->getPostBySlug([
            'slug' => $slug,
        ]);
        if (empty($post)) {
            return abort(404);
        }
        $post->published_by = SeoHelper::getPublishedUser();
        $post->created_username = SeoHelper::getPublishedUser();

        if (!empty($post)) {
            $relatedPosts = $this->postService->getRelatedPosts($post->id, ['category_id' => $post->category_id]);
            if (!config('responsecache.enabled')) { // if True: increment in responsecache replacer
                $query = $this->postRepository->getModel()->query();
                $query->where('id', $post->id)->increment('view', 1);
            }

            foreach ($relatedPosts as &$p) {
                $p->published_by = SeoHelper::getPublishedUser();
                $p->created_username = SeoHelper::getPublishedUser();
            }
        }

        $configSeoDetail = SeoHelper::getConfigSeoBySlug(ConfigSeo::SEO_DETAIL_PAGE, 1);
        view()->share('postSeo', $post);
        $view = $this->view('pages.news.detail', [
            'post' => $post,
            'relatedPosts' => !empty($relatedPosts) ? $relatedPosts : [],
            'configSeoDetail' => $configSeoDetail,
        ])->render();
        return $view;
    }
}
