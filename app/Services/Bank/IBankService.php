<?php

namespace App\Services\Bank;

use App\Services\IBaseService;

interface IBankService extends IBaseService
{
    public function all($columns = ['*']);

    public function search(array $params);

    public function searchAndPaginate(array $params);

    public function find($id, $columns = ['*']);

    public function create(array $attributes);

    public function update(array $attributes, $id);

    public function delete($id);

    /**
     * Get list city by format option
     *
     * @return array
     */
    public function getOptions(): array;
}
