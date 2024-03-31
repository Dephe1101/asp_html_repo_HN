<?php

namespace App\Services\CodeSeo;

use App\Services\IBaseService;

interface ICodeSeoService extends IBaseService
{
    public function all($columns = ['*']);

    public function search(array $params);

    public function searchAndPaginate(array $params);

    public function find($id, $columns = ['*']);

    public function create(array $attributes);

    public function update(array $attributes, $id);

    public function delete($id);
    
    public function getType();

    public function getCodeSeoByType(string $type);
}
