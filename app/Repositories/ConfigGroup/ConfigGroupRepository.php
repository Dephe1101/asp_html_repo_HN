<?php

namespace App\Repositories\ConfigGroup;

use App\Models\ConfigGroup;
use App\Repositories\BaseRepository;

class ConfigGroupRepository extends BaseRepository implements IConfigGroupRepository
{
    public function model()
    {
        return ConfigGroup::class;
    }

    public function boot() {
    }

    public function getCommentKey()
    {
        return ConfigGroup::COMMENT_KEY;
    }

    public function getCommentName()
    {
        return ConfigGroup::COMMENT_NAME;
    }
}
