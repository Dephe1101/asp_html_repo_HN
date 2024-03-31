<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanTerm extends Model
{
    protected $table = 'loan_terms';

    protected $fillable = [
        'name',
        'term',
        'rate',
        'order',
        'public',
        'note',
    ];
}
