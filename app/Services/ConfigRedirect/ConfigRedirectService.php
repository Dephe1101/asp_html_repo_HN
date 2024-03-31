<?php

namespace App\Services\ConfigRedirect;

use App\Models\ConfigRedirect;
use App\Helpers\SortHelper;
use App\Services\BaseService;
use App\Repositories\ConfigRedirect\IConfigRedirectRepository;

class ConfigRedirectService extends BaseService implements IConfigRedirectService
{
    protected $configRedirectRepository;

    public function __construct(IConfigRedirectRepository $configRedirectRepository)
    {
        $this->configRedirectRepository = $configRedirectRepository;
    }

    public function find($id, $columns = ['*'])
    {
        return $this->configRedirectRepository->find($id, $columns);
    }

    public function all($columns = array('*'))
    {
        return $this->configRedirectRepository->all($columns);
    }

    public function search(array $params)
    {
        $query = $this->configRedirectRepository->getModel()->query();

        if ( $keyword = data_get($params, 'keyword') ) {
            $query->where($query->qualifyColumn('title'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('slug'), 'LIKE', "%{$keyword}%");
        }

        $query->orderBy('order', 'ASC');
        $query->orderBy('created_at', 'DESC');

        return $query->all();
    }

    public function searchAndPaginate(array $params)
    {
        $query = $this->configRedirectRepository->getModel()->query();

        if ( $keyword = data_get($params, 'keyword') ) {
            $query->where($query->qualifyColumn('url_from'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('url_to'), 'LIKE', "%{$keyword}%");
        }

        $is_system_redirect = data_get($params, 'is_system_redirect', 0);
        if ( $is_system_redirect == 1 ) {
            $query->whereNotNull($query->qualifyColumn('system_redirect'));
        }

        $query->orderBy('order', 'ASC');
        $query->orderBy('created_at', 'DESC');

        $limit = data_get($params, 'limit');
        $result = $query->paginate($limit);
        return $result;
    }

    public function create(array $attributes)
    {
        $attributes = $this->formatData($attributes);
        return $this->configRedirectRepository->create($attributes);
    }

    public function update(array $attributes, $id)
    {
        $attributes = $this->formatData($attributes);
        return $this->configRedirectRepository->update($attributes, $id);
    }

    public function delete($id)
    {
        return $this->configRedirectRepository->delete($id);
    }

    public function getCodeType()
    {
        return ConfigRedirect::getCodeType();
    }

    private function formatData($params)
    {
        $result = $this->mergeAttributes($params);
        return $result;
    }

    private function mergeAttributes($params)
    {
        return array_merge(array (
            'public' => 0,
            'order' => 1,
        ), $params);
    }

    public function getConfigRedirectBySlug($slug, $is_system_redirect = 0)
    {
        $query = $this->configRedirectRepository->getModel()->query();
        $query = $query->where($query->qualifyColumn('url_from'), $slug);
        if ($is_system_redirect === 1) {
            $query = $query->where($query->qualifyColumn('url_from'), $slug)->where($query->qualifyColumn('public'), 1)->whereNotNull($query->qualifyColumn('system_redirect'))->orderBy('order', 'DESC');
        }
        return $query->first();
    }
}
