<?php

namespace App\Http\Controllers;

use App\Helpers\Common;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Services\ConfigRedirect\IConfigRedirectService;
use App\Helpers\SeoHelper;
use App\Helpers\StringHelper;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Render view with injected data
     *
     * @param string $viewName
     * @param array $data
     * @return Illuminate\View\View
     */
    protected function view($viewName, array $data = [])
    {
        return Common::view($viewName, $data);
    }

    protected $configSeo = null;
    /**
     * getConfigSeoBySlug
     *
     * @param string $slug
     * @param string $isKeySearch 0: get seo url; 1 get seo sim
     */
    protected function getConfigSeo($slug = '', $isKeySearch = 0, $entity = '')
    {
        $this->configSeo = SeoHelper::getConfigSeoBySlug($slug, $isKeySearch, $entity);

        if (!empty($this->configSeo->content_after)) {
            $this->configSeo->content_after = $this->view(
                'includes.config-seo-after',
                [
                    'content' => $this->configSeo->content_after,
                ]
            )->render();
        }

        if (!empty($this->configSeo->content)) {
            $this->configSeo->content = $this->view(
                'includes.config-seo-after',
                [
                    'content' => $this->configSeo->content,
                ]
            )->render();
        }

        return $this->configSeo;
    }

    protected function handelRedirect()
    {
        /** @var IConfigRedirectService $configRedirectService */
        $configRedirectService = app(IConfigRedirectService::class);
        // check slug into config redirect to redirect
        $configRedirect = $configRedirectService->getConfigRedirectBySlug(request()->path());
        if (!empty($configRedirect)) {
            if ($configRedirect->url_to) {
                if (StringHelper::isFullUrl($configRedirect->url_to)) {
                    return redirect()->away($configRedirect->url_to, (int)$configRedirect->type)->send();
                }
                return redirect($configRedirect->url_to, (int)$configRedirect->type)->send();
            }
        }
    }
}
