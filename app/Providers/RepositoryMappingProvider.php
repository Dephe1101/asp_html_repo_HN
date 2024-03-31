<?php

namespace App\Providers;

use App\Repositories\Bank\BankRepository;
use App\Repositories\Bank\IBankRepository;
use App\Repositories\CodeSeo\CodeSeoRepository;
use App\Repositories\CodeSeo\ICodeSeoRepository;
use App\Repositories\Comment\CommentRepository;
use App\Repositories\Comment\ICommentRepository;
use App\Repositories\ConfigGroup\ConfigGroupRepository;
use App\Repositories\ConfigGroup\IConfigGroupRepository;
use App\Repositories\ConfigMenuItem\IMenuItemRepository;
use App\Repositories\ConfigMenuItem\MenuItemRepository;
use App\Repositories\ConfigMenu\IMenuRepository;
use App\Repositories\ConfigMenu\MenuRepository;
use App\Repositories\ConfigRedirect\ConfigRedirectRepository;
use App\Repositories\ConfigRedirect\IConfigRedirectRepository;
use App\Repositories\ConfigSeo\ConfigSeoRepository;
use App\Repositories\ConfigSeo\IConfigSeoRepository;
use App\Repositories\Config\ConfigRepository;
use App\Repositories\Config\IConfigRepository;
use App\Repositories\Customer\CustomerContractRepository;
use App\Repositories\Customer\CustomerRepository;
use App\Repositories\Customer\ICustomerContractRepository;
use App\Repositories\Customer\ICustomerRepository;
use App\Repositories\Customer\ICustomerReviewRepository;
use App\Repositories\Customer\CustomerReviewRepository;
use App\Repositories\LoanTerm\ILoanTermRepository;
use App\Repositories\LoanTerm\LoanTermRepository;
use App\Repositories\Page\IPageRepository;
use App\Repositories\Page\PageRepository;
use App\Repositories\Permission\IPermissionRepository;
use App\Repositories\Permission\PermissionRepository;
use App\Repositories\PostCategory\IPostCategoryRepository;
use App\Repositories\PostCategory\PostCategoryRepository;
use App\Repositories\Post\IPostRepository;
use App\Repositories\Post\PostRepository;
use App\Repositories\Role\IRoleRepository;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Tag\ITagRepository;
use App\Repositories\Tag\TagRepository;
use App\Repositories\User\IUserRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryMappingProvider extends ServiceProvider
{
    /**
     * Boot the repository for the application.
     *
     * @return void
     */
    public function boot()
    {
        // User
        $this->app->singleton(IUserRepository::class, UserRepository::class);

        $this->app->singleton(IPermissionRepository::class, PermissionRepository::class);

        $this->app->singleton(IRoleRepository::class, RoleRepository::class);

        $this->app->singleton(ITagRepository::class, TagRepository::class);

        $this->app->singleton(IConfigRepository::class, ConfigRepository::class);

        $this->app->singleton(IConfigGroupRepository::class, ConfigGroupRepository::class);

        $this->app->singleton(ITagRepository::class, TagRepository::class);

        $this->app->singleton(IPostCategoryRepository::class, PostCategoryRepository::class);

        $this->app->singleton(IPostRepository::class, PostRepository::class);

        $this->app->singleton(IConfigRedirectRepository::class, ConfigRedirectRepository::class);

        $this->app->singleton(ICommentRepository::class, CommentRepository::class);

        $this->app->singleton(IPageRepository::class, PageRepository::class);

        $this->app->singleton(ICodeSeoRepository::class, CodeSeoRepository::class);

        $this->app->singleton(IConfigSeoRepository::class, ConfigSeoRepository::class);

        $this->app->singleton(IMenuRepository::class, MenuRepository::class);

        $this->app->singleton(IMenuItemRepository::class, MenuItemRepository::class);

        $this->app->singleton(IBankRepository::class, BankRepository::class);

        $this->app->singleton(ILoanTermRepository::class, LoanTermRepository::class);

        $this->app->singleton(ICustomerRepository::class, CustomerRepository::class);

        $this->app->singleton(ICustomerContractRepository::class, CustomerContractRepository::class);

        $this->app->singleton(ICustomerReviewRepository::class, CustomerReviewRepository::class);
    }
}
