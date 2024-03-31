<?php

namespace App\Repositories\Permission;

use App\Models\Permission;
use App\Repositories\BaseRepository;

class PermissionRepository extends BaseRepository implements IPermissionRepository
{
    public function model()
    {
        return Permission::class;
    }

    public function boot() {
        $this->skipCriteria()->skipPublic($this->model());
    }
}
