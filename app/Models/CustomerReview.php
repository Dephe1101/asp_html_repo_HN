<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerReview extends Model
{
    protected $table = 'customer_reviews';

    protected $fillable = [
        'id',
        'name',
        'gender',
        'career',
        'avatar',
        'note',
    ];
}
