<?php

namespace App\Services\Permission;

use App\Helpers\SortHelper;
use App\Services\BaseService;
use App\Repositories\Permission\IPermissionRepository;

class PermissionService extends BaseService implements IPermissionService
{
    protected $permissionRepository;

    public function __construct(IPermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function find($id, $columns = ['*'])
    {
        return $this->permissionRepository->find($id, $columns);
    }

    public function all($columns = array('*'))
    {
        return $this->permissionRepository->all($columns);
    }

    public function search(array $params)
    {
        $query = $this->permissionRepository->getModel()->query();

        if ( $keyword = data_get($params, 'keyword') ) {
            $query->where($query->qualifyColumn('route_name'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('note'), 'LIKE', "%{$keyword}%");
        }

        $query->orderBy('order', 'ASC');
        $query->orderBy('created_at', 'DESC');

        return $query->all();
    }

    public function searchAndPaginate(array $params)
    {
        $query = $this->permissionRepository->getModel()->query();

        if ( $keyword = data_get($params, 'keyword') ) {
            $query->where($query->qualifyColumn('route_name'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('note'), 'LIKE', "%{$keyword}%");
        }

        // SortHelper::sortUpdated($query, $params);

        $query->orderBy('order', 'ASC');
        $query->orderBy('created_at', 'DESC');

        $limit = data_get($params, 'limit');

        return $query->paginate($limit);
    }

    public function create(array $attributes)
    {
        return $this->permissionRepository->create($attributes);
    }

    public function update(array $attributes, $id)
    {
        return $this->permissionRepository->update($attributes, $id);
    }

    public function delete($id)
    {
        return $this->permissionRepository->delete($id);
    }
}
