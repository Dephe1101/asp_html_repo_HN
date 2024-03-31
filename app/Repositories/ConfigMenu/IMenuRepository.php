<?php

namespace App\Repositories\ConfigMenu;

use App\Repositories\IBaseRepository;

interface IMenuRepository extends IBaseRepository {
    public function updateAllExcept($id, $updates);
}
