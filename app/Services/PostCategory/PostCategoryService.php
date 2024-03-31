<?php

namespace App\Services\PostCategory;

use App\Helpers\SortHelper;
use App\Services\BaseService;
use Illuminate\Support\Facades\Log;
use App\Repositories\PostCategory\IPostCategoryRepository;
use App\Services\ConfigSeo\IConfigSeoService;
use App\Models\ConfigSeo;
use Illuminate\Support\Facades\DB;

class PostCategoryService extends BaseService implements IPostCategoryService
{
    protected $postCategoryRepository;
    protected $configSeoService;

    public function __construct(
        IPostCategoryRepository $postCategoryRepository,
        IConfigSeoService $configSeoService
    ) {
        $this->postCategoryRepository = $postCategoryRepository;
        $this->configSeoService = $configSeoService;
    }

    public function find($id, $columns = ['*'])
    {
        return $this->postCategoryRepository->find($id, $columns);
    }

    public function all($columns = array('*'))
    {
        return $this->postCategoryRepository->all($columns);
    }

    public function search(array $params)
    {
        $query = $this->postCategoryRepository->getModel()->query();

        if ( $keyword = data_get($params, 'keyword') ) {
            $query->where($query->qualifyColumn('title'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('slug'), 'LIKE', "%{$keyword}%");
        }

        $inIds = data_get($params, 'in_ids');
        if ($inIds && is_array($inIds)) {
            $query->whereIn($query->qualifyColumn('id'), $inIds);
        }

        if ( $public = data_get($params, 'public') ) {
            $query->where($query->qualifyColumn('public'), '=', "{$public}");
        }

        $query->orderBy('order', 'ASC');
        $query->orderBy('created_at', 'DESC');

        return $query->get();
    }

    public function searchAndPaginate(array $params)
    {
        $query = $this->postCategoryRepository->getModel()->query();

        if ( $keyword = data_get($params, 'keyword') ) {
            $query->where($query->qualifyColumn('title'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('slug'), 'LIKE', "%{$keyword}%");
        }

        // SortHelper::sortUpdated($query, $params);

        $query->orderBy('order', 'ASC');
        $query->orderBy('created_at', 'DESC');

        $limit = data_get($params, 'limit');
        $result = $query->paginate($limit);
        $result = $this->getParentName($result);
        return $result;
    }

    public function create(array $attributes)
    {
        $attributes = $this->formatData($attributes);
        return $this->postCategoryRepository->create($attributes);
    }

    public function update(array $attributes, $id)
    {
        $attributes = $this->formatData($attributes);
        return $this->postCategoryRepository->update($attributes, $id);
    }

    public function delete($id)
    {
        return $this->postCategoryRepository->delete($id);
    }

    private function formatData($params)
    {
        $result = $this->mergeAttributes($params);
        if (!isset($result['slug'])) {
            $result['slug'] = $this->slugGenerate($result['title']);
        }
        return $result;
    }

    private function mergeAttributes($params)
    {
        return array_merge(array (
            'public' => 0,
            'seo_noindex' => 0,
            'order' => 1,
        ), $params);
    }

    private function slugGenerate($name)
    {
        $slug = str_replace(" ", "-", $name);
        return strtolower($slug);
    }

    private function getParentName($result)
    {
        foreach ($result as $key => $item) {
            if ($item->parent_id) {
                $item->parent_name = $this->find($item->parent_id, 'title')->title;
            }
        }

        return $result;
    }

    public function getPostCategoryBySlug(string $slug)
    {
        try {
            $configSeo = $this->configSeoService->getConfigSeoURLBySlug($slug, 0, ConfigSeo::POST_CATEGORY);
            if ($configSeo) {
                return $this->postCategoryRepository->firstWhere(['id' => $configSeo->table_id]);
            }
        } catch (\Exception $e) {
            Log::error('get post category error: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stacktrace' => $e->getTraceAsString(),
            ]);
        }
        return null;
    }

    public function increaseView(int $id)
    {
        return $this->postCategoryRepository->update([
            'view' => DB::raw('view + 1')
        ], $id);
    }
}
