<?php

namespace App\Repositories\Tag;

use App\Models\Tag;
use App\Repositories\BaseRepository;

class TagRepository extends BaseRepository implements ITagRepository
{
    public function model()
    {
        return Tag::class;
    }

    public function boot() {
        $this->skipCriteria();
    }
}
