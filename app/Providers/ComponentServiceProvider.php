<?php

namespace App\Providers;

use App\View\Components\Common\Modal;
use App\View\Components\Common\Partner;
use App\View\Components\Common\PostRecent;
use App\View\Components\Common\Product;
use App\View\Components\Common\CustomerReview;
use App\View\Components\Common\ContactHome;
use App\View\Components\Includes\Head;
use App\View\Components\Includes\Meta;
use App\View\Components\Includes\Scripts;
use App\View\Components\Layouts\AppLayout;
use App\View\Components\Layouts\BlankLayout;
use App\View\Components\Templates\Footer;
use App\View\Components\Templates\Header;
use App\View\Components\Templates\Slider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ComponentServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Layouts
         */
        Blade::component('app-layout', AppLayout::class);
        Blade::component('blank-layout', BlankLayout::class);
        /**
         * Includes
         */
        Blade::component('head', Head::class);
        Blade::component('meta', Meta::class);
        Blade::component('scripts', Scripts::class);

        /**
         * Templates
         */
        Blade::component('header', Header::class);
        Blade::component('footer', Footer::class);
        Blade::component('slider', Slider::class);
        /**
         * Common
         */
        Blade::component('modal', Modal::class);
        Blade::component('filter', Filter::class);
        Blade::component('partner', Partner::class);
        Blade::component('product', Product::class);
        Blade::component('post-recent', PostRecent::class);
        Blade::component('customer-review', CustomerReview::class);
        Blade::component('contact-home', ContactHome::class);
    }
}
