<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class SortHelper
{
    public static function sort($query, $attributes = [], $mapFields = [])
    {
        self::mapFields($query, $attributes, $mapFields);

        if (empty($attributes['sort'])) {
            return;
        }

        self::orderByQuery($query, $attributes);
    }

    public static function sortUpdated($query, $attributes = [], $mapFields = [])
    {
        self::mapFields($query, $attributes, $mapFields);

        $attributes['sort'] = array_merge(
            [$query->qualifyColumn('updated_at') => 'DESC'],
            data_get($attributes, 'sort', [])
        );

        self::orderByQuery($query, $attributes);
    }

    public static function sortOrder($query, $attributes = [], $mapFields = [])
    {
        self::mapFields($query, $attributes, $mapFields);

        $attributes['sort'] = array_merge(
            [$query->qualifyColumn('order') => 'ASC'],
            data_get($attributes, 'sort', [])
        );

        self::orderByQuery($query, $attributes);
    }

    protected static function mapFields($query, &$attributes, &$mapFields)
    {
        if (isset($attributes['sort']) && !empty($mapFields)) {
            foreach ($attributes['sort'] as $key => $val) {
                if (isset($mapFields[$key])) {
                    $attributes['sort'][$mapFields[$key]] = $val;
                } else {
                    $attributes['sort'][$query->qualifyColumn($key)] = $val;
                }

                unset($attributes['sort'][$key]);
            }
        }
    }

    protected static function orderByQuery($query, &$attributes)
    {
        $validConditions = ['ASC', 'DESC'];

        foreach ($attributes['sort'] as $key => $value) {
            if (!$value) {
                $value = 'ASC';
            }

            if (!in_array(Str::upper($value), $validConditions)) {
                continue;
            }

            $query->orderBy($key, $value);
        }
    }
}