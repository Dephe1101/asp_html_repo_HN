<?php

namespace App\View\Components\Templates;

use App\Services\Config\IConfigService;
use App\View\Components\BaseComponent;

class Footer extends BaseComponent
{
    protected $configService;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(IConfigService $configService)
    {
        $this->configService = $configService;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $configs = $this->configService->getWithGroup('footer');

        if ($configs->isEmpty()) {
            $configs = $this->getFooterConfigs();
        } else {
            foreach ($configs as $item) {
                if (
                    !empty($item->value) &&
                    in_array($item->type, ['select2', 'select'])
                ) {
                    $list = $this->configService->getMenuByConfigKey([
                        'key' => $item->key
                    ]);
                    $item->list_menu = $list;
                }
            }

            $configs = ['footerData' => $configs];
        }
        $footer = view($this->platform() . 'templates.footer', $configs)->render();

        return "{$footer}";
    }

    protected function getFooterConfigs()
    {
        $configs = [];

        $footerInfo = $this->getFooterInfo();
        $footerDownload = $this->getFooterDownloadApp();
        $copyright = $this->getCopyright();
        $policyMenu = $this->getPolicyMenu();
        $customerGuideMenu = $this->getCustomerGuideMenu();
        $customerSupportMenu = $this->getCustomerSupportMenu();

        $footerCols = $this->getConfigsWithKeyword('footer-');

        $configs = $this->formatConfigsData($footerCols);

        $configs = array_merge([
            'footerInfo' => $footerInfo,
            'copyright' => $copyright,
            'footerDownload' => $footerDownload,
            'policyMenu' => $policyMenu ?? [],
            'customerGuideMenu' => $customerGuideMenu ?? [],
            'customerSupportMenu' => $customerSupportMenu ?? [],
            'footerCopyright' => $this->getFooterCopyright(),
        ], $configs);

        return $configs;
    }

    protected function getFooterInfo()
    {
        $result = $this->configService->getConfigByKey('general_config', 'general_config_footer_col_1');

        return $result->value ?? null;
    }

    protected function getFooterDownloadApp()
    {
        $result = $this->configService->getConfigByKey('general_config', 'footer-col-3-thong-tin-tai-app');

        return $result->value ?? null;
    }

    protected function getCopyright()
    {
        $result = $this->configService->getConfigByKey('general_config', 'general_config_footer_col_4');

        return $result->value ?? null;
    }

    protected function getCustomerSupportMenu()
    {
        $key = 'general_config_footer_col_2';
        $config = $this->configService->getConfigByKey('general_config', 'general_config_footer_col_2');

        if (empty($config)) {
            return [];
        }
        $result = $this->configService->getMenuByConfigKey([
            'key' =>  $key,
        ]);
        return $result;
    }

    protected function getCustomerGuideMenu()
    {
        $key = 'footer-col-1-huong-dan-khach-hang';
        $result = $this->configService->getConfigByKey(
            'general_config',
            'footer-col-1-huong-dan-khach-hang'
        );

        if (empty($config)) {
            return [];
        }

        $result = $this->configService->getMenuByConfigKey([
            'key' =>  $key,
        ]);

        return $result;
    }

    protected function getPolicyMenu()
    {
        $key = 'footer-col-3-chinh-sach-va-quy-dinh';
        $result = $this->configService->getConfigByKey(
            'general_config',
            'footer-col-3-chinh-sach-va-quy-dinh'
        );

        if (empty($config)) {
            return [];
        }

        $result = $this->configService->getMenuByConfigKey([
            'key' =>  $key,
        ]);

        return $result;
    }

    protected function getFooterCopyright()
    {
        $result = $this->configService->getConfigByKey(
            'general_config',
            'footer-copyright'
        );

        return $result->value ?? '';
    }
}
