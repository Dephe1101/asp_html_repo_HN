<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'note',
        'permissions',
        'public',
        'order',
        'created_username'
    ];

    protected $casts = [
        'permissions' => 'array'
    ];

    public function users() {
        return $this->hasMany('App\Models\User');
    }
}
