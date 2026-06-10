<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Tentukan nama tabel dan primary key secara manual
    protected $table = 'user';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'level_id',
        'username',
        'nama',
        'password',
        'branch_id'
    ];

    protected $hidden = [
        'password',
    ];

    // Relasi ke tabel toko
    public function store()
    {
        return $this->hasOne(Store::class, 'user_id', 'user_id');
    }
}