<?php

namespace App\Services\Bank;

use Illuminate\Support\Facades\Log;
use App\Models\Bank;
use App\Repositories\Bank\IBankRepository;
use App\Services\BaseService;

class BankService extends BaseService implements IBankService
{
    protected $bankRepository;

    public function __construct(IBankRepository $bankRepository)
    {
        $this->bankRepository = $bankRepository;
    }

    public function find($id, $columns = ['*'])
    {
        return $this->bankRepository->find($id, $columns);
    }

    public function all($columns = array('*'))
    {
        return $this->bankRepository->all($columns);
    }

    public function search(array $params)
    {
        $query = $this->bankRepository->getModel()->query();

        $keyword = data_get($params, 'keyword');
        $type = data_get($params, 'type');

        if ($keyword || $type) {
            $query->where($query->qualifyColumn('title'), 'LIKE', "%{$keyword}%")
                ->where($query->qualifyColumn('type'), 'LIKE', "%{$type}%");
        }

        $query->orderBy('order', 'ASC');
        $query->orderBy('created_at', 'DESC');

        return $query->all();
    }

    public function searchAndPaginate(array $params)
    {
        $query = $this->bankRepository->getModel()->query();

        $keyword = data_get($params, 'keyword');

        if ($keyword) {
            $query->where($query->qualifyColumn('name'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('short_name'), 'LIKE', "%{$keyword}%");
        }

        $query->orderBy('created_at', 'DESC');

        $limit = data_get($params, 'limit');
        $result = $query->paginate($limit);
        return $result;
    }

    public function create(array $attributes)
    {
        $attributes = $this->formatData($attributes);
        return $this->bankRepository->create($attributes);
    }

    public function update(array $attributes, $id)
    {
        $attributes = $this->formatData($attributes);
        return $this->bankRepository->update($attributes, $id);
    }

    public function delete($id)
    {
        return $this->bankRepository->delete($id);
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
        ), $params);
    }

    /**
     * Get list city by format option
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->bankRepository->pluck('name', 'id')->all();
    }
}
