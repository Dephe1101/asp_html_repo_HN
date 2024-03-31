<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Customer\ICustomerService;
// use App\Services\Bank\IBankService;
use App\Services\Config\IConfigService;
use App\Services\ConfigMenu\IMenuService;

class HomeController extends Controller
{
    protected $customerService;
    // protected $bankService;
    protected $configService;
    protected $menuService;

    public function __construct(ICustomerService $customerService, IConfigService $configService, IMenuService $menuService)
    {
        $this->customerService = $customerService;
        // $this->bankService = $bankService;
        $this->configService = $configService;
        $this->menuService = $menuService;
    }

    public function index(Request $request)
    {
        // TODO test service and repository
        $customers = $this->customerService->searchAndPaginate($request->all());
        // $banks = $this->bankService->getOptions();
        $configGroupKey = 'general_config';
        $productConfigs = $this->configService->getConfigByKey($configGroupKey, 'cau-hinh-san-pham-ban-muon-vay');
        $products = [];
        if(!empty($productConfigs)) {
            $ids = $productConfigs->value ?? [];
            $products = $this->menuService->getMenuItemByIds($ids);
        }
        return $this->view('pages.home.index', ['products' => $products, 'customers' => $customers]);
    }
}
