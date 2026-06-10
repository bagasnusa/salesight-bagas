<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE status_toko MODIFY COLUMN status_toko ENUM(
            'Berkembang Pesat',
            'Tumbuh',
            'Stagnan',
            'Menurun',
            'Kritis',
            'Toko Baru',
            'Data Awal'
        ) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE status_toko MODIFY COLUMN status_toko ENUM(
            'Naik',
            'Turun',
            'Stagnan',
            'Data Awal'
        ) NOT NULL");
    }
};