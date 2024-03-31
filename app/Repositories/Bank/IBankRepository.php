<?php

namespace App\Repositories\Bank;

use App\Repositories\IBaseRepository;

interface IBankRepository extends IBaseRepository
{
    /**
     * Get list banks by format option
     *
     * @param array $options
     * @return array
     */
    public function getOptions(array $options = []): array;
}
