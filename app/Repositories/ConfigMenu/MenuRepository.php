<?php

namespace App\Repositories\ConfigMenu;

use App\Models\Menu;
use App\Repositories\BaseRepository;

class MenuRepository extends BaseRepository implements IMenuRepository {
    public function model() {
        return Menu::class;
    }

    public function boot() {
        $this->skipCriteria();
    }

    public function updateAllExcept($id, $updates) {
        Menu::whereNotIn('id', [$id])->update($updates);
    }
}
