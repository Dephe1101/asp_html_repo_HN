<?php

namespace App\Repositories\Bank;

use App\Models\Bank;
use App\Repositories\BaseRepository;

class BankRepository extends BaseRepository implements IBankRepository
{
    public function model()
    {
        return Bank::class;
    }

    public function boot() {
        $this->skipCriteria();
    }

    /**
     * Get list city by format option
     *
     * @param array $options
     * @return array
     */
    public function getOptions(array $options = []): array
    {
        return $this->pluck('name', 'id')->all();
    }
}
