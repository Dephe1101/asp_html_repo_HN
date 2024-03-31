<?php

namespace App\View\Components\Layouts;

use App\Services\Config\IConfigService;
use App\View\Components\BaseComponent;
use Illuminate\Support\Facades\View;
use App\Models\CodeSeo;
use App\Services\CodeSeo\ICodeSeoService;

class BlankLayout extends BaseComponent
{
    protected $configService;
    protected $codeSeoService;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(IConfigService $configService, ICodeSeoService $codeSeoService)
    {
        $this->configService = $configService;
        $this->codeSeoService = $codeSeoService;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $hotline = $this->getHotline();

        $codeSeosHead = $this->codeSeoService->getCodeSeoByType(CodeSeo::TYPE_HEADER);
        $codeSeosBody = $this->codeSeoService->getCodeSeoByType(CodeSeo::TYPE_BODY);
        $codeSeosFooter = $this->codeSeoService->getCodeSeoByType(CodeSeo::TYPE_FOOTER);
        View::share([
            'hotline' => $hotline,
            'codeSeosHead' => $codeSeosHead,
            'codeSeosBody' => $codeSeosBody,
            'codeSeosFooter' => $codeSeosFooter,
        ]);

        return view('layouts.blank');
    }

    private function getHotline()
    {
        $hotline = $this->configService->getConfigByKey(
            'general_config',
            'general_config_hotline'
        );

        if (empty($hotline)) {
            return [];
        }

        $hotline = $hotline->value;
        $result = [];
        $data = explode(PHP_EOL, $hotline);
        foreach ($data as $key => $item) {
            $tel = [
                'value' => preg_replace("/[^0-9\+]/", "", $item),
                'text' => $item,
            ];
            array_push($result, $tel);
        }
        return $result;
    }
}
