<?php

namespace App\Services\Config;

use App\Repositories\Config\IConfigRepository;
use App\Repositories\ConfigGroup\IConfigGroupRepository;
use App\Repositories\ConfigMenuItem\IMenuItemRepository;
use App\Services\ConfigMenu\MenuService;
use Illuminate\Support\Facades\Cache;
use App\Services\BaseService;

class ConfigService extends BaseService implements IConfigService
{
    protected $configRepository, $configGroupRepository, $menuItemRepository;

    public function __construct(
        IConfigRepository $configRepository, 
        IConfigGroupRepository $configGroupRepository,
        IMenuItemRepository $menuItemRepository,
    )
    {
        $this->configRepository = $configRepository;
        $this->configGroupRepository = $configGroupRepository;
        $this->menuItemRepository = $menuItemRepository;
    }

    public function upsertFilterWord(array $attributes)
    {
        $configGroup = [
            'key' => $this->configGroupRepository->getCommentKey(),
            'name' => $this->configGroupRepository->getCommentName()
        ];

        $configGroup = $this->configGroupRepository->getModel()->firstOrCreate($configGroup);

        $attributes['value'] = array_values(array_filter(explode(PHP_EOL, $attributes['value'])));

        $config = array_merge([
            'key' => $this->configRepository->getFilterWordKey(),
            'name' => $this->configRepository->getFilterWordName(),
            'group_key' => $configGroup->key,
        ], $attributes);

        return $this->configRepository->updateOrCreate(
            ['key' => $this->configRepository->getFilterWordKey()],
            $config
        );
    }

    public function findFilterWord($columns = ['*'])
    {
        return $this->configRepository->firstWhere([
            'key' => $this->configRepository->getFilterWordKey()
        ]);
    }

    /**
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchAndPaginate(array $params): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->configRepository->getModel()->query();

        if ($keyword = data_get($params, 'keyword')) {
            $query->where($query->qualifyColumn('key'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('name'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('note'), 'LIKE', "%{$keyword}%");
        }

        if ($type = data_get($params, 'type')) {
            $query->where('type', $type);
        }

        $query->orderBy('order', 'ASC');
        $query->orderBy('created_at', 'DESC');
        $limit = data_get($params, 'limit', 10);
        return $query->paginate($limit);
    }

    public function create(array $attributes)
    {
        return $this->configRepository->create($attributes);
    }

    public function update(array $attributes, $id)
    {
        return $this->configRepository->update($attributes, $id);
    }

    public function delete($id)
    {
        return $this->configRepository->delete($id);
    }

    public function find($id, $columns = ['*'])
    {
        return $this->configRepository->find($id, $columns);
    }

    /**
     * @return array
     */
    public function getTypeConfigs()
    {
        return $this->configRepository->getTypeConfigs();
    }

    public function getConfigClass()
    {
        return $this->configRepository->getConfigClass();
    }

    public function getWithGroupAndKey($groupKey, $key)
    {
        return $this->configRepository->firstWhere([
            'key' => $key,
            'group_key' => $groupKey,
            'public' => 1
        ]);
    }

    public function updateWithKey(array $params, $key)
    {
        $config = $this->configRepository->findByField('key', $key)->first();
        if (empty($config->id)) {
            return false;
        }
        return $this->update($params, $config->id);
    }


    protected $cacheKey = 'config-service-cache-key';

    public function getAllConfig()
    {
        if (Cache::store('file')->has($this->cacheKey)) {
            return Cache::store('file')->get($this->cacheKey);
        }

        $query = $this->configRepository->getModel()->query();
        $query->where($query->qualifyColumn('public'), 1);
        $query->orderBy($query->qualifyColumn('order'), 'asc');
        $allConfig = $query->get();

        Cache::store('file')->rememberForever($this->cacheKey, function () use ($allConfig) {
            return $allConfig;
        });

        return $allConfig;
    }

    public function getMenuByConfigKey(array $params)
    {
        $config = null;
        $configData = $this->getAllConfig();

        $key = data_get($params, 'key');

        if ($configData->count() > 1) {
            foreach ($configData as $item) {
                if (strtolower($item->key) == strtolower($key)) {
                    $config = $item;
                    break;
                }
            }
        }

        if (empty($config) && $configData->count() > 0) {
            $config = $configData[0];
        }

        $result = $this->getMenuByKey($config);

        return $result;
    }

    private function getMenuByKey($params)
    {
        if (empty($params->class) || $params->class != 'config_menus' || empty($params->value)) {
            return null;
        }

        $menuService = app(MenuService::class);

        $configMenuItem = $menuService->getAllMenuItem();
        foreach ($configMenuItem as $k => $item) {
            if ($item->menu_id != $params->value) {
                $configMenuItem->forget($k);
            }
        }

        return $configMenuItem;
    }

    public function getWithGroup($groupKey, $options = [])
    {
        $configData = $this->getAllConfig();

        if ($configData->count() > 1) {
            $groupKey = strtolower($groupKey);
            foreach ($configData as $k => $item) {
                if (strtolower($item->group_key) != $groupKey) {
                    $configData->forget($k);
                }
            }
        }

        return $configData;
    }

    public function getConfigByKey($groupKey, $key)
    {
        $configData = $this->getAllConfig();

        if ($configData->count() > 1) {
            $groupKey = strtolower($groupKey);
            $key = strtolower($key);
            foreach ($configData as $item) {
                if (strtolower($item->group_key) == $groupKey && strtolower($item->key) == $key) {
                    return $item;
                }
            }
        }

        return null;
    }


    /**
     * @return array
     */
    public function getConfigSitemapPriorities()
    {
        $output = [];

        $config = $this->getConfigByKey('general_config', 'config_seo_common_sitemap_priorities');
        if ($config) {
            $values =  preg_split('/\r?\n/', trim($config->value));
            foreach ($values as $value) {
                list($type, $priority) = explode(':', "{$value}:");
                ($type && $priority) && ($output[$type] = (1 * $priority));
            }
        }

        return $output;
    }

    public function search(array $params)
    {
        $keyword = data_get($params, 'keyword');
        if (!isset($keyword)) {
            return [];
        }

        $configData = $this->getAllConfig();

        if ($configData->count() > 1) {
            $keyword = strtolower($keyword);
            foreach ($configData as $k => $item) {
                $key = strtolower($item->key);
                $name = strtolower($item->name);
                $note = strtolower($item->note);
                if (
                    strpos($key, $keyword) === false
                    && strpos($name, $keyword) === false
                    && strpos($note, $keyword) === false
                ) {
                    $configData->forget($k);
                }
            }
        }

        return $configData;
    }
}
