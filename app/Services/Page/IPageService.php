<?php

namespace App\Services\Page;

use App\Services\IBaseService;

interface IPageService extends IBaseService
{
    public function all($columns = ['*']);

    public function search(array $params);

    public function searchAndPaginate(array $params);

    public function find($id, $columns = ['*']);

    public function create(array $attributes);

    public function update(array $attributes, $id);

    public function delete($id);

    public function getPageByUrl($url);

    public function getPageBySlug($slug);

    /**
     * @param int|string $id
     * @param array $options
     */
    public function getPage($id, array $options = []);

    public function increaseView(int $id);
}
