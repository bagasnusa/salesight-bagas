<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusTokoModel extends Model
{
    protected $table = 'status_toko';

    protected $fillable = [
        'user_id',
        'shopping_mall',

        'year_awal',
        'year_akhir',

        'sales_awal',
        'sales_akhir',

        'growth_percent',

        'status_toko'
    ];
}
