<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('masterdaerahs', function (Blueprint $table) {
            $table->id();
            $table->string('namadaerah');
            $table->string('kategori');
            $table->string('alamat');
            $table->string('budgetperjalanan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masterdaerahs');
    }
};
