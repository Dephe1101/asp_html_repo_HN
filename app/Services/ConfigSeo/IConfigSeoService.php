<?php

namespace App\Services\ConfigSeo;

use App\Services\IBaseService;

interface IConfigSeoService extends IBaseService
{
    public function all($columns = ['*']);

    public function search(array $params);

    public function searchAndPaginate(array $params);

    public function find($id, $columns = ['*']);

    public function create(array $attributes);

    public function update(array $attributes, $id);

    public function delete($id);

    public function getConfigSeoURLBySlug (string $slug, int $isKeySearch = 1, $entity = '');

    public function getConfigSeoByEntity (string $tableName, int $tableId, int $isKeySearch = 0);

    public function createConfig(array $attributes);

    public function updateConfig(array $attributes, $id);

    /**
     * @param string $url
     * @param string $tableName
     * @param Integer $d
     * @return mixed
     */
    public function isDuplicateConfigSeo($url, $tableName, $id = null);
    /**
     * get list config seo
     *
     * @param array $conditions
     * @return mixed
     */
    public function getConfig(array $conditions = []);
}
