<?php

namespace App\Helpers;

class StringHelper
{
    public static function convertStringTextareaToArray($string)
    {
        if (!empty($string)) {
            return preg_split("/\r\n|\n|\r/", $string);
            // return explode(PHP_EOL, $string);
        }
        return [];
    }

    public static function isLastCharAsterisk($str)
    {
        return '*' == $str[strlen($str) - 1];
    }

    public static function getPriceBySlug($slug)
    {
        $result = [];
        preg_match_all("/[0-9]+[-][ngan|trieu|ty]+/", strtolower($slug), $matches);
        if (isset($matches[0]) && !empty($matches[0])) {
            $resultTmp = $matches[0];
            //handle when from price is 0
            if (count($resultTmp) === 1) {
                $aPriceFromTmp = explode('-', $resultTmp[0]);
                if (!empty($aPriceFromTmp) && $aPriceFromTmp[1] === 'ngan') {
                    $result[0] = '0';
                    $result[1] = $resultTmp[0];
                } else if(!empty($aPriceFromTmp) && $aPriceFromTmp[1] === 'trieu') {
                    $result[0] = $resultTmp[0];
                } else if (!empty($aPriceFromTmp) && $aPriceFromTmp[1] === 'ty') {
                    $result[0] = $resultTmp[0];
                }
            } else {
                $result = $resultTmp;
            }
        }
        return $result;
    }

    public static function removeHtmlTags($str = '')
    {
        return preg_replace('([!"#$&’\(\)\*\+,\-\./0123456789:;<=>\?ABCDEFGHIJKLMNOPQRSTUVWXYZ\[\\\]\^_‘abcdefghijklmnopqrstuvwxyz\{\|\}~¡¢£⁄¥ƒ§¤“«‹›ﬁﬂ–†‡·¶•‚„”»…‰¿`´ˆ˜¯˘˙¨˚¸˝˛ˇ—ÆªŁØŒºæıłøœß÷¾¼¹×®Þ¦Ð½−çð±Çþ©¬²³™°µ ÁÂÄÀÅÃÉÊËÈÍÎÏÌÑÓÔÖÒÕŠÚÛÜÙÝŸŽáâäàåãéêëèíîïìñóôöòõšúûüùýÿž€\'])', '', strip_tags($str));
    }

    public static function removeFormatSim(string $sim): string
    {
        return preg_replace('/\D/', '', $sim);
    }

    public static function getLenSimSearch($keyword)
    {
        return strlen(self::removeFormatSim(self::removeHtmlTags(trim($keyword))));
    }

    public static function md5Generate($keyword)
    {
        $keyword = SeoHelper::getCodePostSeo($keyword);
        return md5($keyword.'/'.request()->getHost());
    }

    public static function isFullUrl($url)
    {
        preg_match("(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})", $url, $matches);
        if (!empty($matches)) {
            return true;
        }
        return false;
    }

    public static function getUrl($url, $isCurrentDomain = true) {
        if ((isset($url) && !empty($url))) {
            return url($url);
        }
        if ($isCurrentDomain) {
            return request()->url();
        }
        return url('#');
    }
}
