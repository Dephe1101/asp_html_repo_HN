<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Services\Page\IPageService;
use App\Models\ConfigSeo;
use App\Services\Config\IConfigService;
use Illuminate\Support\Facades\View;

class PageController extends Controller
{
    protected $pageService;
    protected $configService;

    public function __construct(IPageService $pageService, IConfigService $configService)
    {
        $this->pageService = $pageService;
        $this->configService = $configService;
        $slug = request()->route('slug');
        $this->getConfigSeo($slug, 0, ConfigSeo::PAGE);

        // Share view's data
        $viewFactory = view();
        $viewFactory->share('configSeo', $this->configSeo);
    }

    public function index($slug)
    {
        $page = $this->pageService->getPageBySlug($slug);
        if (empty($page)) {
            return abort(404);
        }
        $this->pageService->increaseView($page->id);
        view()->share('postSeo', $page);
        $view = $this->view('pages.static.index', ['page' => $page])->render();
        return $view;
    }

    public function showContact()
    {
        $page = $this->pageService->getPageBySlug(request()->path());
        if (empty($page)) {
            return abort(404);
        }
        $this->pageService->increaseView($page->id);
        view()->share('postSeo', $page);
        View::share([
            'hotline' => $this->getHotline(),
            'email' => $this->getEmail(),
            'companyName' => $this->getCompanyName(),
            'slogan2' => $this->getSlogan2(),
        ]);
        $view = $this->view('pages.contact.index', ['page' => $page])->render();
        return $view;
    }

    public function showBrowseLoan()
    {
        $page = $this->pageService->getPageBySlug(request()->path());
        if (empty($page)) {
            return abort(404);
        }
        $this->pageService->increaseView($page->id);
        view()->share('postSeo', $page);
        $view = $this->view('pages.loan.index', ['page' => $page])->render();
        return $view;
    }

    public function showNotification()
    {
        return redirect()->route('account_volatility.show');
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
