<?php

namespace App\Repositories\LoanTerm;

use App\Models\LoanTerm;
use App\Repositories\BaseRepository;

class LoanTermRepository extends BaseRepository implements ILoanTermRepository
{
    public function model()
    {
        return LoanTerm::class;
    }

    public function boot() {
        $this->skipCriteria();
    }
}
