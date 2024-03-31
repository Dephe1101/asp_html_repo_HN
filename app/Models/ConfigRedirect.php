<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigRedirect extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url_from',
        'url_to',
        'type',
        'public',
        'system_redirect',
        'order',
        'created_username',
    ];

    /** Code 301 */
    public const TYPE_301 = 301;
    /** Code 302 */
    public const TYPE_302 = 302;

    public const REDIRECT_SYSTEM_ROOT = 'root';
    public const REDIRECT_SYSTEM_CORRESPONDING = 'corresponding';

    /** Get code type*/
    public static function getCodeType()
    {
        return [
            static::TYPE_301,
            static::TYPE_302,
        ];
    }
}
