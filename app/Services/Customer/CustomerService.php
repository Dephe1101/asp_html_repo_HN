<?php

namespace App\Services\Customer;

use Illuminate\Support\Facades\Hash;
use App\Repositories\Customer\ICustomerRepository;
use App\Services\BaseService;
use Carbon\Carbon;

class CustomerService extends BaseService implements ICustomerService
{
    protected $customerRepository;

    public function __construct(ICustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|mixed
     */
    public function searchAndPaginate(array $params)
    {
        $query = $this->customerRepository->getModel()->query();

        $keyword = data_get($params, 'keyword');
        $bankId = data_get($params, 'bank_id');
        $loanPurpose = data_get($params, 'loan_purpose');
        $startDate = data_get($params, 'date_start');
        $endDate = data_get($params, 'date_end');
        $type = data_get($params, 'click_search', -1);

        if ($keyword) {
            $query->where($query->qualifyColumn('name'), 'LIKE', "%{$keyword}%");
        }

        if ($bankId) {
            $query->where($query->qualifyColumn('bank_id'), $bankId);
        }

        if ($loanPurpose) {
            $query->where($query->qualifyColumn('loan_purpose'), $loanPurpose);
        }
        switch ($type) {
            case 0:
                $startDate = Carbon::yesterday()->setTime(0, 0, 0);
                $endDate = Carbon::yesterday()->setTime(23, 59, 59);
                break;
            case 1:
                $startDate = Carbon::now()->setTime(0, 0, 0);
                $endDate = Carbon::now()->setTime(23, 59, 59);
                break;
            case 2:
                $startDate = Carbon::now()->startOfMonth()->setTime(0, 0, 0);
                $endDate = Carbon::now()->endOfMonth()->setTime(23, 59, 59);
                break;
            case 3:
                $startDate = Carbon::now()->subMonth()->startOfMonth()->setTime(0, 0, 0);
                $endDate = Carbon::now()->subMonth()->endOfMonth()->setTime(23, 59, 59);
                break;
        }

        if ($startDate) {
            if ($type = -1) {
                $startDate = Carbon::parse($startDate)->setTime(0, 0, 0);
            }
            $query->where($query->qualifyColumn('created_at'), '>=', $startDate);
        }

        if ($endDate) {
            if ($type = -1) {
                $endDate = Carbon::parse($endDate)->setTime(0, 0, 0);
            }
            $query->where($query->qualifyColumn('created_at'), '<=', $endDate);
        }

        $query->orderBy('created_at', 'DESC');
        $limit = data_get($params, 'limit');
        $result = $query->paginate($limit);
        return $result;
    }

    /**
     * @param $id
     * @param string[] $columns
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        return $this->customerRepository->find($id, $columns);
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        $email = data_get($attributes, 'email');
        $attributes["password"] = Hash::make(data_get($attributes, 'password'));
        $attributes["public"] = (int) data_get($attributes, 'public', 0);
        $attributes["order"] = (int) data_get($attributes, 'order', 0);
        $user = $this->customerRepository->create($attributes);
        $usernameTmp = explode('@', $email)[0];
        $userTmp = $this->customerRepository->where('username', $usernameTmp)->first();
        if($userTmp) {
            $usernameTmp = $usernameTmp . $user->id;
        }
        $user->username = $usernameTmp;
        $user->save();
        return $user;
    }

    /**
     * @param array $attributes
     * @param $id
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        return $this->customerRepository->update($attributes, $id);
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        return $this->customerRepository->delete($id);
    }
}
