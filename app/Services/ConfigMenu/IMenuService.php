<?php

namespace App\Services\ConfigMenu;

use App\Services\IBaseService;

interface IMenuService extends IBaseService {
    public function getMainMenu();
    public function getMainMenuMobile();
    public function getMenuItemBySlugSimCategory($slug);
    public function getMenuItemBySlug($slug);
    public function getMenuItemByIds($ids);
}

