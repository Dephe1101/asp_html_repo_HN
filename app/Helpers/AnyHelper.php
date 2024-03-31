<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
class AnyHelper
{
    const PREFIX_REPLACE_LINK = '<!--[prefix_auto_replace_link]-->';
    const DOMAIN_KHOSIM = 'khosim.com';
    public const POSITION_SIDEBAR_LEFT = 'sidebar_left';
    public const POSITION_SIDEBAR_RIGHT = 'sidebar_right';
    public const POSITION_CONTENT_AFTER = 'content_after';
    public const POSITION_CONTENT_BEFORE = 'content_before';

    public static function getTimeCache()
    {
        return now()->addDays();
    }

    /**
     * @param string $html
     * @param array $options
     * @return string
     */
    public static function txt2Lnk($html, array $options = [], $configSeo = null)
    {
        if (empty($configSeo)) {
            return $html;
        }

        $html = SeoHelper::getCodePostSeo($html, [], $configSeo->md5_keyword, $configSeo, null, false, false);
        if (is_string($html) && $html) {
            //
            // opt: auto decode content?
            if (!(isset($options[$_ = 'skip_decode_html_entity']) && $options[$_])) {
                $html = html_entity_decode($html);
            }
            //
            // Tam thoi loai bo cac tag <a />, <h1../>, <img /> trong text
            $holdTags = [];
            $holdTagsFunc = function(&$html, array $opts = []) use (&$holdTags) {
                //
                $tags = (array) (isset($opts[$_ = 'tags']) ? $opts[$_] : ['a', 'img', 'h[123456]']);
                //
                foreach ($tags as $tag) {
                    $pat = '/<(' . $tag . ')\b[^>]*>.*?<\/\1>/is';
                    if ('img' == $tag) {
                        $pat = '/<(' . $tag . ')[^>]*\/?>/is';
                    }
                    // fix: tag <a /> nam trong attributes (title, alt,..)
                    if ($isTagAttr = (0 === strpos($tag, $_ = '__attr:'))) {
                        $tag = str_replace($_, '', $tag);
                        $pat = ($_ = '/') . '<(\w+) +\w+\s*=\s*([\'"]).*' . preg_quote($tag, $_) . ".*\\2[^>]*\/?>{$_}iU";
                        $tag = $_;
                    }
                    $html = preg_replace_callback($pat, function($m) use (&$holdTags, $isTagAttr) {
                        $tag = $m[1];
                        $holdTags[$tag] = $holdTags[$tag] ?? [];
                        //
                        $key = '<!--[t2L#' . "{$tag}:" . count($holdTags[$tag]) . ']-->';
                        $holdTags[$tag][$key] = $m[0];

                        return $key;
                    }, $html);
                }

                return $html;
            };
            $holdTagsFunc($html);
            //
            $collect = [];
            $dataText2LnkCache = AnyHelper::getDataSuggestionsByKey('cache_text_to_links_'. request()->getHost());
            if (!empty($dataText2LnkCache)) {
                $dataText2LnkCache = isset($dataText2LnkCache->data) ? json_decode($dataText2LnkCache->data, true) : [];
                $collect = array_merge($collect, $dataText2LnkCache);
            }
            $collect = collect($collect);
            // +++ unique item(s) by Url(s)
            $uniqueUrls = [];
            $collect->map(function($item) use (&$uniqueUrls) {
                ($item['unique'] == 1) && $uniqueUrls[$item['url']] = 0;
            });

            // +++ sort: longer text will have higher priority
            $collectNext = $collect->all();
            usort($collectNext, function ($a, $b) {
                if ($a['priority_satellite'] == $b['priority_satellite']) {
                    return mb_strlen($a['text']) < mb_strlen($b['text']);
                }
                return 0;
            });
            $collect = collect($collectNext);
            unset($collectNext);
            // +++ sort: text match url(s) will have higher priority
            if (isset($options[$_ = 'match_url']) && !empty($options[$_])) {
                $collectNext = ['high' => [], 'normal' => []];
                foreach ($collect as $item) {
                    if (in_array($options[$_], preg_split('/\r?\n/', trim($item['match_links'])))) {
                        $collectNext['high'][] = $item;
                        continue;
                    }
                    $collectNext['normal'][] = $item;
                }
                $collect = collect(array_merge($collectNext['high'], $collectNext['normal']));

            }
            unset($collectNext);
            //
            if (!$collect->isEmpty()) {
                // pattern normalize whitespaces
                $patWS = '(?:' . implode('|', [chr(32), chr(160), chr(194), chr(194) . chr(160)]) . ')+';
                //
                $iReplace = 0;
                foreach ($collect as $item) {
                    if ($iReplace >= 4) {
                        break;
                    }
                    $textArr = array_filter(explode('|', trim($item['text'])));
                    foreach ($textArr as $text) {
                        // fix: tag <a /> nam trong attributes (title, alt,..)
                        $holdTagsFunc($html, ['tags' => ['__attr:' . $text]]);
                        // normalize whitespaces
                        $pat = preg_replace(($_ = '/') . $patWS . "{$_}i", $patWS, preg_quote($text));
                        $pat = ($_ = '/') . '(?:^|\W)(' . $pat . ")(?:\W|$){$_}is";
                        //
                        $html = preg_replace_callback(
                            $pat,
                            function($m) use ($item, &$uniqueUrls, &$iReplace) {
                                // Check unique
                                if (isset($uniqueUrls[$item['url']])) {
                                    if ($uniqueUrls[$item['url']] > 0) {
                                        return $m[0];
                                    }
                                    ++$uniqueUrls[$item['url']];
                                }

                                if (($item['is_khosim_link'] === 1) && $iReplace === 0) {
                                    $url = $item['url'];
                                } else {
                                    preg_match('/(www\.)?([a-zA-Z0-9]+)(\.[a-zA-Z0-9.-]+)/', $item['url'], $matchDomain);
                                    if (isset($matchDomain[0]) && ($matchDomain[0] !== static::DOMAIN_KHOSIM)) {
                                        $url = $item['url'];
                                    } else {
                                        $url = preg_replace('/https?:\/\/(www\.)?([a-zA-Z0-9]+)(\.[a-zA-Z0-9.-]+)/', url('/'), $item['url']);
                                    }
                                }
                                // Build tag <a />
                                $a = '<a '
                                    . ('href="' . $url . '" ')
                                    . ($item['attr_rel'] ? ('rel="' . $item['attr_rel'] . '" ') : '')
                                    . ($item['attr_extra'] ? ($item['attr_extra'] . ' ') : '')
                                . '><b>' . $m[1] . '</b></a>';
                                $a = str_replace($m[1], $a, $m[0]);
                                if (strpos($m[0], $m[1]) !== false) {
                                    ++$iReplace;
                                }
                                return $a;
                            },
                            $html,
                            intval($item['match_count'])
                        );
                        //
                        // Replace <a /> tags, prevent duplication
                        $holdTagsFunc($html, ['tags' => ['a']]);
                    }
                }
                unset($item, $text);
            }
            //
            // Tra lai cac tags replace tam (<a />, <img />,...)
            if (!empty($holdTags)) {
                foreach ($holdTags as $items) {
                    foreach ($items as $key => $value) {
                        $html = str_replace($key, $value, $html);
                    }
                }
                unset($items, $key, $value);
            }
            unset($holdTags);
        }
        return $html;
    }

    public static function getCampaignLinksByPositionURL($position = null)
    {
        $collect = [];
        $dataCampaignLinkCache = self::getDataSuggestionsByKey('cache_campaign_links_'. request()->getHost());
        if (!empty($dataCampaignLinkCache)) {
            $dataCampaignLinkCache = isset($dataCampaignLinkCache->data) ? json_decode($dataCampaignLinkCache->data, true) : [];
            $collect = array_merge($collect, $dataCampaignLinkCache);
        }
        $collect = collect($collect);
        $filtered = $collect;
        if ($position) {
            $filtered = $collect->filter(function ($value, $key) use ($position) {
                $now = Carbon::now();
                return (
                    ($value['url_at'] === url()->current()) &&
                    ($value['position'] === $position) &&
                    (($value['begin_at'] === null) || ($value['end_at'] === null) || (($value['begin_at'] <= $now) && ($value['end_at'] >= $now)))
                );
            });
        }
        return $filtered->toArray();
    }
}
