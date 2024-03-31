<?php

namespace App\Services\Page;

use App\Services\ConfigSeo\IConfigSeoService;
use App\Repositories\Page\IPageRepository;
use Illuminate\Support\Facades\DB;
use App\Services\BaseService;
use Illuminate\Support\Facades\Log;
use App\Models\ConfigSeo;

class PageService extends BaseService implements IPageService
{
    protected $pageRepository;
    protected $configSeoService;

    public function __construct(
        IPageRepository $pageRepository,
        IConfigSeoService $configSeoService
    ) {
        $this->pageRepository = $pageRepository;
        $this->configSeoService = $configSeoService;
    }

    public function find($id, $columns = ['*'])
    {
        return $this->pageRepository->find($id, $columns);
    }

    public function all($columns = array('*'))
    {
        return $this->pageRepository->all($columns);
    }

    public function search(array $params)
    {
        $query = $this->pageRepository->getModel()->query();

        if ($keyword = data_get($params, 'keyword')) {
            $query->where($query->qualifyColumn('title'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('brief'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('content'), 'LIKE', "%{$keyword}%");
        }

        $query->orderBy('order', 'ASC');
        $query->orderBy('created_at', 'DESC');

        return $query->get();
    }

    public function searchAndPaginate(array $params)
    {
        $query = $this->pageRepository->getModel()->query();

        if ($keyword = data_get($params, 'keyword')) {
            $query->where($query->qualifyColumn('title'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('brief'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('content'), 'LIKE', "%{$keyword}%");
        }

        // SortHelper::sortUpdated($query, $params);

        $query->orderBy('order', 'ASC');
        $query->orderBy('created_at', 'DESC');
        $limit = data_get($params, 'limit', 10);
        return $query->paginate($limit);
    }

    public function create(array $attributes)
    {
        $page = $this->pageRepository->create($attributes);
        return $page;
    }

    public function update(array $attributes, $id)
    {
        $page = $this->pageRepository->update($attributes, $id);
        return $page;
    }

    public function delete($id)
    {
        return $this->pageRepository->delete($id);
    }

    public function getPageByUrl($url)
    {
        try {
            $configSeo = $this->configSeoService->getConfigSeoURLBySlug($url, 0, ConfigSeo::PAGE);
            return $this->pageRepository->firstWhere(['id' => $configSeo->table_id]);
        } catch (\Exception $e) {
            Log::error('get page error: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stacktrace' => $e->getTraceAsString(),
            ]);
        }
        return null;
    }

    public function getPageBySlug($slug)
    {
        $page = null;

        if ($slug) {
            $configSeo = $this->configSeoService->getConfigSeoURLBySlug($slug, 0, ConfigSeo::PAGE);
            if (!isset($configSeo)) {
                return null;
            }
            $page = $this->pageRepository->firstWhere(['id' => $configSeo->table_id]);
        }

        return $page;
    }

    /**
     * @param int|string $id
     * @param array $options
     */
    public function getPage($id, array $options = [])
    {
        return $this->pageRepository->where('public', 1)->find($id);
    }

    public function increaseView(int $id)
    {
        return $this->pageRepository->update([
            'view' => DB::raw('view + 1')
        ], $id);
    }
}
