<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilEdasModel extends Model
{
    protected $table = 'hasil_edas';

    protected $fillable = [
        'user_id',
        'shopping_mall',
        'periode_year',

        'total_sales',
        'total_transaction',
        'total_quantity',
        'average_sales',

        'pda_sales',
        'pda_transaction',
        'pda_quantity',
        'pda_average_sales',

        'nda_sales',
        'nda_transaction',
        'nda_quantity',
        'nda_average_sales',

        'sp',
        'sn',

        'nsp',
        'nsn',

        'appraisal_score',

        'ranking_position'
    ];
}