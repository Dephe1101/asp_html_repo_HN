<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\DateHelper;

class Post extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $dates = ['published_at'];

    protected $fillable = [
        'category_id',
        'title',
        'brief',
        'content',
        'word_total',
        'popup_title',
        'view',
        'status',
        'public',
        'order',
        'published_by',
        'published_at',
        'created_username',
        'created_at',
    ];

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'post_tags');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\PostCategory');
    }

    public function configSeo()
    {
        return $this->hasOne(ConfigSeo::class, 'table_id')->where(['table_name' => ConfigSeo::POST]);
    }

    public function getPublishedAt()
    {
        $result = '';
        
        if ($this) {
            if (!empty($this->published_at)) {
                $result = DateHelper::dateTimeNewsFormat($this->published_at);

                return $result;
            } elseif (!empty($this->created_at)) {
                $result = DateHelper::dateTimeNewsFormat($this->created_at);

                return $result;
            }
        }

        return $result;
    }
}
