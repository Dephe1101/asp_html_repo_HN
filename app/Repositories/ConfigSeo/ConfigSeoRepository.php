<?php

namespace App\Repositories\ConfigSeo;

use App\Models\ConfigSeo;
use App\Repositories\BaseRepository;

class ConfigSeoRepository extends BaseRepository implements IConfigSeoRepository
{
    public function model()
    {
        return ConfigSeo::class;
    }

    public function boot() {
        $this->skipCriteria();
    }
}
