<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'title',
        'brief',
        'content',
        'view',
        'created_username',
    ];

    public function posts() {
        return $this->hasMany('App\Models\Post');
    }

    public function configSeo() {
        return $this->hasOne(ConfigSeo::class, 'table_id')->where(['table_name' => ConfigSeo::POST_CATEGORY]);
    }
}
