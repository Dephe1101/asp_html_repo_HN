<?php

namespace App\View\Components\Templates;

use App\Services\Config\IConfigService;
use App\View\Components\BaseComponent;

class Slider extends BaseComponent
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
        $configGroupKey = 'slider-config';
        $sliders = $this->configService->getWithGroup($configGroupKey);
        // Render template
        return view('templates.slider', ['sliders' => $sliders]);
    }
}
