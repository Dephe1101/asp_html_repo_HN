<?php

namespace App\View\Components\Common;

use Illuminate\Support\Facades\View;
use App\View\Components\BaseComponent;
use App\Services\Config\IConfigService;
use App\Services\ConfigMenu\IMenuService;

class Product extends BaseComponent
{
    protected $configService;
    protected $menuService;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(IConfigService $configService, IMenuService $menuService)
    {
        $this->configService = $configService;
        $this->menuService = $menuService;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $viewName = 'components.common.product';
        if (!View::exists($viewName)) {
            return '';
        }
        $configGroupKey = 'general_config';
        $productConfigs = $this->configService->getConfigByKey($configGroupKey, 'cau-hinh-san-pham-ban-muon-vay');
        $products = [];
        if(!empty($productConfigs)) {
            $ids = $productConfigs->value ?? [];
            $products = $this->menuService->getMenuItemByIds($ids);
        }
        return view('components.common.product', ['products' => $products ?? [], 'config_product' => $productConfigs]);
    }
}
