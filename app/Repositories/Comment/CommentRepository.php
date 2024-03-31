<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Repositories\BaseRepository;

class CommentRepository extends BaseRepository implements ICommentRepository
{
    public function model()
    {
        return Comment::class;
    }

    public function boot() {
        $this->skipCriteria()->skipPublic($this->model());
    }
}
