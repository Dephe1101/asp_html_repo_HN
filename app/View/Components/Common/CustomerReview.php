<?php

namespace App\View\Components\Common;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use App\View\Components\BaseComponent;
use App\Services\Customer\ICustomerReviewService;

class CustomerReview extends BaseComponent
{
    protected $customerReviewService;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(ICustomerReviewService $customerReviewService)
    {
        $this->customerReviewService = $customerReviewService;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $viewName = 'components.common.customer-review';
        if (!View::exists($viewName)) {
            return '';
        }
        $customerReviews = $this->customerReviewService->searchAndPaginate(['limit', 3]);
        return view('components.common.customer-review', ['customerReviews' => $customerReviews ?? []]);
    }
}
