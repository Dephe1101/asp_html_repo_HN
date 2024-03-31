<?php

namespace App\Repositories\ConfigMenuItem;

use App\Models\MenuItem;
use App\Repositories\BaseRepository;

class MenuItemRepository extends BaseRepository implements IMenuItemRepository {
    public function model() {
        return MenuItem::class;
    }

    public function boot() {
        $this->skipCriteria();
    }
}
