<?php

namespace App\Services\ConfigGroup;

use App\Repositories\Config\IConfigRepository;
use App\Repositories\ConfigGroup\IConfigGroupRepository;
use App\Helpers\SortHelper;
use App\Services\BaseService;

class ConfigGroupService extends BaseService implements IConfigGroupService
{
    protected $configGroupRepository;

    public function __construct(IConfigRepository $configRepository, IConfigGroupRepository $configGroupRepository)
    {
        $this->configGroupRepository = $configGroupRepository;
    }

    /**
     * @param array $params
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function search(array $params)
    {
        $query = $this->configGroupRepository->getModel()->query();

        if ($keyword = data_get($params, 'keyword')) {
            $query->where($query->qualifyColumn('key'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('name'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('note'), 'LIKE', "%{$keyword}%");
        }

        $query->where('key', '!=', $this->configGroupRepository->getCommentKey());
        $query->orderBy('order', 'ASC');
        $query->orderBy('created_at', 'DESC');

        return $query->get();
    }

    /**
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchAndPaginate(array $params): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->configGroupRepository->getModel()->query()->with(['configs']);

        if ($keyword = data_get($params, 'keyword')) {
            $query->where($query->qualifyColumn('key'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('name'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('note'), 'LIKE', "%{$keyword}%");
        }

        $query->where('key', '!=', $this->configGroupRepository->getCommentKey());

        // SortHelper::sortUpdated($query, $params);

        $query->orderBy('order', 'ASC');
        $query->orderBy('created_at', 'DESC');
        $limit = data_get($params, 'limit', 10);
        return $query->paginate($limit);
    }

    public function create(array $attributes)
    {
        return $this->configGroupRepository->create($attributes);
    }

    public function update(array $attributes, $id)
    {
        return $this->configGroupRepository->update($attributes, $id);
    }

    public function delete($id)
    {
        return $this->configGroupRepository->delete($id);
    }

    public function find($id, $columns = ['*'])
    {
        return $this->configGroupRepository->find($id, $columns);
    }
}
