<?php

namespace App\View\Components;

use App\Helpers\Common;
use App\Services\Config\IConfigService;
use Illuminate\View\Component;

class BaseComponent extends Component
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
    }

    /**
     * @return String - name of device mode (pc | sp)
     */
    public function platform()
    {
        return Common::platform() . '.';
    }

    /**
     * Get all configs with the keyword
     *
     * @param String - keyword need search
     *
     * @return Array - array of configs
     */
    public function getConfigsWithKeyword($keyword)
    {
        $result = [];

        if (isset($keyword) && !empty($keyword)) {
            $result = $this->configService->search([
                'keyword' => $keyword,
            ]);
        }

        return $result;
    }

    /**
     * Format the config in the config group according to the required structure
     *
     * @param Array - Configs array
     *
     * @return Array
     */
    public function formatConfigsData($configs = [])
    {
        $result = [];

        foreach ($configs as $item) {
            $label = str_replace('-', ' ', $item->key);
            $label = ucwords($label);
            $label = str_replace(' ', '', $label);

            if ($item->class == 'config_menus') {
                $item->menu_list = $this->configService->getMenuByConfigKey([
                    'key' => $item->key,
                    'public' => 1
                ]);
            }

            $result[$label] = $item;
        }
        return $result;
    }
}
