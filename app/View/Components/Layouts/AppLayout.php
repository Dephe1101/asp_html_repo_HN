<?php

namespace App\View\Components\Layouts;

use App\Services\Config\IConfigService;
use App\View\Components\BaseComponent;
use Illuminate\Support\Facades\View;
use App\Models\CodeSeo;
use App\Services\CodeSeo\ICodeSeoService;

class AppLayout extends BaseComponent
{
    protected $configService;
    public $layoutClass, $isSearch, $showComment, $codeSeoService;


    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(IConfigService $configService, ICodeSeoService $codeSeoService, $layoutClass = '', $isSearch = true, $showComment = true,)
    {
        $this->configService = $configService;
        $this->codeSeoService = $codeSeoService;
        $this->layoutClass = $layoutClass;
        $this->isSearch = $isSearch;
        $this->showComment = $showComment;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $hotline = $this->getHotline();
        $email = $this->getEmail();
        $codeSeo = $this->codeSeoService->getCodeSeo([
            'public' => 1,
            'type_in' => [CodeSeo::TYPE_HEADER, CodeSeo::TYPE_BODY, CodeSeo::TYPE_FOOTER]
        ]);
        if (!empty($codeSeo)) {
            $codeSeo =  $codeSeo->mapToGroups(function ($item) {
                return [$item['type'] => $item];
            })->all();
        }
        View::share([
            'hotline' => $hotline,
            'email' => $email,
            'codeSeosHead' => $codeSeo[CodeSeo::TYPE_HEADER] ?? [],
            'codeSeosBody' => $codeSeo[CodeSeo::TYPE_BODY] ?? [],
            'codeSeosFooter' => $codeSeo[CodeSeo::TYPE_FOOTER] ?? [],
        ]);

        return view('layouts.app');
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

    private function getEmail()
    {
        $email = $this->configService->getConfigByKey(
            'general_config',
            'general_config_email'
        );

        if (empty($email)) {
            return '';
        }
        return $email->value;
    }
}
