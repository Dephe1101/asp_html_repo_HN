<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodeSeo extends Model
{
    const TYPE_HEADER = 'header';
    const TYPE_BODY = 'body';
    const TYPE_FOOTER = 'footer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'config_code_seos';
    protected $fillable = [
        'title',
        'type',
        'content',
        'note',
        'public',
        'order',
        'created_username',
    ];

    public static function getType()
    {
        return [
            static::TYPE_HEADER,
            static::TYPE_BODY,
            static::TYPE_FOOTER,
        ];
    }
}
