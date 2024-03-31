<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryCriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

interface IBaseRepository extends RepositoryInterface, RepositoryCriteriaInterface
{
    /**
     * Find data by multiple fields
     *
     * @param array $where
     * @param array $columns
     *
     * @return mixed
     */
    public function firstWhere(array $where, $columns = ['*']);
}
