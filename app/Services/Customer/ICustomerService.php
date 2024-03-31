<?php

namespace App\Services\Customer;

use App\Services\IBaseService;

interface ICustomerService extends IBaseService
{
    /**
     * @param array $params
     * @return mixed
     */
    public function searchAndPaginate(array $params);

    public function find($id, $columns = ['*']);

    public function create(array $attributes);

    public function update(array $attributes, $id);

    public function delete($id);

}
