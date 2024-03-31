<?php

namespace App\Repositories\Role;

use App\Models\Role;
use App\Repositories\BaseRepository;

class RoleRepository extends BaseRepository implements IRoleRepository
{
    public function model()
    {
        return Role::class;
    }

    public function boot() {
        $this->skipCriteria()->skipPublic($this->model());
    }
}
