<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id('branch_id');
            $table->unsignedBigInteger('user_id'); // Relasi ke Owner
            $table->string('name'); // Akan diisi dengan nama shopping_mall
            $table->string('location');
            $table->string('branch_code')->unique(); // Token Cabang
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};