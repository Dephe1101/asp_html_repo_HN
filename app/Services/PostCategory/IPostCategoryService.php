<?php

namespace App\Services\PostCategory;

use App\Services\IBaseService;

interface IPostCategoryService extends IBaseService
{
    public function all($columns = ['*']);

    public function search(array $params);

    public function searchAndPaginate(array $params);

    public function find($id, $columns = ['*']);

    public function create(array $attributes);

    public function update(array $attributes, $id);

    public function delete($id);

    public function getPostCategoryBySlug(string $slug);

    public function increaseView(int $id);
}
