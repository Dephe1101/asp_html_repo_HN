<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigGroup extends Model
{
    const COMMENT_KEY = 'CMT';
    const COMMENT_NAME = 'Comment';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'name',
        'note',
        'public',
        'order'
    ];

    public function configs()
    {
        return $this->hasMany(Config::class, 'group_key', 'key');
    }
}
