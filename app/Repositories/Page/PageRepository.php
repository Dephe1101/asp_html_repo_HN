<?php

namespace App\Repositories\Page;

use App\Models\Page;
use App\Repositories\BaseRepository;

class PageRepository extends BaseRepository implements IPageRepository
{
    public function model()
    {
        return Page::class;
    }

    public function boot() {
    }
}
