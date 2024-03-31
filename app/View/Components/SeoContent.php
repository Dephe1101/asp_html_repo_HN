<?php

namespace App\View\Components;

use App\Services\Config\IConfigService;
use App\Helpers\SeoHelper;
use App\Models\ConfigSeoByUrl;
use App\Helpers\AnyHelper;

class SeoContent extends BaseComponent
{
    /**
     * @var string
     */
    public $position;

    /**
     * @var array
     */
    public $suggestion = [];

    public $contentAfterCategory = null;

    /**
     * Create a new component instance.
     *
     * @param string|null $position
     * @param string|null $skip
     * @return void
     */
    public function __construct(IConfigService $configService, $position = null, $skip = null)
    {
        parent::__construct($configService);
        // before | after
        $this->position = ($position = strtolower($position) == 'before') ? $position : 'after';
        // +++
        $this->skip = explode(',', strtolower(trim($skip)));
        $this->skip = array_filter(array_map('trim', $this->skip));

        $this->getConfig();
    }

    /**
     * @return boolean
     */
    public function isPosBefore()
    {
        return 'before' == $this->position;
    }

    /**
     * @return boolean
     */
    public function isPosAfter()
    {
        return 'after' == $this->position;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $classPrefix = config('theme.encoding_class_prefix');
        $classSuffix = config('theme.encoding_class_suffix');
        return view('components.seo-content-' . $this->position, [
            'class_prefix' => $classPrefix,
            'class_suffix' => $classSuffix,
        ]);
    }

    /**
     * @return null
     */
    protected function getKeywordSuggestion()
    {
        if ($this->configService->isOnGoogleSuggestion() == 0) {
            return;
        }
        $configSeo = view()->getShared()['configSeo'] ?? null;
        // +++
        $skip = in_array('suggestion', $this->skip);
        // +++
        $debug = isset($_GET[$_ = 'debug-SeokeywordSuggestion']) ? $_GET[$_] : null;

        $debug && dump('0.1) skip: ' . $skip, '0.2) configSeo: ', $configSeo
            ? $configSeo->only(['id', 'table_name', 'table_id', 'url', 'title'])
            : null
        );

        if (!$skip && ($configSeo instanceof ConfigSeoByUrl)) {
            $tableId = $configSeo->table_id;
            if (!$tableId && (ConfigSeoByUrl::SEO_HOME_PAGE == $configSeo->url)) {
                $tableId = 0;
            }

            // +++  
            $configSuggLimit = $this->configService->getConfigGoogleSuggestionPaging();
            if ($configSuggLimit <= 0) {
                return;
            }

            $keyword = SeoHelper::getCodePostSeo($configSeo->seo_keyword, [], $configSeo->md5_keyword, $configSeo);

            //
            $seoKeywords = explode(',', str_replace(';', ',', $keyword));
            $seoKeywords = array_map('trim', $seoKeywords);
            $seoKeywords = array_map('mb_strtolower', $seoKeywords);
            $seoKeywords = array_unique($seoKeywords);
            if (empty($seoKeywords)) {
                return;
            }
            $debug && dump('1) keywords lay tu seo_keywords cua danh muc: ', $seoKeywords);

            //
            $txt2LnkKeywords = [];
            $dataText2LnkCache = AnyHelper::getDataSuggestionsByKey('cache_text_to_links_'. request()->getHost());
            $output = [];
            if (!empty($dataText2LnkCache)) {
                $dataText2LnkCache = isset($dataText2LnkCache->data) ? json_decode($dataText2LnkCache->data, true) : [];
                foreach ($seoKeywords as $seoKeyword) {
                    foreach ($dataText2LnkCache as $itemLink) {
                        if (strpos($itemLink['text'], $seoKeyword) !== false) {
                            $txt2LnkKeywords[] = $itemLink;
                        }
                    }
                }
            }
            $txt2LnkKeywords = array_reduce($txt2LnkKeywords, function ($result, $item) {
                if (!empty($item['text'])) {
                    $result = [$item['text'] => [
                        'text' => $item['text'],
                        'url' => $item['url'] ? preg_replace('/https?:\/\/(www\.)?([a-zA-Z0-9]+)(\.[a-zA-Z0-9.-]+)/', url('/'), $item['url']) : null
                    ]];
                }
                return $result;
            });

            $debug && dump('2) keywords lay tu text-to-link cua danh muc: ', $txt2LnkKeywords);
            
            $seoKeywords = implode(',', $seoKeywords);
            $dataSuggestionCache = AnyHelper::getDataSuggestionsByKey('cache_google_suggestions');
            $output = [];
            if (!empty($dataSuggestionCache)) {
                $dataSuggestionCache = isset($dataSuggestionCache->data) ? json_decode($dataSuggestionCache->data, true) : [];
                $output = isset($dataSuggestionCache[$seoKeywords]) ? $dataSuggestionCache[$seoKeywords] : [];
            }
            unset($seoKeywords);
            $debug && dump('4) keywords suggestion google: ', $output);
            // +++
            $blacklist = (array) $this->configService->getConfigGoogleSuggestionBlacklist();
            $debug && dump('3) blacklist: ', $blacklist);

            // +++ limit
            $suggestion = ['high' => [], 'normal' => []];
            collect($output)->map(function($text) use ($txt2LnkKeywords, $blacklist, &$suggestion) {
                foreach ($blacklist as $words) {
                    if (false !== mb_strpos($text['text'], $words)) {
                        return;
                    }
                }
                $item = $txt2LnkKeywords[$text['text']] ?? null;
                if (!empty($item)) {
                    $suggestion['high'][] = $item;
                    return;
                }
                $suggestion['normal'][] = [
                    'text' => $text['text'],
                    'url' => null
                ];
            })->toArray();

            unset($txt2LnkKeywords, $blacklist, $output);
            $suggestion = array_merge($suggestion['high'], $suggestion['normal']);
            $this->suggestion = array_chunk($suggestion, $configSuggLimit)[0] ?? [];
            $this->contentAfterCategory = SeoHelper::getContentAfterCategory();
            unset($suggestion);
            $debug && dump('5) keywords result: ', collect($this->suggestion)->pluck('text')->toArray());
        }
        ('dd' === $debug) && die();
    }

    /**
     * get data
     *
     * @return null
     */
    protected function getConfig($key = null)
    {
        $this->isPosAfter() && $this->getKeywordSuggestion();
    }
}
