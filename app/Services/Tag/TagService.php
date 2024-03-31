<?php

namespace App\Services\Tag;

use App\Models\ConfigSeo;
use Illuminate\Support\Facades\DB;
use App\Services\BaseService;
use App\Repositories\Tag\ITagRepository;
use App\Services\ConfigSeo\IConfigSeoService;

class TagService extends BaseService implements ITagService
{
    protected $tagRepository;
    protected $configSeoService;

    public function __construct(
        ITagRepository $tagRepository,
        IConfigSeoService $configSeoService
    ) {
        $this->tagRepository = $tagRepository;
        $this->configSeoService = $configSeoService;
    }

    public function find($id, $columns = ['*'])
    {
        return $this->tagRepository->find($id, $columns);
    }

    public function all($columns = array('*'))
    {
        return $this->tagRepository->all($columns);
    }

    public function search(array $params)
    {
        $query = $this->tagRepository->getModel()->query();

        if ( $keyword = data_get($params, 'keyword') ) {
            $query->where($query->qualifyColumn('route_name'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('note'), 'LIKE', "%{$keyword}%");
        }

        $query->orderBy('order', 'ASC');
        $query->orderBy('created_at', 'DESC');

        return $query->get();
    }

    public function searchAndPaginate(array $params)
    {
        $query = $this->tagRepository->getModel()->query();

        if ( $keyword = data_get($params, 'keyword') ) {
            $query->where($query->qualifyColumn('name'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('seo_title'), 'LIKE', "%{$keyword}%");
        }

        // SortHelper::sortUpdated($query, $params);

        $limit = data_get($params, 'limit');

        return $query->paginate($limit);
    }

    public function create(array $attributes)
    {
        $attributes = $this->formatData($attributes);
        return $this->tagRepository->create($attributes);
    }

    public function update(array $attributes, $id)
    {
        $attributes = $this->formatData($attributes);
        return $this->tagRepository->update($attributes, $id);
    }

    public function delete($id)
    {
        return $this->tagRepository->delete($id);
    }

    private function formatData($params)
    {
        $result = $this->mergeAttributes($params);
        if (!isset($result['slug'])) {
            $result['slug'] = $this->slugGenerate($result['name']);
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

    public function getTagBySlug(array $params = []) {
        try {
            if ($slug = data_get($params, 'slug')) {
                $configSeo = $this->configSeoService->getConfigSeoURLBySlug($slug, 0, ConfigSeo::TAG);
                if (!isset($configSeo)) {
                    return null;
                }

                return $this->tagRepository->firstWhere(['id' => $configSeo->table_id]);
            }
        } catch (\Exception $e) {
            Log::error('get tag error: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stacktrace' => $e->getTraceAsString(),
            ]);
        }

        return null;
    }

    /**
     * get detail
     *
     * @param int $id
     * @return mixed
     */
    public function getTag(int $id)
    {
        return $this->tagRepository->find($id);
    }

    public function increaseView(int $id)
    {
        return $this->tagRepository->update([
            'view' => DB::raw('view + 1')
        ], $id);
    }
}
