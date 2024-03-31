<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository as PrettusBaseRepository;
use App\Repositories\Criteria\PublicCriteria;
use App\Traits\PublicCriteriaTrait;

class BaseRepository extends PrettusBaseRepository implements IBaseRepository
{
    use PublicCriteriaTrait;

    /** @var int Paging: default limit */
    public const PAGER_LIMIT = 30;

    /** @var int Mark is non public status */
    public const NON_PUBLISHED = 0;

    /** @var int Mark is public status */
    public const PUBLISHED = 1;

    /** @var boolean Apply public criteria? */
    protected $_applyPublicCriteria = true;

    public function boot()
    {
        $this->_applyPublicCriteria && $this->pushCriteria(new PublicCriteria());
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return $this->getModelClass();
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function getModelClass()
    {}

    /**
     * Find data by multiple fields
     * @param array $where
     * @param array $columns
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function firstWhere(array $where, $columns = ['*']){
        $this->applyCriteria();
        $this->applyScope();

        $this->applyConditions($where);

        $model = $this->model->first($columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Helper: dump sql string
     *
     * @return string
     */
    protected function dumpSql()
    {
        $this->applyCriteria();
        $this->applyScope();
        $this->resetScope();
        return $this->toSql();
    }
}
