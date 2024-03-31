<?php

namespace App\Helpers;

class PriceHelper
{
    public static function formatPrice($number, $decimals = 0)
    {
        return number_format($number, $decimals, ',', '.');
    }

    public static function formatPriceTwo($number, $decimals = 0)
    {
        return number_format($number, $decimals, '.', ',');
    }

    public static function removeFormatPrice($price = '')
    {
        $price = trim($price);
        $price = str_replace(' ', '', $price);
        $price = str_replace(',', '', $price);
        $price = str_replace('-', '', $price);
        $price = str_replace('.', '', $price);
        $price = (int) $price;

        return $price;
    }

    /**
     * Get price format
     * @param string $sell_price
     */
    public static function formatPriceCompact($sell_price)
    {
        if ($sell_price >= 1000000) {
            $sell_price = substr($sell_price, 0, strlen($sell_price) - 5);
            $strlen = strlen($sell_price);
            if (substr($sell_price, -1) == 0) {
                $sell_price = substr($sell_price, 0, $strlen - 1);
            } else {
                $sell_price = substr($sell_price, 0, $strlen - 1) . ',' . $sell_price[$strlen - 1];
            }
            $sell_price .= 'tr';
        } else if($sell_price >= 100000) {
            $sell_price = substr($sell_price, 0, 3);
            $sell_price .= 'k';
        } else {
            $sell_price = number_format($sell_price);
        }
        return $sell_price;
    }

    /**
     * Get price format
     * @param string $price
     */
    public static function formatPriceUnit($price)
    {
        if ($price == 0) {
            return $price;
        }

        $aPrice = explode('-', $price);
        if (!empty($aPrice)) {
            if (count($aPrice) == 1) {
                return $aPrice[0];
            }
            $strUnit = $aPrice[1];
            $priceNumber = $aPrice[0];
            if ($strUnit == 'ngan') {
                return (int)$priceNumber * 1000;
            }
            if ($strUnit == 'trieu') {
                return (int)$priceNumber * 1000000;
            }
            if ($strUnit == 'ty') {
                return (int)$priceNumber * 1000000000;
            }
        }
        return 0;
    }

    public static function listRangePrice() {
        return [
            1 => '0-500000',
            2 => '500000-1000000',
            3 => '1000000-2000000',
            4 => '2000000-5000000',
            5 => '5000000-10000000',
            6 => '10000000-20000000',
            7 => '20000000-50000000',
            8 => '50000000-100000000',
            9 => '100000000-'.PHP_INT_MAX,
        ];
    }

    public static function getRangePriceByKey($key) {
        return isset(self::listRangePrice()[$key]) ? self::listRangePrice()[$key] : null;
    }

    /**
     * Get price format
     * @param string $price
     */
    public static function formatPriceCompactTwo($price)
    {
        if ($price >= 1000000000) {
            $price = substr($price, 0, strlen($price) - 8);
            $strlen = strlen($price);
            if (substr($price, -1) == 0) {
                $price = number_format((int)substr($price, 0, $strlen - 1));
            } else {
                $price = substr($price, 0, $strlen - 1) . ',' . $price[$strlen - 1];
            }
            $price .= ' tỷ';
        } else if ($price >= 1000000) {
            $price = substr($price, 0, strlen($price) - 6);
            $price .= ' triệu';
        } else if($price >= 100000) {
            $price = substr($price, 0, 3);
            $price .= 'k';
        } else {
            $price = number_format($price);
        }
        return $price;
    }
}
