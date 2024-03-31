<?php

namespace App\Services\ConfigMenu;

use Illuminate\Support\Facades\Cache;
use App\Services\BaseService;
use App\Repositories\ConfigMenu\IMenuRepository;
use App\Repositories\ConfigMenuItem\IMenuItemRepository;

class MenuService extends BaseService implements IMenuService {
    protected $menuRepository;
    protected $menuItemRepository;
    protected $menuCacheKey = 'menu-cache-key';
    protected $menuItemCacheKey = 'menu-item-cache-key';

    public function __construct(
        IMenuRepository $menuRepository,
        IMenuItemRepository $menuItemRepository
    ) {
        $this->menuRepository = $menuRepository;
        $this->menuItemRepository = $menuItemRepository;
    }

    public function getAllMenu()
    {
        if (Cache::store('file')->has($this->menuCacheKey)) {
            return Cache::store('file')->get($this->menuCacheKey);
        }

        $query = $this->menuRepository->getModel()->query();
        $query->where($query->qualifyColumn('public'), 1);
        $query->orderBy($query->qualifyColumn('order'), 'asc');
        $allMenu = $query->get();

        Cache::store('file')->rememberForever(
            $this->menuCacheKey,
            function () use ($allMenu) {
                return $allMenu;
            }
        );

        return $allMenu;
    }

    public function getAllMenuItem()
    {
        if (Cache::store('file')->has($this->menuItemCacheKey)) {
            return Cache::store('file')->get($this->menuItemCacheKey);
        }

        $query = $this->menuItemRepository->getModel()->query();
        $query->where($query->qualifyColumn('public'), 1);
        $query->orderBy($query->qualifyColumn('order'), 'asc');
        $allMenuItem = $query->get();

        Cache::store('file')->rememberForever(
            $this->menuItemCacheKey,
            function () use ($allMenuItem) {
                return $allMenuItem;
            }
        );

        return $allMenuItem;
    }

    public function getMainMenu()
    {
        $mainMenu = (object)[];
        $menus = $this->getAllMenu();
        foreach ($menus as $item) {
            if ($item->is_main == 1) {
                $mainMenu = $item;
                break;
            }
        }

        $mainMenuId = $mainMenu->id ?? '';

        $menuItem = $this->getAllMenuItem();
        foreach ($menuItem as $k => $item) {
            if ($item->menu_id != $mainMenuId) {
                $menuItem->forget($k);
            }
        }

        if (isset($menuItem) && !empty($menuItem)) {
            $menuItem = $this->formatMenuMultiLevel($menuItem);
        }

        return $menuItem;
    }

    /**
     * Get main menu for mobile
     */
    public function getMainMenuMobile()
    {
        $menus = $this->getAllMenu();
        $mobileMenu = (object)[];
        foreach ($menus as $item) {
            if ($item->is_main == 1) {
                $mobileMenu = $item;
                break;
            }
        }

        $mobileMenuId = $mobileMenu->id ?? '';

        $menuItem = $this->getAllMenuItem();
        foreach ($menuItem as $k => $item) {
            if ($item->menu_id != $mobileMenuId) {
                $menuItem->forget($k);
            }
        }

        if (isset($menuItem) && !empty($menuItem)) {
            $menuItem = $this->formatMenuMultiLevel($menuItem);
        }

        return $menuItem;
    }

    public function getMenuItemBySlugSimCategory($slug)
    {
        try {
            $menuItem = $this->getAllMenuItem();
            foreach ($menuItem as $k => $item) {
                if ($item->link == $slug && !empty($item->data)) {
                    return $item;
                }
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getMenuItemBySlug($slug)
    {
        try {
            $menuItem = $this->getAllMenuItem();
            foreach ($menuItem as $k => $item) {
                if ($item->link == $slug) {
                    return $item;
                }
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Format for menu has multiple level
     */
    private function formatMenuMultiLevel($menuItems)
    {
        $parentItem = [];
        $childItem = [];

        /**
         * Split all menu items into parent and child item
         */
        foreach ($menuItems as $item) {
            if (!isset($item->parent_id) || empty($item->parent_id)) {
                array_push($parentItem, $item);
            } else {
                array_push($childItem, $item);
            }
        }

        /**
         * Push the child item into the corresponding parent item
         */
        foreach ($parentItem as $parent) {
            $sub_item = [];

            foreach ($childItem as $child) {
                if ($child->parent_id == $parent->id) {
                    array_push($sub_item, $child);
                }
            }

            $parent->sub_item = $sub_item;
        }

        return $parentItem;
    }

    public function getMenuItemByIds($ids) {
        $data = [];
        try {
            $menuItem = $this->getAllMenuItem();
            foreach ($menuItem as $item) {
                if (in_array($item->id, $ids)) {
                    $data[] = $item;
                }
            }
            return $data;
        } catch (\Exception $e) {
            return $data;
        }
    }
}

