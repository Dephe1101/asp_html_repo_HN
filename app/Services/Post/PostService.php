<?php

namespace App\Services\Post;

use App\Services\ConfigSeo\IConfigSeoService;
use App\Repositories\Post\IPostRepository;
use Illuminate\Support\Facades\DB;
use App\Services\BaseService;
use Illuminate\Support\Facades\Log;
use App\Models\ConfigSeo;

class PostService extends BaseService implements IPostService
{
    protected $postRepository;
    protected $configSeoService;

    public function __construct(
        IPostRepository $postRepository,
        IConfigSeoService $configSeoService
    ) {
        $this->postRepository = $postRepository;
        $this->configSeoService = $configSeoService;
    }

    public function find($id, $columns = ['*'])
    {
        return $this->postRepository->find($id, $columns);
    }

    public function all($columns = ['*'])
    {
        return $this->postRepository->all($columns);
    }

    public function search(array $params)
    {
        $query = $this->postRepository->getModel()->query();

        if ($keyword = data_get($params, 'keyword')) {
            $query->where($query->qualifyColumn('title'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('brief'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('content'), 'LIKE', "%{$keyword}%");
        }

        $query->orderBy('order', 'ASC');
        $query->orderBy('created_at', 'DESC');

        return $query->get();
    }

    public function searchAndPaginate(array $params)
    {
        $query = $this->postRepository->getModel()->query()->with(['category', 'tags']);

        if ($keyword = data_get($params, 'keyword')) {
            $query->where($query->qualifyColumn('title'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('brief'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('content'), 'LIKE', "%{$keyword}%");
        }

        if ($categoryId = data_get($params, 'category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($publishedBy = data_get($params, 'published_by')) {
            $query->where('published_by', $publishedBy);
        }

        if ($creator = data_get($params, 'created_username')) {
            $query->where('created_username', $creator);
        }

        if ($tag = data_get($params, 'tag')) {
            $query->whereHas('tags', function ($q) use ($tag) {
                $q->where("tag_id", $tag);
            });
        }

        if ($status = data_get($params, 'status')) {
            $query->where('status', $status);
        }

        if ($public = data_get($params, 'public')) {
            $query->where('public', $public);
        }

        if ($publishedAt = data_get($params, 'published_at')) {
            // Input type: 2022/01/16 - 2023/01/17
            if (strpos($publishedAt, '/') > 0 && strpos($publishedAt, '-') > 7) {
                $range = explode('-', $publishedAt);
                $from = date("Y-m-d", strtotime(trim($range[0])));
                $to = date("Y-m-d", strtotime(trim($range[1])));
                $query->whereRaw("DATE(published_at) >= '${from}'");
                $query->whereRaw("DATE(published_at) <= '${to}'");
            } else {
                $publishedAt = date("Y-m-d", strtotime(trim($publishedAt)));
                $query->whereRaw("DATE(published_at) = '${publishedAt}'");
            }
        }

        // SortHelper::sortUpdated($query, $params);

        $query->orderBy('order', 'ASC');
        $orderBy = $params['orderBy']?? 'created_at';
        $orderSort = $params['orderSort']?? 'DESC';
        $query->orderBy($orderBy, $orderSort);

        $limit = data_get($params, 'limit', 10);
        $page = data_get($params, 'page', 1);
        if (!empty($params['onlyGetFirstRecordByLimit'])) {
            return $query->limit($limit)->get();
        }
        return $query->paginate($limit, ['*'], 'page', $page);
    }

    public function create(array $attributes)
    {
        $post = $this->postRepository->create($attributes);
        if ($tagIds = data_get($attributes, 'tag_ids')) {
            $post->tags()->sync($tagIds);
        }

        return $post;
    }

    public function update(array $attributes, $id)
    {
        $post = $this->postRepository->update($attributes, $id);
        if ($tagIds = data_get($attributes, 'tag_ids')) {
            $post->tags()->sync($tagIds);
        }

        return $post;
    }

    public function delete($id)
    {
        return $this->postRepository->delete($id);
    }

    public function getPostByUrl(string $url)
    {
        try {
            $configSeo = $this->configSeoService->getConfigSeoURLBySlug($url, 0, ConfigSeo::POST);
            return $this->postRepository->firstWhere(['id' => $configSeo->table_id]);
        } catch (\Exception $e) {
            Log::error('get post error: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stacktrace' => $e->getTraceAsString(),
            ]);
        }
        return null;
    }

    public function getAllPublishers() {
        return $this->postRepository
            ->whereNotNull("published_by")
            ->distinct("published_by")
            ->get(['published_by as name']);
    }

    public function getAllCreators() {
        return $this->postRepository
            ->whereNotNull("created_username")
            ->distinct("created_username")
            ->get(['created_username as name']);
    }


    public function getPostBySlug(array $params = [], array $options = [])
    {
        if ($slug = data_get($params, 'slug')) {
            $configSeo = $this->configSeoService->getConfigSeoURLBySlug($slug, 0, ConfigSeo::POST);
            if ($configSeo) {
                $post = $this->postRepository->with(['category', 'tags'])->findWhere([
                    'id' => $configSeo->table_id,
                    'public' => $params['public'] ?? 1,
                    'status' => $params['status'] ?? config('constant.post_status.publish'),
                ]);

                $post = $post->first();
                return $post;
            }
        }

        return null;
    }

    /**
     * get detail
     *
     * @param int $id
     * @return mixed
     */
    public function getPost(int $id)
    {
        return $this->postRepository->find($id);
    }

    public function getRelatedPosts(int $postId, array $params = []) {
        if ($postId && $params['category_id']) {
            $posts = $this->postRepository->where([
                'public' => $params['public'] ?? 1,
                'status' => $params['status'] ?? config('constant.post_status.publish'),
                'category_id' => $params['category_id'],
                'category_id' => $params['category_id'],
                ['id', '<>', $postId]
            ])->limit(3)->get();
            return $posts;
        }


        return [];
    }

    public function increaseView(int $id)
    {
        return $this->postRepository->update([
            'view' => DB::raw('view + 1')
        ], $id);
    }
}


