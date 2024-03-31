<?php
namespace App\Repositories\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use App\Traits\PublicCriteriaTrait;

class PublicCriteria implements CriteriaInterface
{
    use PublicCriteriaTrait;
    /**
     * Apply criteria in query repository
     *
     * @param $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository){
        return $this->isPublic($model);
    }
}
