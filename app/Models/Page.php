<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'brief',
        'content',
        'view',
        'created_username',
    ];

    public function configSeo() {
        return $this->hasOne(ConfigSeo::class, 'table_id')->where(['table_name' => ConfigSeo::PAGE]);
    }
}
