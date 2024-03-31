<?php

namespace App\Services\ConfigGroup;

use App\Services\IBaseService;

interface IConfigGroupService extends IBaseService
{
    public function search(array $params);

    public function searchAndPaginate(array $params);

    public function find($id, $columns = ['*']);

    public function create(array $attributes);

    public function update(array $attributes, $id);

    public function delete($id);
}
