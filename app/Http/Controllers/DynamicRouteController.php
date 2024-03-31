<?php

namespace App\Http\Controllers;

use App\Http\Controllers\HomePage\HomePageController;
use App\Http\Controllers\Page\PageController;
use App\Http\Controllers\PostCategory\PostCategoryController;
use App\Http\Controllers\Tag\TagController;
use App\Services\ConfigSeo\IConfigSeoService;
use Illuminate\Routing\ControllerDispatcher;
use Illuminate\Routing\Route;


class DynamicRouteController extends Controller
{
    /**
     * @var IConfigSeoService
     */
    protected $configSeoService;

    protected $routes = [
        'post-category' => [
            'controller' => PostCategoryController::class,
            'action' => 'index',
        ],
        'page' => [
            'controller' => PageController::class,
            'action' => 'index',
        ],
        'tag' => [
            'controller' => TagController::class,
            'action' => 'index',
        ],
    ];

    public function __construct(
        IConfigSeoService $configSeoService
    ) {
        $this->configSeoService = $configSeoService;
        $this->handelRedirect();
    }

    public function handle(string $slug)
    {
        $configSeo = $this->configSeoService->getConfig([
            'url' => $slug,
            'table_name_in' => array_keys($this->routes)
        ]);
        if (empty($configSeo)) {
            return abort(404);
        }

        $tableName = $configSeo->table_name;
        if (empty($this->routes[$tableName])) {
            $controller = HomePageController::class;
            $action = 'index';
            return $this->dispatchController($controller, $action);
        }

        $route = $this->routes[$tableName];

        $controller = $route['controller'];
        $action = $route['action'];
        return $this->dispatchController($controller, $action);
    }

    protected function dispatchController($controller, $action) {
        if ($controller) {
            $container = app();
            $route = $container->make(Route::class);
            $controllerInstance = $container->make($controller);
            return (new ControllerDispatcher($container))->dispatch($route, $controllerInstance, $action);
        }
    }
}
