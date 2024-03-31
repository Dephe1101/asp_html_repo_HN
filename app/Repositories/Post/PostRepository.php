<?php

namespace App\Repositories\Post;

use App\Models\Post;
use App\Repositories\BaseRepository;

class PostRepository extends BaseRepository implements IPostRepository
{
    public function model()
    {
        return Post::class;
    }

    public function boot() {
    }
}
