<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements IUserRepository
{

    public function model()
    {
        return User::class;
    }

    public function boot() {
        $this->skipCriteria()->skipPublic($this->model());
    }
}
