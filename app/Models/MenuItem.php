<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model {
    use SoftDeletes;

    protected $table = 'config_menu_items';

    protected $fillable = [
        'parent_id',
        'menu_id',
        'title',
        'link',
        'target',
        'public',
        'order',
        'image',
        'data',
        'class_css'
    ];

    public function menu() {
        return $this->belongsTo(Menu::class);
    }

    public function parent() {
        return $this->belongsTo(MenuItem::class,'parent_id')->where('parent_id',0);
    }

    public function children() {
        return $this->hasMany(MenuItem::class,'parent_id');
    }
}
