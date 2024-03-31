<?php

namespace App\Helpers;

use Jenssegers\Date\Date;

class DateHelper
{
    /**
     * @param $datetime
     * @return false|string
     */
    public static function formatDatetime($datetime)
    {
        return date('d/m/Y H:i:s', strtotime($datetime));
    }

    /**
     * @param $datetime
     * @return false|string
     */
    public static function formatDate($datetime)
    {
        return date('d/m/Y', strtotime($datetime));
    }

    public static function dateVNFormat($datetime)
    {
        return $datetime ? date('d/m/Y', strtotime($datetime)) : "";
    }

    public static function dateTimeVNFormat($datetime)
    {
        return $datetime ? date('H:i d/m/Y', strtotime($datetime)) : "";
    }

    public static function dateTimeVNFormatV2($datetime)
    {
        return $datetime ? date('d/m/Y H:i', strtotime($datetime)) : "";
    }

    public static function dateTimeNewsFormat($datetime)
    {
        $date = Date::createFromFormat('Y-m-d H:i:s', $datetime, 'GMT+7');
        // Đặt vùng thời gian thành GMT+7
        $date->setTimezone('GMT+7');
        // Định dạng theo ngôn ngữ Tiếng Việt
        $date->setLocale('vi');
        return $date->format('l, d/m/Y, H:i \G\M\TO');
    }

    public static function curDateVNFormat()
    {
        return date('d/m/Y');
    }
}
