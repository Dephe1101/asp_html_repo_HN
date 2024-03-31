<?php

namespace App\Services\CodeSeo;

use Illuminate\Support\Facades\Log;
use App\Models\CodeSeo;
use App\Repositories\CodeSeo\ICodeSeoRepository;
use App\Services\BaseService;

class CodeSeoService extends BaseService implements ICodeSeoService
{
    protected $codeSeoRepository;

    public function __construct(ICodeSeoRepository $codeSeoRepository)
    {
        $this->codeSeoRepository = $codeSeoRepository;
    }

    public function find($id, $columns = ['*'])
    {
        return $this->codeSeoRepository->find($id, $columns);
    }

    public function all($columns = array('*'))
    {
        return $this->codeSeoRepository->all($columns);
    }

    public function search(array $params)
    {
        $query = $this->codeSeoRepository->getModel()->query();

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
        $query = $this->codeSeoRepository->getModel()->query();

        $keyword = data_get($params, 'keyword');
        $type = data_get($params, 'type');

        if ($keyword || $type) {
            $query->where($query->qualifyColumn('title'), 'LIKE', "%{$keyword}%")
                ->where($query->qualifyColumn('type'), 'LIKE', "%{$type}%");
        }

        // SortHelper::sortUpdated($query, $params);

        $query->orderBy('order', 'ASC');
        $query->orderBy('created_at', 'DESC');

        $limit = data_get($params, 'limit');
        $result = $query->paginate($limit);
        return $result;
    }

    public function create(array $attributes)
    {
        $attributes = $this->formatData($attributes);
        return $this->codeSeoRepository->create($attributes);
    }

    public function update(array $attributes, $id)
    {
        $attributes = $this->formatData($attributes);
        return $this->codeSeoRepository->update($attributes, $id);
    }

    public function delete($id)
    {
        return $this->codeSeoRepository->delete($id);
    }

    public function getType()
    {
        return CodeSeo::getType();
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

    public function getCodeSeoByType(string $type)
    {
        try {
            $query = $this->codeSeoRepository->getModel()->query();
            $query->where($query->qualifyColumn('type'), $type);
            return $query->get();
        } catch (\Exception $e) {
            Log::error('get config seo error: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stacktrace' => $e->getTraceAsString(),
            ]);
        }
        return null;
    }

    public function getCodeSeo($params = null)
    {
        try {
            $query = $this->codeSeoRepository->getModel()->query();

            if (isset($params['type_in'])) {
                $query->whereIn($query->qualifyColumn('type'), $params['type_in']);
            }

            if (isset($params['public'])) {
                $query->where($query->qualifyColumn('public'), $params['public']);
            }

            $query->orderBy($query->qualifyColumn('order'), 'asc');

            return $query->get();
        } catch (\Exception $e) {
            Log::error('getCodeSeo error: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stacktrace' => $e->getTraceAsString(),
            ]);
        }

        return null;
    }
}
