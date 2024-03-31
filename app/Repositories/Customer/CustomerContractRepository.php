<?php

namespace App\Repositories\Customer;

use App\Models\CustomerContract;
use App\Repositories\BaseRepository;

class CustomerContractRepository extends BaseRepository implements ICustomerContractRepository
{

    public function model()
    {
        return CustomerContract::class;
    }

    public function boot() {
        $this->skipCriteria()->skipPublic($this->model());
    }
}
