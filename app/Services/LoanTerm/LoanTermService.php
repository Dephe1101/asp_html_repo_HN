<?php

namespace App\Services\LoanTerm;

use Illuminate\Support\Facades\Log;
use App\Models\LoanTerm;
use App\Repositories\LoanTerm\ILoanTermRepository;
use App\Services\BaseService;

class LoanTermService extends BaseService implements ILoanTermService
{
    protected $loanTermRepository;

    public function __construct(ILoanTermRepository $loanTermRepository)
    {
        $this->loanTermRepository = $loanTermRepository;
    }

    public function find($id, $columns = ['*'])
    {
        return $this->loanTermRepository->find($id, $columns);
    }

    public function all($columns = array('*'))
    {
        return $this->loanTermRepository->all($columns);
    }

    public function search(array $params)
    {
        $query = $this->loanTermRepository->getModel()->query();

        $keyword = data_get($params, 'keyword');

        if ($keyword) {
            $query->where($query->qualifyColumn('title'), 'LIKE', "%{$keyword}%");
        }

        $query->orderBy('order', 'ASC');
        $query->orderBy('created_at', 'DESC');

        return $query->all();
    }

    public function searchAndPaginate(array $params)
    {
        $query = $this->loanTermRepository->getModel()->query();

        $keyword = data_get($params, 'keyword');

        if ($keyword) {
            $query->where($query->qualifyColumn('name'), 'LIKE', "%{$keyword}%");
        }

        $query->orderBy('order', 'ASC');
        $query->orderBy('created_at', 'DESC');

        $limit = data_get($params, 'limit');
        $result = $query->paginate($limit);
        return $result;
    }

    public function create(array $attributes)
    {
        $attributes = $this->formatData($attributes);
        return $this->loanTermRepository->create($attributes);
    }

    public function update(array $attributes, $id)
    {
        $attributes = $this->formatData($attributes);
        return $this->loanTermRepository->update($attributes, $id);
    }

    public function delete($id)
    {
        return $this->loanTermRepository->delete($id);
    }

    private function formatData($params)
    {
        $result = $this->mergeAttributes($params);
        return $result;
    }

    private function mergeAttributes($params)
    {
        return array_merge(array(
            'public' => 0,
            'order' => 1,
        ), $params);
    }

    /**
     * Get list city by format option
     *
     * @return array
     */
    public function getOptions(): array {
        return $this->loanTermRepository->pluck('name', 'id')->all();
    }
}
