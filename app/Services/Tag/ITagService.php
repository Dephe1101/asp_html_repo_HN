<?php

namespace App\Services\Tag;

use App\Services\IBaseService;

interface ITagService extends IBaseService
{
    public function all($columns = ['*']);

    public function search(array $params);

    public function searchAndPaginate(array $params);

    public function find($id, $columns = ['*']);

    public function create(array $attributes);

    public function update(array $attributes, $id);

    public function delete($id);

    public function getTagBySlug(array $params = []);

    /**
     * get detail
     *
     * @param int $id
     * @return mixed
     */
    public function getTag(int $id);

    public function increaseView(int $id);
}
