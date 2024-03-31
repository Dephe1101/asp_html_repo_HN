<?php

namespace App\View\Components\Common;

use App\Services\Config\IConfigService;
use App\View\Components\BaseComponent;
use Illuminate\Support\Facades\View;

class ContactHome extends BaseComponent
{
    protected $configService;
    public $layoutClass, $showComment, $codeSeoService;


    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(IConfigService $configService, $layoutClass = '', $showComment = true,)
    {
        $this->configService = $configService;
        $this->layoutClass = $layoutClass;
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
        View::share([
            'hotline' => $hotline,
            'email' => $email,
            'slogan1' => $this->getSlogan1(),
            'slogan2' => $this->getSlogan2(),
            'companyName' => $this->getCompanyName(),
        ]);

        return view('components.common.contact-home');
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

    private function getCompanyName()
    {
        $companyName = $this->configService->getConfigByKey(
            'general_config',
            'ten-cong-ty'
        );

        if (empty($companyName)) {
            return '';
        }
        return $companyName->value;
    }

    private function getSlogan1()
    {
        $slogan1 = $this->configService->getConfigByKey(
            'general_config',
            'slogan-1'
        );

        if (empty($slogan1)) {
            return '';
        }
        return $slogan1->value;
    }

    private function getSlogan2()
    {
        $slogan2 = $this->configService->getConfigByKey(
            'general_config',
            'slogan-2'
        );

        if (empty($slogan2)) {
            return '';
        }
        return $slogan2->value;
    }
}
