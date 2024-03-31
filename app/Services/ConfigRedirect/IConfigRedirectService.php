<?php

namespace App\Services\ConfigRedirect;

use App\Services\IBaseService;

interface IConfigRedirectService extends IBaseService
{
    public function all($columns = ['*']);

    public function search(array $params);

    public function searchAndPaginate(array $params);

    public function find($id, $columns = ['*']);

    public function create(array $attributes);

    public function update(array $attributes, $id);

    public function delete($id);
    
    public function getCodeType();

    public function getConfigRedirectBySlug($slug, $is_system_redirect = 0);
}
