<?php

namespace App\View\Components\Common;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\View\Component;
use App\Services\Post\IPostService;

class PostRecent extends Component
{
    protected $postService;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(IPostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $viewName = 'components.post.post-recent';
        if (!View::exists($viewName)) {
            return '';
        }

        Request::merge(['page' => 1]);
        $posts = $this->postService->searchAndPaginate([
            'limit' => 8,
            'public' => 1,
            'status' => config('constant.post_status.publish'),
        ]);
        return view('components.post.post-recent', ['news' => $posts ?? []]);
    }
}
