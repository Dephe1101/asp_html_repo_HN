<?php

namespace App\Repositories\CodeSeo;

use App\Models\CodeSeo;
use App\Repositories\BaseRepository;

class CodeSeoRepository extends BaseRepository implements ICodeSeoRepository
{
    public function model()
    {
        return CodeSeo::class;
    }

    public function boot() {
        $this->skipCriteria();
    }
}
