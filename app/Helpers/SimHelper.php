<?php

namespace App\Helpers;

use App\Models\Config;
use App\Models\ConfigSeo;
use App\Helpers\StringHelper;
use App\Services\API\Network\INetworkOperatorService;
use App\Services\Config\IConfigService;

class SimHelper
{
    const SIM = 'sim';
    const POST = 'post';
    const PAGE = 'page';

    public static function getSimSearchByKeyword($keyword)
    {
        $result = array('first_number' => '', 'last_number' => '', 'key_search' => $keyword);
        // Case: search keywords
        $keySearch = explode('*', $keyword);
        $keySearchFilter = array_filter($keySearch, function ($var) {return '' != trim($var);});
        $countKeySearch = count($keySearch);
        if (!empty($keySearch) && $countKeySearch > 0) {
            // Case: khong co '*' (VD: 6789) --> tim ends = keyword
            if ($countKeySearch == 1) {
                $lastNumber = $keySearch[0];
                // Case: co '*' o giua (VD: 09*6789) --> ...
            } else if ($countKeySearch == 2 && $keySearch[0] != '' && $keySearch[1] != '') {
                $firstNumber = $keySearch[0];
                $lastNumber = $keySearch[1];
                // Case: co '*' o cuoi (VD: 098*) --> tim start = keyword
            } else if ($countKeySearch == 2 && $keySearch[0] != '' && $keySearch[1] == '') {
                $firstNumber = $keySearch[0];
                // Case: co '*' o dau (VD: *6789) --> tim ends = keyword
            } else if ($countKeySearch == 2 && $keySearch[0] == '' && $keySearch[1] != '') {
                $lastNumber = $keySearch[1];
                // Case: co '*' o dau va cuoi (VD: 09*6789*, *8888*) --> tim relatives = keyword
            } else if ($countKeySearch == 3 && !empty($keySearchFilter)) {
                if (isset($keySearchFilter[0]) && $keySearchFilter[0] != '') {
                    $firstNumber = $keySearchFilter[0];
                }
                $lastNumber = "";
                if (isset($keySearchFilter[1]) && $keySearchFilter[1] != '') {
                    $lastNumber .= "{$keySearchFilter[1]}?*";
                }
                if (isset($ks_f[2]) && $keySearchFilter[2] != '') {
                    $lastNumber .= $keySearchFilter[2];
                }
                // Case: ...
            } else {
                $firstNumber = array_values(array_filter($keySearch));
                $firstNumber = isset($firstNumber[0]) ? trim($firstNumber[0]) : '';
            }
            unset($countKeySearch, $keySearchFilter);

            // Check Đầu số
            if (isset($firstNumber)) {
                $_firstNumber = preg_replace('/[^0-9]/', '', $firstNumber);
                $result['first_number'] = $_firstNumber;
                unset($_firstNumber);
            }

            // Check Đuôi số
            if (isset($lastNumber)) {
                $_lastNumber = preg_replace('/[^0-9*]/', '', $lastNumber);
                $result['last_number'] = $_lastNumber;
                unset($_lastNumber);
            }
        }
        return $result;
    }

    public static function getSlugConfigSeoBySimSearch($simSearch)
    {
        $_slug_ = ($slug = ConfigSeo::SEO_SIM_CATE_SO_KHONG_CHUA_DAU_SO);

        if (!empty($simSearch)) {
            // Case: tim 'o giua'
            if (($_slug_ == $slug) && isset($simSearch['last_number'])
                && ($simSearch['last_number'] != '')
                && StringHelper::isLastCharAsterisk($simSearch['last_number'])
            ) {
                $slug = ConfigSeo::SEO_SIM_CATE_NUMBER_MIDDLE;
            }
            // Case: co 'dau so'
            if (($_slug_ == $slug) && isset($simSearch['first_number']) && $simSearch['first_number'] != '') {
                $slug = ConfigSeo::SEO_SIM_CATE_DAU_SO_KHONG_CHUA_NHA_MANG;
                // Case: co 'dau so' khong co 'duoi so'
                if (!isset($simSearch['last_number']) || $simSearch['last_number'] == '') {
                    $slug = ConfigSeo::SEO_SIM_CATE_DAU_SO_KHONG_CO_DUOI;
                }
                // Case: co 'dau so' la nha mang
                elseif (!empty(self::isSimNhaMang($simSearch['first_number']))) {
                    $slug = ConfigSeo::SEO_SIM_CATE_DAU_SO_CHUA_NHA_MANG;
                }
            }
        }
        return $slug;
    }

    protected static function isSimNhaMang($firstNumber = 0, $simFull = ''): array
    {
        if ($firstNumber != 0) {
            $firstTwoNumbers = $firstNumber;
            $firstThreeNumbers = $firstNumber;
        } else {
            $firstTwoNumbers = !empty($simFull) ? self::simFirstFewNumbers($simFull, 2) : '';
            $firstThreeNumbers = !empty($simFull) ? self::simFirstFewNumbers($simFull, 3) : '';
        }
        $networks = self::getNetworks();
        foreach ($networks as $network) {
            $prefix = isset($network['prefix']) ? explode(',', $network['prefix']) : [];
            if (in_array($firstThreeNumbers, $prefix) || in_array($firstTwoNumbers, $prefix)) {
                return [
                    'code' => $network['code'],
                    'name' => $network['name'],
                    'group_code' => $network['code'],
                ];
            }
        }
        return [];
    }

    /**
     * Get all networks
     *
     * @return mixed
     */
    protected static function getNetworks()
    {
        /** @var INetworkOperatorService $networkService */
        $networkService = app(INetworkOperatorService::class);

        return $networkService->getList([]);
    }

    /**
     * Take the first few numbers
     *
     * @param string $sim
     * @param int $length
     *
     * @return string
     */
    public static function simFirstFewNumbers(string $sim, int $length): string
    {
        $sim = self::removeFormatSim($sim);

        return substr($sim, 0, $length);
    }

    /**
     * Remove all special characters
     *
     * @param string $sim
     *
     * @return string
     */
    public static function removeFormatSim(string $sim): string
    {
        return preg_replace('/\D/', '', $sim);
    }

    /**
     * Get all sim categories
     *
     * @return mixed
     */
    public static function getSimCategories()
    {
        return [];
    }

    public static function getConfigFilterSims($isMobile = false, $options = [])
    {
        $configGroupKey = 'loc-sim-theo-yeu-cau';
        /** @var IConfigService $configService */
        $configService = app(IConfigService::class);
        $allFilter = $configService->getWithGroup($configGroupKey, $options);
        $listFilter = [];

        foreach ($allFilter as $key => $item) {
            if ($item->key == Config::CONFIG_RANGE_PRICE) {
                $data = StringHelper::convertStringTextareaToArray($item->data);
                $optFilterPriceAll = [];
                if (!empty($data)) {
                    foreach ($data as $value) {
                        $dataTmp = explode('|', $value);
                        if (!empty($dataTmp)) {
                            $optFilterPriceAll[trim($dataTmp[0])] = trim($dataTmp[1]);
                        }
                    }
                }
                $optFilterPriceSelected = [];
                if (!empty($item->value) && !empty($optFilterPriceAll)) {
                    foreach ($item->value as $k => $v) {
                        $optFilterPriceSelected[$v] = $optFilterPriceAll[$v];
                    }
                }
                $listFilter[] = [
                    'name' => $item->name,
                    'key' => $item->key,
                    'data' => $optFilterPriceSelected,
                ];
            } else if ($item->key == Config::CONFIG_FIRST_NUMBER) {
                $listFilter[] = [
                    'name' => $item->name,
                    'key' => $item->key,
                    'data' => $item->value ? $item->value : [],
                ];
            } else if ($item->key == Config::CONFIG_SIM_CATEGORY && !$isMobile) {
                $simCategories = self::getSimCategories();
                $filterCategories = array_filter($simCategories, function ($v, $k) use ($item) {
                    return in_array($v['id'], $item->value);
                }, ARRAY_FILTER_USE_BOTH);
                if (!empty($filterCategories)) {
                    $categories = [];
                    foreach ($filterCategories as $filterCategory) {
                        $categories[$filterCategory['id']] = $filterCategory['name'];
                    }
                    $listFilter[] = [
                        'name' => $item->name,
                        'key' => $item->key,
                        'data' => $categories,
                    ];
                }
            }
        }
        $networks = self::getNetworks();
        if (!empty($networks)) {
            $networkOption = [];
            foreach ($networks as $network) {
                $networkOption[$network['code']] = $network['name'];
            }
            $listFilter[] = [
                'name' => 'Nhà mạng',
                'key' => 'network',
                'data' => $networkOption,
            ];
        }
        return $listFilter;
    }

    public static function getItemFilters($filterOptions, $routeParam = '', $routeName = 'search')
    {
        $queryParams = request()->query();
        $result = [];
        if (!empty($filterOptions) && !empty($queryParams)) {
            foreach ($filterOptions as $key => $filterOption) {
                switch ($filterOption['key']) {
                    case Config::CONFIG_FIRST_NUMBER:
                        $aQueryParam = isset($queryParams['d2']) ? explode(',', $queryParams['d2']) : [];
                        if (!empty($aQueryParam)) {
                            // TODO loop value get data
                            foreach ($aQueryParam as $key => $value) {
                                if (in_array($value, $filterOption['data'])) {
                                    $diff = implode(',', array_values(array_diff($aQueryParam, [$value])));
                                    $diff = ($diff != '') ? $diff : null;
                                    if ($routeName == 'search') {
                                        $link = route($routeName, [
                                            'keyword' => $routeParam,
                                            'd2' => $diff,
                                            'g' => data_get($queryParams, 'g'),
                                            'l' => data_get($queryParams, 'l'),
                                            'm' => data_get($queryParams, 'm'),
                                            'sort' => data_get($queryParams, 'sort'),
                                            'direct' => data_get($queryParams, 'direct')]);
                                    } else {
                                        $link = route($routeName, [
                                            'slug' => $routeParam,
                                            'd2' => $diff,
                                            'g' => data_get($queryParams, 'g'),
                                            'l' => data_get($queryParams, 'l'),
                                            'm' => data_get($queryParams, 'm'),
                                            'sort' => data_get($queryParams, 'sort'),
                                            'direct' => data_get($queryParams, 'direct')]);
                                    }
                                    $result[] = [
                                        'link' => $link,
                                        'name' => 'Đầu số ' . $value,
                                        'value' => $value,
                                    ];
                                }
                            }
                        }
                        break;
                    case Config::CONFIG_RANGE_PRICE:
                        $aQueryParam = isset($queryParams['g']) ? explode(',', $queryParams['g']) : [];
                        if (!empty($aQueryParam)) {
                            // TODO loop value get data
                            foreach ($aQueryParam as $key => $value) {
                                if (isset($filterOption['data'][$value]) && !empty($filterOption['data'][$value])) {
                                    $diff = implode(',', array_values(array_diff($aQueryParam, [$value])));
                                    $diff = ($diff != '') ? $diff : null;
                                    if ($routeName == 'search') {
                                        $link = route($routeName, [
                                            'keyword' => $routeParam,
                                            'd2' => data_get($queryParams, 'd2'),
                                            'g' => $diff,
                                            'l' => data_get($queryParams, 'l'),
                                            'm' => data_get($queryParams, 'm'),
                                            'sort' => data_get($queryParams, 'sort'),
                                            'direct' => data_get($queryParams, 'direct')]);
                                    } else {
                                        $link = route($routeName, [
                                            'slug' => $routeParam,
                                            'd2' => data_get($queryParams, 'd2'),
                                            'g' => $diff,
                                            'l' => data_get($queryParams, 'l'),
                                            'm' => data_get($queryParams, 'm'),
                                            'sort' => data_get($queryParams, 'sort'),
                                            'direct' => data_get($queryParams, 'direct')]);
                                    }
                                    $result[] = [
                                        'link' => $link,
                                        'name' => $filterOption['data'][$value],
                                        'value' => $value,
                                    ];
                                }
                            }
                        }
                        break;
                    case Config::CONFIG_SIM_CATEGORY:
                        $aQueryParam = isset($queryParams['l']) ? explode(',', $queryParams['l']) : [];
                        if (!empty($aQueryParam)) {
                            // TODO loop value get data
                            foreach ($aQueryParam as $key => $value) {
                                if (isset($filterOption['data'][$value]) && !empty($filterOption['data'][$value])) {
                                    $diff = implode(',', array_values(array_diff($aQueryParam, [$value])));
                                    $diff = ($diff != '') ? $diff : null;
                                    if ($routeName == 'search') {
                                        $link = route($routeName, [
                                            'keyword' => $routeParam,
                                            'd2' => data_get($queryParams, 'd2'),
                                            'g' => data_get($queryParams, 'g'),
                                            'l' => $diff,
                                            'm' => data_get($queryParams, 'm'),
                                            'sort' => data_get($queryParams, 'sort'),
                                            'direct' => data_get($queryParams, 'direct')]);
                                    } else {
                                        $link = route($routeName, [
                                            'slug' => $routeParam,
                                            'd2' => data_get($queryParams, 'd2'),
                                            'g' => data_get($queryParams, 'g'),
                                            'l' => $diff,
                                            'm' => data_get($queryParams, 'm'),
                                            'sort' => data_get($queryParams, 'sort'),
                                            'direct' => data_get($queryParams, 'direct')]);
                                    }
                                    $result[] = [
                                        'link' => $link,
                                        'name' => $filterOption['data'][$value],
                                        'value' => $value,
                                    ];
                                }
                            }
                        }
                        break;
                    default:
                        $aQueryParam = isset($queryParams['m']) ? explode(',', $queryParams['m']) : [];
                        if (!empty($aQueryParam)) {
                            // TODO loop value get data
                            foreach ($aQueryParam as $key => $value) {
                                if (isset($filterOption['data'][$value]) && !empty($filterOption['data'][$value])) {
                                    $diff = implode(',', array_values(array_diff($aQueryParam, [$value])));
                                    $diff = ($diff != '') ? $diff : null;
                                    if ($routeName == 'search') {
                                        $link = route($routeName, [
                                            'keyword' => $routeParam,
                                            'd2' => data_get($queryParams, 'd2'),
                                            'g' => data_get($queryParams, 'g'),
                                            'l' => data_get($queryParams, 'l'),
                                            'm' => $diff,
                                            'sort' => data_get($queryParams, 'sort'),
                                            'direct' => data_get($queryParams, 'direct')]);
                                    } else {
                                        $link = route($routeName, [
                                            'slug' => $routeParam,
                                            'd2' => data_get($queryParams, 'd2'),
                                            'g' => data_get($queryParams, 'g'),
                                            'l' => data_get($queryParams, 'l'),
                                            'm' => $diff,
                                            'sort' => data_get($queryParams, 'sort'),
                                            'direct' => data_get($queryParams, 'direct')]);
                                    }
                                    $result[] = [
                                        'link' => $link,
                                        'name' => $filterOption['data'][$value],
                                        'value' => $value,
                                    ];
                                }
                            }
                        }
                        break;
                }
            }
        }
        return $result;
    }

    public static function getSubCategoriesTagDefault($simCateId)
    {
        $aSimCateId = [];
        /** @var IConfigService $configService */
        $configService = app(IConfigService::class);
        $tagDefault = $configService->getWithGroupAndKey('cau-hinh-seo', 'config_seo_common_sub_categories_default');
        if ($tagDefault && $tagDefault->value) {
            $aSimCateId = $tagDefault->value;
        }
        $aSimCateId = array_filter($aSimCateId, function($value) use($simCateId) {
            return $value != $simCateId; 
        });
        
        if (empty($aSimCateId)) {
            return [];
        }
        return [];
    }
}
