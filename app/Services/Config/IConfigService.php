<?php

namespace App\Services\Config;

use App\Services\IBaseService;

interface IConfigService extends IBaseService
{
    public function upsertFilterWord(array $attributes);

    public function findFilterWord($columns = ['*']);

    public function search(array $params);

    public function searchAndPaginate(array $params);

    public function find($id, $columns = ['*']);

    public function create(array $attributes);

    public function update(array $attributes, $id);

    public function delete($id);

    public function getTypeConfigs();

    public function getWithGroup($groupKey, $options = []);

    public function getConfigClass();

    public function getWithGroupAndKey($groupKey, $key);

    public function updateWithKey(array $params, $key);

    public function getMenuByConfigKey(array $params);
    public function getConfigByKey($groupKey, $key);
}
