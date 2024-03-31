<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'banks';

    protected $fillable = [
        'name',
        'short_name',
        'content',
        'logo',
        'public',
        'address',
        'tel',
    ];
}
