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
        Schema::create('alokasis', function (Blueprint $table) {
            $table->id();
            $table->string('nosurat')->nullable();
            $table->string('status')->nullable();
            $table->string('id_daerah');
            $table->string('nominal');
            $table->string('keperluan');
            $table->string('tanggal');
            $table->string('penanggungjawab');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alokasis');
    }
};
