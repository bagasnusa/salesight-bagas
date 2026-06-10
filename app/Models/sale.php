<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    // Mendefinisikan nama tabel secara eksplisit karena nama modelnya singular (Sale) 
    // dan nama tabel di database-mu adalah jamak (sales)
    protected $table = 'sales';

    // Mengizinkan semua kolom untuk diisi (Mass Assignment)
    protected $guarded = []; 
    
    // Opsional: Jika kolom timestamp di database-mu bukan 'created_at' & 'updated_at' bawaan Laravel
    // public $timestamps = false; 
}