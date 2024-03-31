<?php

namespace App\Services\ConfigSeo;

use App\Helpers\SortHelper;
use App\Services\BaseService;
use Illuminate\Support\Facades\Log;
use App\Repositories\ConfigSeo\IConfigSeoRepository;

class ConfigSeoService extends BaseService implements IConfigSeoService
{
    protected $configSeoRepository;

    public function __construct(IConfigSeoRepository $configSeoRepository)
    {
        $this->configSeoRepository = $configSeoRepository;
    }

    public function find($id, $columns = ['*'])
    {
        return $this->configSeoRepository->find($id, $columns);
    }

    public function all($columns = array('*'))
    {
        return $this->configSeoRepository->all($columns);
    }

    public function search(array $params)
    {
        $query = $this->configSeoRepository->getModel()->query();
        $is_keysearch = data_get($params, 'is_keysearch') ?? 0;

        $query->where($query->qualifyColumn('is_keysearch'), $is_keysearch);
        if ($keyword = data_get($params, 'keyword')) {
            $query->where($query->qualifyColumn('url'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('title'), 'LIKE', "%{$keyword}%");
        }

        $query->orderBy('order', 'ASC');
        $query->orderBy('created_at', 'DESC');

        return $query->all();
    }

    public function searchAndPaginate(array $params)
    {
        $query = $this->configSeoRepository->getModel()->query();
        $is_keysearch = data_get($params, 'is_keysearch') ?? 0;

        $query->where($query->qualifyColumn('is_keysearch'), $is_keysearch);
        if ($keyword = data_get($params, 'keyword')) {
            $query->where($query->qualifyColumn('url'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('title'), 'LIKE', "%{$keyword}%");
        }

        // SortHelper::sortUpdated($query, $params);

        $query->orderBy('order', 'ASC');
        $query->orderBy('created_at', 'DESC');

        $limit = data_get($params, 'limit');
        $result = $query->paginate($limit);
        return $result;
    }

    public function create(array $attributes)
    {
        $attributes = $this->formatData($attributes);
        return $this->configSeoRepository->create($attributes);
    }

    public function update(array $attributes, $id)
    {
        return $this->configSeoRepository->update($attributes, $id);
    }

    public function delete($id)
    {
        return $this->configSeoRepository->delete($id);
    }

    private function formatData($params)
    {
        $result = $this->mergeAttributes($params);
        return $result;
    }

    private function mergeAttributes($params)
    {
        return array_merge(array(
            'public' => 0,
            'order' => 1,
            'seo_noindex' => 0,
            'is_keysearch' => 0,
        ), $params);
    }

    public function getConfigSeoURLBySlug (string $slug, int $isKeySearch = 1, $entity = '')
    {
        try {
            $query = $this->configSeoRepository->getModel()->query();
            $query->where($query->qualifyColumn('url'), $slug)->where($query->qualifyColumn('is_keysearch'), $isKeySearch)->where($query->qualifyColumn('public'), 1);
            if ($entity !== '') {
                $query->where($query->qualifyColumn('table_name'), $entity);
            }
            return $query->first();
        } catch (\Exception $e) {
            Log::error('get config seo error: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stacktrace' => $e->getTraceAsString(),
            ]);
        }
        return null;
    }

    public function getConfigSeoByEntity (string $tableName, int $tableId, int $isKeySearch = 0)
    {
        try {
            $query = $this->configSeoRepository->getModel()->query();
            $query
                ->where($query->qualifyColumn('table_name'), $tableName)
                ->where($query->qualifyColumn('table_id'), $tableId)
                ->where($query->qualifyColumn('is_keysearch'), $isKeySearch);
            
            return $query->first();
        } catch (\Exception $e) {
            Log::error('get config seo error: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stacktrace' => $e->getTraceAsString(),
            ]);
        }
        return null;
    }

    public function createConfig(array $attributes)
    {
        return $this->configSeoRepository->create($attributes);
    }

    public function updateConfig(array $attributes, $id)
    {
        return $this->configSeoRepository->update($attributes, $id);
    }

    /**
     * @param string $url
     * @param string $tableName
     * @param Integer $d
     * @return mixed
     */
    public function isDuplicateConfigSeo($url, $tableName, $id = null)
    {
        try {
            $query = $this->configSeoRepository->getModel()->query();
            if (!empty($id)) {
                $query = $query->where('id', '<>', $id);
            }
            
            return $query
                ->where($query->qualifyColumn('url'), $url)
                ->where($query->qualifyColumn('table_name'), $tableName)->get()->toArray();
        } catch (\Exception $e) {
            Log::error('get config seo error: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stacktrace' => $e->getTraceAsString(),
            ]);
        }
        return [];
    }

    /**
     * get list config seo
     *
     * @param array $conditions
     * @return mixed
     */
    public function getConfig(array $conditions = [])
    {
        $query = $this->configSeoRepository->getModel()->select('*');
        $query->where('public', 1);
        if (!empty($conditions['table_name'])) {
            $query->where('table_name', $conditions['table_name']);
        }
        if (!empty($conditions['table_name_in'])) {
            $query->whereIn('table_name', $conditions['table_name_in']);
        }
        if (!empty($conditions['table_id'])) {
            $query->where('table_id', $conditions['table_id']);
        }
        if (!empty($conditions['url'])) {
            $query->where('url', $conditions['url']);
        }
        return $query->first();
    }
}
