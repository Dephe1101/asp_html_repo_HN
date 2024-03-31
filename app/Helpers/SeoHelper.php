<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\ConfigSeo;
use App\Services\ConfigSeo\IConfigSeoService;
use App\Helpers\StringHelper;
use App\Helpers\PriceHelper;
use App\Services\Config\IConfigService;
use App\Services\KeySearch\IKeySearchService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SeoHelper
{
    public static function getTimeCache()
    {
        return Carbon::now()->addSeconds(
            config('responsecache.cache_lifetime_in_seconds')
        );
    }

    public static function constTableNames()
    {
        return [
            'pages' => ConfigSeo::PAGE,
            'posts' => ConfigSeo::POST,
            'post_categories' => ConfigSeo::POST_CATEGORY,
            'tags' => ConfigSeo::TAG,
            'sim_categories' => ConfigSeo::SIM_CATEGORY,
        ];
    }

    public static function geTableName($tableName)
    {
        return isset(static::constTableNames()[$tableName]) ? static::constTableNames()[$tableName] : '';
    }
    
    public static function generateMetaData(
        $seoConfig,
        $simSearch = [],
        $post = null,
        $includeMetaNames = [],
        $excludeMetaNames = []
    ) {
        if (!empty($seoConfig)) {
            $seoContent = [
                $seoConfig->seo_title,
                $seoConfig->seo_keyword,
                $seoConfig->seo_description
            ];
            if (!empty($seoContent)) {
                $seoContent = implode($sep = ('<!-- ' . str_repeat('#', 10) . ' -->'), $seoContent);
                $seoContent = self::getCodePostSeo($seoContent, $simSearch, '', $post, true);
                $seoContent = explode($sep, $seoContent);
            }

            list($title, $keyword, $description) = $seoContent;
            $title = $title ?: config('app.name', 'Kho Sim Ve Tinh');
            unset($seoContent, $sep);
            $image = $seoConfig->image ? $seoConfig->image : '';
            /** @var IConfigService $configService */
            $configService = app(IConfigService::class);
            // Site name
            $siteNameConfig = $configService->getWithGroupAndKey('general_config', 'cau-hinh-ten-website');
            $siteName = (isset($siteNameConfig->value) && !empty($siteNameConfig->value)) ? $siteNameConfig->value : null;
            if ($image === '') {
                 /** @var IConfigSeoService $configSeoService */
                $configSeoService = app(IConfigSeoService::class);
                $configSeoDefault = $configSeoService->getConfigSeoURLBySlug(ConfigSeo::SEO_SIM_CATE_DEFAULT);
                $image = (isset($configSeoDefault->image) && !empty($configSeoDefault->image)) ? $configSeoDefault->image : '';
            }
            if ((empty($includeMetaNames) || in_array('title', $includeMetaNames)) && !in_array('title', $excludeMetaNames)) {
                $meta = '<title>' . $title . '</title>';
            }
            if ((empty($includeMetaNames) || in_array('Content-Type', $includeMetaNames)) && !in_array('Content-Type', $excludeMetaNames)) {
                $meta .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
            }
            if ((empty($includeMetaNames) || in_array('keywords', $includeMetaNames)) && !in_array('keywords', $excludeMetaNames)) {
                $meta .= '<meta name="keywords" content="' . $keyword . '"/>';
            }
            if ((empty($includeMetaNames) || in_array('description', $includeMetaNames)) && !in_array('description', $excludeMetaNames)) {
                $meta .= '<meta name="description" content="' . $description . '"/>';
            }
            if ($seoConfig->seo_noindex) {
                if ((empty($includeMetaNames) || in_array('robots', $includeMetaNames)) && !in_array('robots', $excludeMetaNames)) {
                    $meta .= '<meta name="robots" content="noindex, nofollow"/>';
                }
            } else {
                if ((empty($includeMetaNames) || in_array('robots', $includeMetaNames)) && !in_array('robots', $excludeMetaNames)) {
                    $meta .= '<meta name="robots" content="index, archive, follow, noodp"/>';
                }
                if ((empty($includeMetaNames) || in_array('googlebot', $includeMetaNames)) && !in_array('googlebot', $excludeMetaNames)) {
                    $meta .= '<meta name="googlebot" content="index, archive, follow, noodp"/>';
                }
            }
            if ((empty($includeMetaNames) || in_array('google', $includeMetaNames)) && !in_array('google', $excludeMetaNames)) {
                $meta .= '<meta name="google" content="nositelinkssearchbox"/>';
            }
            if ((empty($includeMetaNames) || in_array('revisit-after', $includeMetaNames)) && !in_array('google', $excludeMetaNames)) {
                $meta .= '<meta name="revisit-after" content="1 days"/>';
            }
            if ((empty($includeMetaNames) || in_array('viewport', $includeMetaNames)) && !in_array('viewport', $excludeMetaNames)) {
                $meta .= '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>';
            }
            //            $meta .= '<meta property="article:published_time" content="' . $seoConfig->created_at . '"/>';
            // $meta .= '<meta property="article:modified_time" content="' . $seoConfig->updated_at . '"/>';

            if (request()->route()->getName() == 'homepage') {
                if ((empty($includeMetaNames) || in_array('og:updated_time', $includeMetaNames)) && !in_array('og:updated_time', $excludeMetaNames)) {
                    $meta .= '<meta property="og:updated_time" content="' . date('Y-m-d\TH:i:sP', strtotime($seoConfig->updated_at)) . '"/>';
                }
                if ((empty($includeMetaNames) || in_array('article:modified_time', $includeMetaNames)) && !in_array('article:modified_time', $excludeMetaNames)) {
                    $meta .= '<meta property="article:modified_time" content="' . date('Y-m-d\TH:i:sP', strtotime($seoConfig->updated_at)) . '"/>';
                }
            }

            if ((empty($includeMetaNames) || in_array('og:type', $includeMetaNames)) && !in_array('og:type', $excludeMetaNames)) {
                $meta .= '<meta property="og:type" content="website"/>';
            }
            if ((empty($includeMetaNames) || in_array('og:locale', $includeMetaNames)) && !in_array('og:locale', $excludeMetaNames)) {
                $meta .= '<meta property="og:locale" content="vi_VN"/>';
            }
            if ((empty($includeMetaNames) || in_array('og:title', $includeMetaNames)) && !in_array('og:title', $excludeMetaNames)) {
                $meta .= '<meta property="og:title" content="' . $title . '"/>';
            }
            if ((empty($includeMetaNames) || in_array('og:description', $includeMetaNames)) && !in_array('og:description', $excludeMetaNames)) {
                $meta .= '<meta property="og:description" content="' . $description . '"/>';
            }
            if ($image) {
                if ((empty($includeMetaNames) || in_array('og:image', $includeMetaNames)) && !in_array('og:image', $excludeMetaNames)) {
                    $meta .= '<meta property="og:image" content="' . $image . '"/>';
                }
            }
            if ((empty($includeMetaNames) || in_array('og:url', $includeMetaNames)) && !in_array('og:url', $excludeMetaNames)) {
                $meta .= '<meta property="og:url" content="' . StringHelper::getUrl(null, true) . '"/>';
            }
            if ($siteName) {
                if ((empty($includeMetaNames) || in_array('og:site_name', $includeMetaNames)) && !in_array('og:site_name', $excludeMetaNames)) {
                    $meta .= '<meta property="og:site_name" content="' . $siteName . '"/>';
                }
            }
            if ((empty($includeMetaNames) || in_array('x-dns-prefetch-control', $includeMetaNames)) && !in_array('x-dns-prefetch-control', $excludeMetaNames)) {
                $meta .= '<meta http-equiv="x-dns-prefetch-control" content="on">';
            }
            if ((empty($includeMetaNames) || in_array('alternate', $includeMetaNames)) && !in_array('alternate', $excludeMetaNames)) {
                $meta .= '<link rel="alternate" hreflang="vi-vn" href="' . StringHelper::getUrl('/') . '">';
            }

            if ((empty($includeMetaNames) || in_array('canonical', $includeMetaNames)) && !in_array('canonical', $excludeMetaNames)) {
                $meta .= '<link rel="canonical" href="' . self::getCanonical($seoConfig->seo_canonical) . '"/>';
            }
            return $meta;
        }
        return  '';
    }

    public static function getCanonical($canonical) {
        if ((isset($canonical) && !empty($canonical))) {
            if (StringHelper::isFullUrl($canonical)) {
                return $canonical;
            }
            return StringHelper::getUrl($canonical);
        }
        return request()->url();
    }

    /**
     * Check placeholders in content
     * @param string $content
     * @param string|array $placeholders
     * @param boolean $any
     * @return boolean
     */
    public static function hasPlaceholders($content, $placeholders, $any = true)
    {
        $placeholders = (array) $placeholders;
        $cnt = 0;
        //
        foreach ($placeholders as $placeholder) {
            if (false !== stripos($content, $placeholder)) {
                $cnt++;
            }
        }

        return $any ? ($cnt > 0) : ($cnt > 0 && ($cnt === count($placeholders)));
    }

    public static function replacePriceFromContent($content)
    {
        if (static::hasPlaceholders($content, [
            $ph1 = '#giatu#', $ph2 = '#giatu1#',
            $ph3 = '#giaden#', $ph4 = '#giaden1#',
            $ph5 = ' - #giaden#', $ph6 = ' - #giaden1#'
        ]) && ($slug = request()->route('slug'))) {
            $aPrice = StringHelper::getPriceBySlug($slug);
            if (!empty($aPrice)) {
                $priceFrom = isset($aPrice[0]) ? (int)PriceHelper::formatPriceUnit($aPrice[0]) : 0;
                $priceTo = isset($aPrice[1]) ? (int)PriceHelper::formatPriceUnit($aPrice[1]) : 0;
                if ($priceFrom >= 0) {
                    $content = str_replace($ph1, PriceHelper::formatPriceCompactTwo($priceFrom), $content);
                    $content = str_replace($ph2, PriceHelper::formatPrice($priceFrom), $content);
                }
                if ($priceTo > 0) {
                    $content = str_replace($ph3, PriceHelper::formatPriceCompactTwo($priceTo), $content);
                    $content = str_replace($ph4, PriceHelper::formatPrice($priceTo), $content);
                } else {
                    $content = str_replace($ph5, '', $content);
                    $content = str_replace($ph6, '', $content);
                }
            }
        }
        return $content;
    }

    public static function replaceDiscountPrice($content, $sim)
    {
        preg_match_all("/(#giam)(\d+)(#)/", $content, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $k => $item) {
                $stringTmp = $item;
                $numberDiscount = isset($matches[2][$k]) ? (int)$matches[2][$k] : 0;
                if (isset($sim->sell_price) && !empty($sim->sell_price)) {
                    $priceDiscount = ($sim->sell_price * $numberDiscount) / 100;
                    $priceDiscount = PriceHelper::formatPrice($priceDiscount) . 'đ';
                    $content = str_replace($stringTmp, $priceDiscount, $content);
                }
                // $content = str_replace($stringTmp, '', $content);
            }
        }

        return $content;
    }

    public static function replaceDiscountPriceByConfig($content, $sim)
    {
        if (static::hasPlaceholders($content, $placeholder = '#giam#')) {
            if (isset($sim->sell_price) && !empty($sim->sell_price)) {
                $configDiscounts = Cache::get('config_seo_discount_price');
                if (empty($configDiscounts)) {
                    $configGroupKey = 'cau-hinh-seo';
                    $key = 'config_seo_percent_discount_by_price';
                    /** @var IConfigService $configService */
                    $configService = app(IConfigService::class);
                    $configs = $configService->getWithGroupAndKey($configGroupKey, $key);
                    $configDiscounts = [];
                    if (isset($configs->value) && !empty($configs->value)) {
                        $configDiscounts = (array) StringHelper::convertStringTextareaToArray($configs->value);
                    }
                    Cache::put('config_seo_discount_price', $configDiscounts, static::getTimeCache());
                }
                if (!empty($configDiscounts)) {
                    foreach ($configDiscounts as $item) {
                        if (empty($item)) {
                            continue;
                        }
                        $aConfigTmp = explode('|', $item);
                        if (empty($aConfigTmp)) {
                            continue;
                        }
                        $aPrice = explode('-', $aConfigTmp[0]);
                        if (empty($aPrice)) {
                            continue;
                        }
                        if ($sim->sell_price >= (int)$aPrice[0] && $sim->sell_price <= (int)$aPrice[1]) {
                            $priceDiscount = ($sim->sell_price * (int)$aConfigTmp[1]) / 100;
                            $priceDiscount = PriceHelper::formatPrice($priceDiscount) . 'đ';
                            $content = str_replace($placeholder, $priceDiscount, $content);
                            break;
                        }
                    }
                }
            }
            $content = str_ireplace(
                [$placeholder],
                [''],
                $content
            );
        }
        return $content;
    }

    public static function getBrand($brands, $numberBrand = 0)
    {
        $brand = '';
        if (!empty($brands)) {
            for ($i = $numberBrand; $i >= 0; $i--) {
                foreach ($brands as $value) {
                    if (empty($value)) {
                        continue;
                    }
                    $brandTmp = explode('|', $value);
                    if (!empty($brandTmp) && isset($brandTmp[1]) && !empty($brandTmp[1])) {
                        if ($i === (int)$brandTmp[0]) {
                            $brand = $brandTmp[1];
                            break;
                        }
                    }
                }
                if (!empty($brand)) {
                    break;
                }
            }
        }
        return !empty($brand) ? $brand : request()->getHost();
    }

    public static function replaceBrand($content)
    {
        if (static::hasPlaceholders($content, [
            $ph1 = '#brand#',
            $ph2 = '#brand1#',
            $ph3 = '#brand2#',
            $ph4 = '#brand3#',
            $ph5 = '#brand4#',
            $ph6 = '#brand5#',
        ])) {
            $brands = Cache::get('config_seo_brand');
            if (empty($brands)) {
                $configGroupKey = 'cau-hinh-seo';
                $key = 'cau-hinh-nhan-hieu';
                /** @var IConfigService $configService */
                $configService = app(IConfigService::class);
                $configBrand = $configService->getWithGroupAndKey($configGroupKey, $key);
                $brands = [];
                if (isset($configBrand->value) && !empty($configBrand->value)) {
                    $brands = (array) StringHelper::convertStringTextareaToArray($configBrand->value);
                }
                Cache::put('config_seo_brand', $brands, static::getTimeCache());
            }
            if (static::hasPlaceholders($content, $ph6)) {
                $content = str_replace($ph6, static::getBrand($brands, 5), $content);
            }
            if (static::hasPlaceholders($content, $ph5)) {
                $content = str_replace($ph5, static::getBrand($brands, 4), $content);
            }
            if (static::hasPlaceholders($content, $ph4)) {
                $content = str_replace($ph4, static::getBrand($brands, 3), $content);
            }
            if (static::hasPlaceholders($content, $ph3)) {
                $content = str_replace($ph3, static::getBrand($brands, 2), $content);
            }
            if (static::hasPlaceholders($content, $ph2)) {
                $content = str_replace($ph2, static::getBrand($brands, 1), $content);
            }
            if (static::hasPlaceholders($content, $ph1)) {
                $content = str_replace($ph1, static::getBrand($brands), $content);
            }
        }
        return $content;
    }

    public static function getRandomSimQuantily($simQuantilies, $strReplace, $numberCacheDay = 0, $path = null)
    {
        $randomSimQuantily = '';
        $path = $path ?: request()->path();
        if (!empty($simQuantilies)) {
            foreach ($simQuantilies as $value) {
                if (empty($value)) {
                    continue;
                }
                $simQuantily = explode('|', $value);
                if (empty($simQuantily) || !isset($simQuantily[1]) || empty($simQuantily[1])) {
                    continue;
                }
                if ($strReplace !== $simQuantily[0]) {
                    continue;
                }
                $randomSimQuantily = Cache::get('random_sim_quantily_path_'.$strReplace.'-' . json_encode($simQuantily));
                if (!empty($randomSimQuantily) && isset($randomSimQuantily[$path]) && $randomSimQuantily[$path]) {
                    break;
                }
                $numberRandom = $simQuantily[1];
                $aNumberRandom = explode('-', $numberRandom);
                if (!empty($aNumberRandom) && count($aNumberRandom) == 2) {
                    $dataRandom = rand((int)$aNumberRandom[0], (int)$aNumberRandom[1]);
                    if (!empty($randomSimQuantily)) {
                        $randomSimQuantily[$path] = $dataRandom;
                    } else {
                        $randomSimQuantily = [
                            $path => $dataRandom,
                        ];
                    }
                    Cache::put('random_sim_quantily_path_'.$strReplace.'-' . json_encode($simQuantily), $randomSimQuantily, now()->addDays($numberCacheDay));
                    break;
                }
            }
        }
        return $randomSimQuantily;
    }

    public static function replaceSimQuantily($content)
    {
        $placeholders = [];
        for ($i = 0; $i < 6; $i++) {
            $placeholders[] = ($i === 0) ? '#soluongsim#' : ('#soluongsim' . $i .'#');
        }
        if (static::hasPlaceholders($content, $placeholders)) {
            $simQuantilies = Cache::get('config_seo_number_sim_random');
            $configGroupKey = 'cau-hinh-seo';
            if (empty($simQuantilies)) {
                $keySimQuantily = 'cau-hinh-so-luong-sim-random';
                /** @var IConfigService $configService */
                $configService = app(IConfigService::class);
                $configSimQuantily = $configService->getWithGroupAndKey($configGroupKey, $keySimQuantily);
                $simQuantilies = [];
                if (isset($configSimQuantily->value) && !empty($configSimQuantily->value)) {
                    $simQuantilies = (array) StringHelper::convertStringTextareaToArray($configSimQuantily->value);
                }
                Cache::put('config_seo_number_sim_random', $simQuantilies, static::getTimeCache());
            }
            $numberCacheDay = Cache::get('config_seo_day_cache_random');
            if (empty($numberCacheDay)) {
                $keyNumberCacheDay = 'cau-hinh-ngay-luu-cache-random';
                /** @var IConfigService $configService */
                $configService = app(IConfigService::class);
                $configNumberCacheDay = $configService->getWithGroupAndKey($configGroupKey, $keyNumberCacheDay);
                $numberCacheDay = 0;
                if (isset($configNumberCacheDay->value) && !empty($configNumberCacheDay->value)) {
                    $numberCacheDay = (int) $configNumberCacheDay->value;
                }
                Cache::put('config_seo_day_cache_random', $numberCacheDay, static::getTimeCache());
            }
            $path = request()->path();
            foreach ($placeholders as $placeholder) {
                if (static::hasPlaceholders($content, $placeholder)) {
                    $aRandomData = static::getRandomSimQuantily($simQuantilies, $placeholder, $numberCacheDay, $path);
                    $randomNumber = isset($aRandomData[$path]) ? PriceHelper::formatPrice($aRandomData[$path]) : '';
                    $content = str_replace($placeholder, $randomNumber, $content);
                }
            }
        }
        return $content;
    }

    public static function replaceCountKeySearch($content, $keySearch)
    {
        if (static::hasPlaceholders($content, [
            $ph1 = '#searchs#',
        ])) {
            /** @var IKeySearchService $keySearchService */
            $keySearchService = app(IKeySearchService::class);
            $dataKeySearch = $keySearchService->findByKeyword($keySearch);
            $countKeySearch = 0;
            if (!empty($dataKeySearch)) {
                $countKeySearch = $dataKeySearch->count;
            }
            $replace = ('<!--[seo ' . http_build_query([
                'key' => 'searchs',
                'data' => $keySearch
            ]) . ']-->')
                . PriceHelper::formatPriceTwo($countKeySearch)
            . '<!--[/seo]-->';
            $content = str_replace($ph1, $replace, $content);
            $content = static::replacePhoBien($countKeySearch, $content);
            $content = str_ireplace(
                [$ph1],
                [''],
                $content
            );
        }
        return $content;
    }

    public static function replacePhoBien($count, $content)
    {
        if (static::hasPlaceholders($content, [
            $ph1 = '#phobien#',
        ])) {
            $aConfigPhoBien = Cache::get('config_seo_key_search_pho_bien');
            if (empty($aConfigPhoBien)) {
                $configGroupKey = 'cau-hinh-seo';
                $key = 'config_seo_common_key_search_pho_bien';
                /** @var IConfigService $configService */
                $configService = app(IConfigService::class);
                $configPhoBien = $configService->getWithGroupAndKey($configGroupKey, $key);
                $aConfigPhoBien = isset($configPhoBien->value) && !empty($configPhoBien->value) ? StringHelper::convertStringTextareaToArray($configPhoBien->value) : [];
                Cache::put('config_seo_key_search_pho_bien', $aConfigPhoBien, static::getTimeCache());
            }
            $replacePhoBien = '';
            if (!empty($aConfigPhoBien)) {
                foreach ($aConfigPhoBien as $item) {
                    if (empty($item)) {
                        continue;
                    }
                    $phoBien = explode('|', $item);
                    if (empty($phoBien) || !isset($phoBien[0]) || !isset($phoBien[1])) {
                        continue;
                    }
                    if ($count >= (int)$phoBien[0]) {
                        $replacePhoBien = $phoBien[1];
                    }
                }
            }
            $replace = ('<!--[seo ' . http_build_query([
                'key' => 'phobien'
            ]) . ']-->')
                . $replacePhoBien
            . '<!--[/seo]-->';
            $content = str_replace($ph1, $replace, $content);
        }
        return $content;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $post
     * @param string $content
     * @return string
     */
    public static function replaceViewByPost($post, $content)
    {

        if (static::hasPlaceholders($content, [
            $ph1 = '#view#',
        ])) {
            if (isset($post->url) && $post->url === ConfigSeo::SEO_HOME_PAGE) {
                $tableName = 'config_seos';
            } else if (!isset($post->url) && $post->getTable()) {
                $tableName = $post->getTable();
            } else {
                $tableName = $post->table_name;
            }
            $replace = ('<!--[seo ' . http_build_query([
                    'key' => 'view',
                    'data-table' => (isset($post->url) && $post->url === ConfigSeo::SEO_HOME_PAGE) ? $tableName : static::geTableName($tableName),
                    'data-url' => isset($post->url) ? $post->url : '',
                    'data-id' => $post->id
                ]) . ']-->')
                    . PriceHelper::formatPriceTwo($post->view)
                . '<!--[/seo]-->';
            return str_replace($ph1, $replace, $content);
        }
        return $content;
    }

    public static function replaceMD5($content, $md5Keyword)
    {
        if (static::hasPlaceholders($content, [
            $ph1 = '#md5#'
        ])) {
            $textMd5 = !empty($md5Keyword) ? __('Mã MD5 của ') . $md5Keyword .' : '. static::generateMd5Text($md5Keyword) : '';
            $replace = ('<!--[seo ' . http_build_query([
                    'key' => 'md5',
                    'data' => $md5Keyword
                ]) . ']-->')
                    . $textMd5
                . '<!--[/seo]-->'
            ;
            $content = str_replace($ph1, $replace, $content);
        }
        return $content;
    }

    public static function replaceKeySearchUpdateAt($content, $simInfos)
    {
        if (static::hasPlaceholders($content, [
            $ph1 = '#keysearch_updated_at#'
        ])) {
            /** @var IKeySearchService $keySearchService */
            $keySearchService = app(IKeySearchService::class);
            $dataKeySearch = $keySearchService->findByKeywordNew($simInfos);
            $updatedAt = '';
            if (!empty($dataKeySearch)) {
                $updatedAt = DateHelper::dateTimeVNFormat($dataKeySearch->updated_at);
            }
            $replace = ('<!--[seo ' . http_build_query([
                    'key' => 'keysearch_updated_at',
                    'data' => $simInfos
                ]) . ']-->')
                    . $updatedAt
                . '<!--[/seo]-->'
            ;
            $content = str_replace($ph1, $replace, $content);
        }
        return $content;
    }

    public static function replaceKeySearchNewUpdateAt($content, $simInfos)
    {
        if (static::hasPlaceholders($content, [
            $ph1 = '#keysearchnew_updated_at#'
        ])) {
            /** @var IKeySearchService $keySearchService */
            $keySearchService = app(IKeySearchService::class);
            $dataKeySearch = $keySearchService->findByKeywordNew($simInfos);
            $updatedAt = '';
            if (!empty($dataKeySearch)) {
                $updatedAt = DateHelper::dateTimeVNFormat($dataKeySearch->updated_at);
            }
            $replace = ('<!--[seo ' . http_build_query([
                    'key' => 'keysearchnew_updated_at',
                    'data' => $simInfos
                ]) . ']-->')
                    . $updatedAt
                . '<!--[/seo]-->'
            ;
            $content = str_replace($ph1, $replace, $content);
        }
        return $content;
    }

    public static function replaceSumKeySearch($content, $simInfos)
    {
        if (static::hasPlaceholders($content, [
            $ph1 = '#sum_keysearch#'
        ])) {
            /** @var IKeySearchService $keySearchService */
            $keySearchService = app(IKeySearchService::class);
            $dataKeySearch = $keySearchService->findByKeywordByRuleCode($simInfos['rule_code']);
            $sumKeySearch = 0;
            if (!empty($dataKeySearch)) {
                $sumKeySearch = $dataKeySearch->total;
            }
            $replace = ('<!--[seo ' . http_build_query([
                    'key' => 'sum_keysearch',
                    'data' => $simInfos
                ]) . ']-->')
                    . $sumKeySearch
                . '<!--[/seo]-->'
            ;
            $content = str_replace($ph1, $replace, $content);
        }
        return $content;
    }

    public static function replaceKeySearchTops($content, $simInfos, $isTripTagHtml = true)
    {
        preg_match_all("/(#keysearchtop)(\d+)(#)/", $content, $matches);
        if (isset($matches[0]) && !empty($matches[0])) {
            /** @var IKeySearchService $keySearchService */
            $keySearchService = app(IKeySearchService::class);
            //
            $keyReplace = $matches[0][0];
            $limitTop = isset($matches[2]) && !empty($matches[2]) ? (int)$matches[2][0] : 1;
            $dataKeySearchTop = (array) $keySearchService->findByKeywordTopByRuleCode($simInfos, $limitTop);
            $sumKeySearch = '';
            if (!empty($dataKeySearchTop)) {
                if ($isTripTagHtml) {
                    $sumKeySearch = implode(', ', $dataKeySearchTop);
                } else {
                    $aTmpKeySearch = [];
                    foreach ($dataKeySearchTop as $keySearch) {
                        $aTmpKeySearch[] = static::getLinkKeySearch($keySearch);
                    }
                    $sumKeySearch = implode(', ', $aTmpKeySearch);
                }
            }
            //
            $replace = ('<!--[seo ' . http_build_query([
                'key' => 'keysearchtop',
                'data' => $simInfos
            ]) . ']-->')
                . $sumKeySearch
            . '<!--[/seo]-->';
            $content = str_replace($keyReplace, $replace, $content);
        }
        return $content;
    }

    public static function replaceKeySearchNew($content, $isTripTagHtml = true)
    {
        if (static::hasPlaceholders($content, [
            $ph1 = '#keysearchnew#'
        ])) {
            /** @var IKeySearchService $keySearchService */
            $keySearchService = app(IKeySearchService::class);
            $dataKeySearch = $keySearchService->getKeySearchNew();
            $keySearchNew = '';
            if (!empty($dataKeySearch)) {
                if ($isTripTagHtml) {
                    $keySearchNew = $dataKeySearch->keyword;
                } else {
                    $keySearchNew = static::getLinkKeySearch($dataKeySearch->keyword);
                }
            }
            $replace = ('<!--[seo ' . http_build_query([
                    'key' => 'keysearchnew'
                ]) . ']-->')
                    . $keySearchNew
                . '<!--[/seo]-->'
            ;
            $content = str_replace($ph1, $replace, $content);
        }
        return $content;
    }

    public static function replaceKeySearchNewRC($content, $ruleCode, $isTripTagHtml = true)
    {
        if (static::hasPlaceholders($content, [
            $ph1 = '#keysearchnewrc#'
        ])) {
            /** @var IKeySearchService $keySearchService */
            $keySearchService = app(IKeySearchService::class);
            $dataKeySearch = $keySearchService->getKeySearchNew($ruleCode);
            $keySearchNewRC = '';
            if (!empty($dataKeySearch)) {
                if ($isTripTagHtml) {
                    $keySearchNewRC = $dataKeySearch->keyword;
                } else {
                    $keySearchNewRC = static::getLinkKeySearch($dataKeySearch->keyword);
                }
            }
            $replace = ('<!--[seo ' . http_build_query([
                    'key' => 'keysearchnewrc',
                    'data' => $ruleCode
                ]) . ']-->')
                    . $keySearchNewRC
                . '<!--[/seo]-->'
            ;
            $content = str_replace($ph1, $replace, $content);
        }
        return $content;
    }

    public static function replaceKeySearchALL($content)
    {
        if (static::hasPlaceholders($content, [
            $ph1 = '#keysearchall#'
        ])) {
            /** @var IKeySearchService $keySearchService */
            $keySearchService = app(IKeySearchService::class);
            $dataKeySearch = $keySearchService->totalKeyword();
            $sumKeySearch = 0;
            if (!empty($dataKeySearch)) {
                $sumKeySearch = $dataKeySearch->total;
            }
            $replace = ('<!--[seo ' . http_build_query([
                    'key' => 'keysearchall'
                ]) . ']-->')
                    . $sumKeySearch
                . '<!--[/seo]-->'
            ;
            $content = str_replace($ph1, $replace, $content);
        }
        return $content;
    }

    public static function getCodePostSeo($content = '', $sim = [], $md5Keyword = '', $post = null, $isTripTagHtml = false, $isReplaceEmpty = true)
    {
        $sim = (object) $sim;
        if (empty($content)) {
            return $content;
        }

        $content = static::replaceMD5($content, $md5Keyword);
        // Sim không dấu
        if (isset($sim->sim_search) && !empty($sim->sim_search)) {
            $content = str_replace('#sosim#', $sim->sim_search, $content);
            $content = str_replace('#duoi2#', substr($sim->sim_search, -2), $content);
            $content = str_replace('#duoi3#', substr($sim->sim_search, -3), $content);
            $content = str_replace('#duoi4#', substr($sim->sim_search, -4), $content);
            $content = str_replace('#duoi5#', substr($sim->sim_search, -5), $content);
            $content = str_replace('#duoi6#', substr($sim->sim_search, -6), $content);
            $content = str_replace('#dau2#', substr($sim->sim_search, 0, 2), $content);
            $content = str_replace('#dau3#', substr($sim->sim_search, 0, 3), $content);
            $content = str_replace('#dau4#', substr($sim->sim_search, 0, 4), $content);
        }
        // Sim Bình thường
        if (isset($sim->sim) && !empty($sim->sim)) {
            $content = str_replace('#sosim#', SimHelper::removeFormatSim($sim->sim), $content);
            $content = str_replace('#sim#', $sim->sim, $content);
        }
        if (isset($sim->network_name) && !empty($sim->network_name)) {
            $content = str_replace('#mang#', $sim->network_name, $content);
        }
        if (isset($sim->rule_name) && !empty($sim->rule_name)) {
            $content = str_replace('#loai#', $sim->rule_name, $content);
        }
        if (isset($sim->sell_price) && !empty($sim->sell_price)) {
            $content = str_replace('#gia#', number_format($sim->sell_price), $content);
        }

        if (isset($sim->rule_code) && !empty($sim->rule_code)) {
            $content = static::replaceKeySearchNewRC($content, $sim->rule_code, $isTripTagHtml);
        }

        $simInfos = [];

        if (isset($sim->first_number) && !empty($sim->first_number)) {
            $content = str_replace('#dauso#', $sim->first_number, $content);
        }

        if (isset($sim->last_number) && !empty($sim->last_number)) {
            $content = str_replace('#duoiso#', $sim->last_number, $content);
        }

        $content = str_replace('#domain#', request()->getHost(), $content);
        $content = str_replace('#domainfull#', request()->getSchemeAndHttpHost(), $content);
        $content = str_replace('#slug#' , request()->path(), $content);
        $content = str_replace('#nam#', date("Y"), $content);
        $content = str_replace('#thang#', now()->month, $content);
        $content = static::replacePriceFromContent($content);
        $content = static::replaceDiscountPrice($content, $sim);
        $content = static::replaceBrand($content);
        $content = static::replaceSimQuantily($content);
        $content = static::replaceDiscountPriceByConfig($content, $sim);
        $content = static::replaceKeySearchTops($content, $simInfos, $isTripTagHtml);
        $content = static::replaceKeySearchNew($content, $isTripTagHtml);
        $content = static::replaceKeySearchALL($content);

        if (!empty($post)) {
            $title = '';
            if (isset($post->title)) {
                $title = $post->title;
            } else if (isset($post->name)) {
                $title = $post->name;
            }
            $content = str_replace('#tentieude#', $title, $content);
            $content = static::replaceViewByPost($post, $content);
            $content = static::replacePhoBien($post->view, $content);
        }

        if ($isReplaceEmpty) {
            $content = str_ireplace(
                ['#sim#', '#duoi3#', '#duoi4#', '#duoi5#', '#duoi6#', '#dau2#', '#dau3#', '#dau4#', '#sosim#', '#mang#', '#duoi2#', '#loai#', '#gia#', '#keysearch#', '#dauso#', '#duoiso#', '#domain#',
                '#nam#', '#thang#', '#giatu#', '#giaden#', '#giatu1#', '#giaden1#', '#domainfull#', '#tentieude#', '#view#', '#slug#', '#sum_keysearch#', '#keysearch_updated_at#'],
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
                $content
            );
        }

        return trim($content);
    }

    /**
     * getConfigSeoBySlug
     *
     * @param string $slug
     * @param string $isKeySearch 0: get seo url; 1 get seo sim
     */
    public static function getConfigSeoBySlug($slug, $isKeySearch = 0, $entity = '')
    {
        // check sim theo gia
        $aPrice = StringHelper::getPriceBySlug($slug);
        if (!empty($aPrice)) {
            $slug = ConfigSeo::SEO_SIM_CATE_SIM_THEO_GIA;
            $isKeySearch = 1;
        }
        /** @var IConfigSeoService $configSeoService */
        $configSeoService = app(IConfigSeoService::class);
        $configSeo = $configSeoService->getConfigSeoURLBySlug(request()->path(), 0);
        if (empty($configSeo)) {
            $configSeo = $configSeoService->getConfigSeoURLBySlug($slug, $isKeySearch, $entity);
        }

        if (empty($configSeo)) {
            $configSeo = $configSeoService->getConfigSeoURLBySlug(ConfigSeo::SEO_SIM_CATE_DEFAULT);
        }
        return static::updateConfigSeo($configSeo);
    }

    public static function generateMd5Text($md5Keyword)
    {   
        if (!empty($md5Keyword)) {
            return md5($md5Keyword);
        }
        return '';
    }

    /**
     * getConfigSeoBySlug
     *
     * @param string $slug
     * @param string $isKeySearch 0: get seo url; 1 get seo sim
     */
    public static function getConfigSeoSimCategoryBySlug($slug)
    {
        /** @var IConfigSeoService $configSeoService */
        $configSeoService = app(IConfigSeoService::class);
        $configSeo = $configSeoService->getConfigSeoURLBySlug($slug, 0, 'sim_categories');
        if (!empty($configSeo)) {
            return static::updateConfigSeo($configSeo);
        }
        $configSeo = $configSeoService->getConfigSeoURLBySlug($slug, 0, null);
        if (!empty($configSeo)) {
            return static::updateConfigSeo($configSeo);
        }
        $configSeo = $configSeoService->getConfigSeoURLBySlug(ConfigSeo::SEO_SIM_CATE_DEFAULT);
        return static::updateConfigSeo($configSeo);
    }

    /**
     * getConfigSeoBySlug
     *
     * @param string $slug
     * @param string $isKeySearch 0: get seo url; 1 get seo sim
     */
    public static function checkSimCategoryBySlug($slug)
    {
        /** @var IConfigSeoService $configSeoService */
        $configSeoService = app(IConfigSeoService::class);
        $configSeo = $configSeoService->getConfigSeoURLBySlug($slug, 0, 'sim_categories');
        if (!empty($configSeo)) {
            return static::updateConfigSeo($configSeo);
        }
        Log::info('checkSimCategoryBySlug info: ', [json_encode($configSeo)]);
        return $configSeo;
    }

    public static function getContentBeforeSimCategory($configSeo = null) {
        $configContentBeforeSimCategory = Cache::get('config_seo_content_before_danh_muc_sim');
        $configGroupKey = 'cau-hinh-seo';
        if (empty($configContentBeforeSimCategory)) {
            $key = 'config_seo_content_before_danh_muc_sim';
            /** @var IConfigService $configService */
            $configService = app(IConfigService::class);
            $configContentBeforeSimCategory = $configService->getWithGroupAndKey($configGroupKey, $key);
            $configContentBeforeSimCategory = isset($configContentBeforeSimCategory->value) && !empty($configContentBeforeSimCategory->value) ? $configContentBeforeSimCategory->value : '';
            $configContentBeforeSimCategory = static::replaceConfigRandom($configContentBeforeSimCategory);
            Cache::put('config_seo_content_before_danh_muc_sim', $configContentBeforeSimCategory, static::getTimeCache());
            if (!empty($configSeo)) {
                /** @var IConfigSeoService $configSeoService */
                $configSeoService = app(IConfigSeoService::class);
                $dataConfig = [
                    "content_before_default" => $configContentBeforeSimCategory,
                ];
                $configSeo = $configSeoService->updateConfig($dataConfig, $configSeo->id);
            }
        }
        return $configContentBeforeSimCategory;
    }

    public static function getContentAfterCategory($configSeo = null) {
        $configContentAfterCategory = Cache::get('config_seo_content_after_category');
        $configGroupKey = 'cau-hinh-seo';
        if (empty($configContentAfterCategory)) {
            $key = 'config_seo_content_after_category';
            /** @var IConfigService $configService */
            $configService = app(IConfigService::class);
            $configContentAfterCategory = $configService->getWithGroupAndKey($configGroupKey, $key);
            $configContentAfterCategory = isset($configContentAfterCategory->value) && !empty($configContentAfterCategory->value) ? $configContentAfterCategory->value : '';
            $configContentAfterCategory = static::replaceConfigRandom($configContentAfterCategory);
            Cache::put('config_seo_content_after_category', $configContentAfterCategory, static::getTimeCache());
            if (!empty($configSeo)) {
                /** @var IConfigSeoService $configSeoService */
                $configSeoService = app(IConfigSeoService::class);
                $dataConfig = [
                    "content_after_default" => $configContentAfterCategory,
                ];
                $configSeo = $configSeoService->updateConfig($dataConfig, $configSeo->id);
            }
        }
        return $configContentAfterCategory;
    }

    public static function replaceConfigRandom($content) {
        preg_match_all("/(?<=\{)(.*?)(?=\})/", $content, $matches);
        if (isset($matches[0]) && !empty($matches[0])) {
            $aDataReplace = $matches[0];
            foreach ($aDataReplace as $strRepacle) {
                if (!empty($strRepacle)) {
                    $aTmpReplace = explode('|', $strRepacle);
                    if (!empty($aTmpReplace)) {
                        $kRand = array_rand($aTmpReplace);
                        $vRand = $aTmpReplace[$kRand];
                        $keySearch = '{'.$strRepacle.'}';
                        $content = str_replace($keySearch, $vRand, $content);
                    }
                }
            }
        }
        return $content;
    }

    public static function getLinkKeySearch($keySearch) {
        return '<a href="' . request()->getSchemeAndHttpHost() . '/tim-sim/' .$keySearch. '">'.$keySearch.'</a>';
    }

    public static function updateConfigSeo(&$configSeo) {
        if (!empty($configSeo)) {
            $configSeo->title = static::replaceConfigRandom($configSeo->title);
            $configSeo->content_before = static::replaceConfigRandom($configSeo->content_before);
            $configSeo->content_after = static::replaceConfigRandom($configSeo->content_after);
            $configSeo->seo_title = static::replaceConfigRandom($configSeo->seo_title);
            $configSeo->seo_keyword = static::replaceConfigRandom($configSeo->seo_keyword);
            $configSeo->seo_description = static::replaceConfigRandom($configSeo->seo_description);
            return $configSeo;
        }
        return $configSeo;
    }

    public static function generateClassWithId($id = 1, $isConfigSeo = true, $prefixClass = 'sidebar') {
        if ($isConfigSeo) {
            return 'config-' . $id . ' ' . str_replace('.', '-', request()->getHost());
        }
        return $prefixClass . '-' . $id;
    }

    public static function getPublishedUser()
    {
        return static::replaceBrand('#brand1#');
    }
}
