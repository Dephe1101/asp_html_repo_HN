<?php

namespace App\Repositories\Customer;

use App\Models\Customer;
use App\Repositories\BaseRepository;

class CustomerRepository extends BaseRepository implements ICustomerRepository
{

    public function model()
    {
        return Customer::class;
    }

    public function boot() {
        $this->skipCriteria()->skipPublic($this->model());
    }
}
