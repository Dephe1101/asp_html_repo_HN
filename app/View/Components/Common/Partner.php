<?php

namespace App\View\Components\Common;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use App\View\Components\BaseComponent;
use App\Services\Config\IConfigService;

class Partner extends BaseComponent
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
        $viewName = 'components.common.partner';
        if (!View::exists($viewName)) {
            return '';
        }
        $configGroupKey = 'partner-config';
        $partners = $this->configService->getWithGroup($configGroupKey)->chunk(5);
        return view('components.common.partner', ['partners' => $partners ?? []]);
    }
}
