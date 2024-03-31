<?php

namespace App\Repositories\Customer;

use App\Models\CustomerReview;
use App\Repositories\BaseRepository;

class CustomerReviewRepository extends BaseRepository implements ICustomerReviewRepository
{

    public function model()
    {
        return CustomerReview::class;
    }

    public function boot() {
        $this->skipCriteria()->skipPublic($this->model());
    }
}
