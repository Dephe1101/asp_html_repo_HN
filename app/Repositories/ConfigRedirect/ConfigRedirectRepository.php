<?php

namespace App\Repositories\ConfigRedirect;

use App\Models\ConfigRedirect;
use App\Repositories\BaseRepository;

class ConfigRedirectRepository extends BaseRepository implements IConfigRedirectRepository
{
    public function model()
    {
        return ConfigRedirect::class;
    }

    public function boot() {
        $this->skipCriteria();
    }
}
