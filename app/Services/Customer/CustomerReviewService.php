<?php

namespace App\Services\Customer;

use App\Repositories\Customer\ICustomerReviewRepository;
use App\Services\BaseService;

class CustomerReviewService extends BaseService implements ICustomerReviewService
{
    protected $customerReviewRepository;

    public function __construct(ICustomerReviewRepository $customerReviewRepository)
    {
        $this->customerReviewRepository = $customerReviewRepository;
    }

    /**
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|mixed
     */
    public function searchAndPaginate(array $params)
    {
        $query = $this->customerReviewRepository->getModel()->query();
        $keyword = data_get($params, 'keyword');

        if ($keyword) {
            $query->where($query->qualifyColumn('name'), 'LIKE', "%{$keyword}%");
        }

        $query->orderBy('created_at', 'DESC');
        $limit = data_get($params, 'limit');
        $result = $query->paginate($limit);
        return $result;
    }

    public function create(array $attributes)
    {
        return $this->customerReviewRepository->create($attributes);
    }

    /**
     * @param array $attributes
     * @param $id
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        return $this->customerReviewRepository->update($attributes, $id);
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        return $this->customerReviewRepository->delete($id);
    }

        /**
     * @param $id
     * @param string[] $columns
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        return $this->customerReviewRepository->find($id, $columns);
    }
}
