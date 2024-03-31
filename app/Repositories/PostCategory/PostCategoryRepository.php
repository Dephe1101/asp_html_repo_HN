<?php

namespace App\Repositories\PostCategory;

use App\Models\PostCategory;
use App\Repositories\BaseRepository;

class PostCategoryRepository extends BaseRepository implements IPostCategoryRepository
{
    public function model()
    {
        return PostCategory::class;
    }

    public function boot() {
        $this->skipCriteria();
    }
}
