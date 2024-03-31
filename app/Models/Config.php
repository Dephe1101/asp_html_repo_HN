<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    const FILTER_WORD_KEY = 'CFW';
    const FILTER_WORD_NAME = 'Comment Filter Words';

    const TYPE_INPUT = 'input';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_CKEDITOR = 'ckeditor';
    const TYPE_JSON = 'json';
    const TYPE_IMAGE = 'image';
    const TYPE_SELECT = 'select';
    const TYPE_SELECT2 = 'select2';
    const TYPE_SELECT2_MULTIPLE = 'select2-multiple';

    const CLASS_POST_CATEGORY = 'post_category';
    const CLASS_SIM_CATEGORY = 'sim_category';
    const CLASS_CONFIG_MENUS = 'config_menus';

    public const CONFIG_FIRST_NUMBER = 'chon-dau-so';
    public const CONFIG_RANGE_PRICE = 'chon-khoang-gia';
    public const CONFIG_SIM_CATEGORY = 'sim-theo-loai';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_key',
        'key',
        'name',
        'type',
        'class',
        'value',
        'note',
        'public',
        'order',
        'data',
        'is_system',
    ];

    protected $casts = [
        'value' => 'array'
    ];

    public function configGroup()
    {
        return $this->belongsTo(ConfigGroup::class, 'group_key', 'key');
    }
}
