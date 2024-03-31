<?php

namespace App\Providers;

use App\Services\Bank\BankService;
use App\Services\Bank\IBankService;
use App\Services\CodeSeo\CodeSeoService;
use App\Services\CodeSeo\ICodeSeoService;
use App\Services\Comment\CommentService;
use App\Services\Comment\ICommentService;
use App\Services\ConfigGroup\ConfigGroupService;
use App\Services\ConfigGroup\IConfigGroupService;
use App\Services\ConfigMenu\IMenuService;
use App\Services\ConfigMenu\MenuService;
use App\Services\ConfigRedirect\ConfigRedirectService;
use App\Services\ConfigRedirect\IConfigRedirectService;
use App\Services\ConfigSeo\ConfigSeoService;
use App\Services\ConfigSeo\IConfigSeoService;
use App\Services\Config\ConfigService;
use App\Services\Config\IConfigService;
use App\Services\Customer\CustomerContractService;
use App\Services\Customer\CustomerService;
use App\Services\Customer\ICustomerContractService;
use App\Services\Customer\ICustomerReviewService;
use App\Services\Customer\CustomerReviewService;
use App\Services\Customer\ICustomerService;
use App\Services\KeySearch\IKeySearchService;
use App\Services\KeySearch\KeySearchService;
use App\Services\LoanTerm\ILoanTermService;
use App\Services\LoanTerm\LoanTermService;
use App\Services\Page\IPageService;
use App\Services\Page\PageService;
use App\Services\Permission\IPermissionService;
use App\Services\Permission\PermissionService;
use App\Services\PostCategory\IPostCategoryService;
use App\Services\PostCategory\PostCategoryService;
use App\Services\Post\IPostService;
use App\Services\Post\PostService;
use App\Services\Role\IRoleService;
use App\Services\Role\RoleService;
use App\Services\Tag\ITagService;
use App\Services\Tag\TagService;
use App\Services\User\IUserService;
use App\Services\User\UserService;
use Illuminate\Support\ServiceProvider;

class ServiceMappingProvider extends ServiceProvider
{
    /**
     * Boot the repository for the application.
     *
     * @return void
     */
    public function boot()
    {
        // User
        $this->app->singleton(IUserService::class, UserService::class);

        $this->app->singleton(IPermissionService::class, PermissionService::class);

        $this->app->singleton(IRoleService::class, RoleService::class);

        $this->app->singleton(ITagService::class, TagService::class);

        $this->app->singleton(IPostCategoryService::class, PostCategoryService::class);

        $this->app->singleton(IPostService::class, PostService::class);

        $this->app->singleton(IConfigRedirectService::class, ConfigRedirectService::class);

        $this->app->singleton(IConfigService::class, ConfigService::class);

        $this->app->singleton(ICommentService::class, CommentService::class);

        $this->app->singleton(IPageService::class, PageService::class);

        $this->app->singleton(IConfigGroupService::class, ConfigGroupService::class);

        $this->app->singleton(IAuthService::class, AuthService::class);

        $this->app->singleton(IOrderService::class, OrderService::class);

        $this->app->singleton(ICodeSeoService::class, CodeSeoService::class);

        $this->app->singleton(IConfigSeoService::class, ConfigSeoService::class);

        $this->app->singleton(IMenuService::class, MenuService::class);

        $this->app->singleton(IKeySearchService::class, KeySearchService::class);

        $this->app->singleton(ICommonService::class, CommonService::class);

        $this->app->singleton(ILoanTermService::class, LoanTermService::class);

        $this->app->singleton(IBankService::class, BankService::class);

        $this->app->singleton(ICustomerService::class, CustomerService::class);

        $this->app->singleton(ICustomerContractService::class, CustomerContractService::class);

        $this->app->singleton(ICustomerReviewService::class, CustomerReviewService::class);
    }
}
