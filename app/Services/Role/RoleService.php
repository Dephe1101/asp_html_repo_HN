<?php

namespace App\Services\Role;

use App\Repositories\Permission\IPermissionRepository;
use App\Repositories\Role\IRoleRepository;
use App\Services\BaseService;

class RoleService extends BaseService implements IRoleService
{
    protected $roleRepository;
    protected $permissionRepository;

    public function __construct(IRoleRepository $roleRepository, IPermissionRepository $permissionRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function find($id, $columns = ['*'])
    {
        return $this->roleRepository->find($id, $columns);
    }

    public function all($columns = array('*'))
    {
        return $this->roleRepository->all($columns);
    }

    public function allPermission($columns = ['*'])
    {
        return $this->permissionRepository->all($columns);
    }

    public function search(array $params)
    {
        $query = $this->roleRepository->getModel()->query();

        if ( $keyword = data_get($params, 'keyword') ) {
            $query->where($query->qualifyColumn('name'), 'LIKE', "%{$keyword}%");
        }

        $query->orderBy('order', 'ASC');
        $query->orderBy('created_at', 'DESC');

        return $query->get();
    }

    public function searchAndPaginate(array $params)
    {
        $query = $this->roleRepository->getModel()->query();

        if ( $keyword = data_get($params, 'keyword') ) {
            $query->where($query->qualifyColumn('name'), 'LIKE', "%{$keyword}%");
        }

        // SortHelper::sortUpdated($query, $params);

        $limit = data_get($params, 'limit');

        return $query->paginate($limit);
    }

    public function create(array $attributes)
    {
        return $this->roleRepository->create($attributes);
    }

    public function update(array $attributes, $id)
    {
        return $this->roleRepository->update($attributes, $id);
    }

    public function delete($id)
    {
        return $this->roleRepository->delete($id);
    }
}
