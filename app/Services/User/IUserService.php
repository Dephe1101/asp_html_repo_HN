<?php

namespace App\Services\User;

use App\Services\IBaseService;

interface IUserService extends IBaseService
{
    /**
     * @param array $params
     * @return mixed
     */
    public function fetchList(array $params);

    public function fetch($id, $columns = ['*']);

    public function create(array $attributes);

    public function update(array $attributes, $id);

    public function delete($id);

}
