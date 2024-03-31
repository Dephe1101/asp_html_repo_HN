<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model {
    use SoftDeletes;

    protected $table = 'config_menus';

    protected $fillable = [
        'key',
        'name',
        'image',
        'note',
        'public',
        'order',
        'is_main',
        'is_mobile',
    ];

    public function menuItems() {
        return $this->hasMany(MenuItem::class);
    }
}
