<?php

namespace App\View\Components\Includes;

// use App\Helpers\Common;
use App\Services\Config\IConfigService;
use App\View\Components\BaseComponent;
// use Jenssegers\Agent\Agent;

class Head extends BaseComponent
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
        $prefix = '';
        // $agent = new Agent();
        // $prefix = env('PREFIX_DESKTOP', 'pc');
        $favicon = $this->getFavicon();

        // if ($agent->isMobile()) {
        //     $prefix = env('PREFIX_MOBILE', 'sp');
        // }

        return view($this->platform() . 'includes.head', [
            'prefix' => $prefix,
            'favicon' => $favicon,
        ]);
    }

    protected function getFavicon()
    {
        $favicon = $this->configService->getConfigByKey('general_config', 'general_config_favicon');
        return $favicon;
    }
}
