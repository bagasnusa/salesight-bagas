<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $primaryKey = 'branch_id';

    protected $fillable = [
        'user_id',
        'name',
        'location',
        'branch_code',
        'status'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}