<?php
namespace App\Traits;

trait PublicCriteriaTrait {

    public function isPublic($model)
    {
        $model = $model->where($this->getPublicColumn(), '=', 1);
        return $model;
    }

    public function unPublic($model)
    {
        $model = $model->where($this->getPublicColumn(), '=', 0);
        return $model;
    }

    public function skipPublic($model)
    {
        return $model;
    }

    /**
     * Get the name of the "public" column.
     *
     * @return string
     */
    public function getPublicColumn()
    {
        return defined('static::PUBLIC') ? static::PUBLIC : 'public';
    }
}
