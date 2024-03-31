<?php

namespace App\Services\Comment;

use App\Services\IBaseService;

interface ICommentService extends IBaseService
{
    public function all($columns = ['*']);

    public function search(array $params);

    public function searchAndPaginate(array $params);

    public function find($id, $columns = ['*']);

    public function create(array $attributes);

    public function update(array $attributes, $id);

    public function delete($id);

    public function togglePublic($id);

    public function updateOrCreateAnswer(array $params, $id);

    public function countNotPublish();
}
