<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'view',
        'created_username',
    ];

    public function posts()
    {
        return $this->belongsToMany('App\Models\Post', 'post_tags');
    }

    public function configSeo() {
        return $this->hasOne(ConfigSeo::class, 'table_id')->where(['table_name' => ConfigSeo::TAG]);
    }
}
