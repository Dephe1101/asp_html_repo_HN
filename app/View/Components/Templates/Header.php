<?php

namespace App\View\Components\Templates;

use App\Services\Config\IConfigService;
use App\Services\ConfigMenu\IMenuService;
use App\View\Components\BaseComponent;
// use Jenssegers\Agent\Agent;

class Header extends BaseComponent
{
    protected $configService, $menuService;
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
        // $agent = new Agent();
        $isMobile = false;
        $configGroupKey = 'general_config';

        // Megatop menu
        $megatop = $this->configService->getMenuByConfigKey([
            'key' => 'top_right_menu'
        ]);

        // Navigation
        $menu = $this->getNavigation();

        // Logo
        $logo = $this->configService->getConfigByKey($configGroupKey, 'logo' . ($isMobile ? '-mobile' : ''));
        // Site name
        $siteNameConfig = $this->configService->getConfigByKey($configGroupKey, 'cau-hinh-ten-website' . ($isMobile ? '-mobile' : ''));
        $siteName = (isset($siteNameConfig->value) && !empty($siteNameConfig->value)) ? $siteNameConfig->value : null;

        // Other header configs
        $configs = [];
        $headerConfigs = $this->getConfigsWithKeyword('header-');

        $configs = $this->formatConfigsData($headerConfigs);

        $configs = array_merge([
            'megatop' => $megatop ?? [],
            'menu' => $menu ?? [],
            'logo' => $logo,
            'siteName' => $siteName
        ], $configs);

        // Render template
        return view($this->platform() . 'templates.header', $configs);
    }

    public function getNavigation()
    {
        // $agent = new Agent();
        // $navigation = null;

        // if ($agent->isMobile()) {
        //     $navigation = $this->menuService->getMainMenuMobile();
        //     if (isset($navigation) && !empty($navigation)) {
        //         return $navigation;
        //     }
        // }

        $navigation = $this->menuService->getMainMenu();
        return $navigation;
    }
}
