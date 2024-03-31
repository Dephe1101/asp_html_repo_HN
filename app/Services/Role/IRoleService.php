<?php

namespace App\Services\Role;

use App\Services\IBaseService;

interface IRoleService extends IBaseService
{
    public function all($columns = ['*']);

    public function allPermission($columns = ['*']);

    public function search(array $params);

    public function searchAndPaginate(array $params);

    public function find($id, $columns = ['*']);

    public function create(array $attributes);

    public function update(array $attributes, $id);

    public function delete($id);
}
