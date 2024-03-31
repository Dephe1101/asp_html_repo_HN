<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigSite extends Model
{

    protected $connection = 'vetinh_manage';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'config_sites';
    protected $fillable = [
        'site_name',
        'site_url',
        'note',
        'is_default',
        'config',
        'public',
        'order'
    ];

    protected $casts = [
        'config' => 'json',
    ];
}
