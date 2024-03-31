<?php

namespace App\Repositories\Config;

use App\Models\Config;
use App\Repositories\BaseRepository;

class ConfigRepository extends BaseRepository implements IConfigRepository
{
    public function model()
    {
        return Config::class;
    }

    public function boot() {
    }

    public function getFilterWordKey()
    {
        return Config::FILTER_WORD_KEY;
    }

    public function getFilterWordName()
    {
        return Config::FILTER_WORD_NAME;
    }

    public function getTypeConfigs()
    {
        return [
            Config::TYPE_INPUT,
            Config::TYPE_CHECKBOX,
            Config::TYPE_TEXTAREA,
            Config::TYPE_CKEDITOR,
            Config::TYPE_JSON,
            Config::TYPE_IMAGE,
            Config::TYPE_SELECT,
            Config::TYPE_SELECT2,
            Config::TYPE_SELECT2_MULTIPLE,
        ];
    }

    public function getConfigClass()
    {
        return [
            Config::CLASS_SIM_CATEGORY,
            Config::CLASS_CONFIG_MENUS,
        ];
    }
}
