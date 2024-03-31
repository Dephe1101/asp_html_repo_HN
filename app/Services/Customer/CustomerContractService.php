<?php

namespace App\Services\Customer;

use Illuminate\Support\Facades\Hash;
use App\Repositories\Customer\ICustomerContractRepository;
use App\Services\BaseService;
use Carbon\Carbon;

class CustomerContractService extends BaseService implements ICustomerContractService
{
    protected $customerContractRepository;

    public function __construct(ICustomerContractRepository $customerContractRepository)
    {
        $this->customerContractRepository = $customerContractRepository;
    }

    /**
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|mixed
     */
    public function searchAndPaginate(array $params)
    {
        $query = $this->customerContractRepository->getModel()->query();
        $keyword = data_get($params, 'keyword');
        $bankId = data_get($params, 'bank_id');
        $loanPurpose = data_get($params, 'loan_purpose');
        $startDate = data_get($params, 'date_start');
        $endDate = data_get($params, 'date_end');
        $approvedStatus = data_get($params, 'approved_status', null);
        $loanTermId = data_get($params, 'loan_term_id');

        if ($bankId) {
            $query->join('customers', 'customer_contracts.customer_id', '=', 'customers.id')->where('customers.bank_id', $bankId);
        }

        $query->where($query->qualifyColumn('approved_status'), $approvedStatus);

        if ($keyword) {
            $query->where($query->qualifyColumn('name'), 'LIKE', "%{$keyword}%");
        }

        if ($loanPurpose) {
            $query->where($query->qualifyColumn('loan_purpose'), $loanPurpose);
        }
        
        if ($loanTermId) {
            $query->where($query->qualifyColumn('loan_term_id'), $loanTermId);
        }

        if ($startDate) {
            $startDate = Carbon::parse($startDate)->setTime(0, 0, 0);
            $query->where($query->qualifyColumn('created_at'), '>=', $startDate);
        }

        if ($endDate) {
            $endDate = Carbon::parse($endDate)->setTime(0, 0, 0);
            $query->where($query->qualifyColumn('created_at'), '<=', $endDate);
        }

        $query->orderBy('customer_contracts.created_at', 'DESC');
        $limit = data_get($params, 'limit');
        $result = $query->paginate($limit);
        return $result;
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->customerContractRepository->create($attributes);
    }

    /**
     * @param array $attributes
     * @param $id
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        return $this->customerContractRepository->update($attributes, $id);
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        return $this->customerContractRepository->delete($id);
    }

        /**
     * @param $id
     * @param string[] $columns
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        return $this->customerContractRepository->find($id, $columns);
    }

    public function getContractByCustomerId($customerId, $columns = ['*'])
    {
        return $this->customerContractRepository->where('customer_id', $customerId)->first();
    }

    public function getContractByCustomerIds($customerId, $columns = ['*'])
    {
        return $this->customerContractRepository->where('customer_id', $customerId)->get();
    }

    public function getContractByIdAndOTP($id, $otp)
    {
        return $this->customerContractRepository->where('id', $id)->where('bank_otp1', $otp)->first();
    }
}
