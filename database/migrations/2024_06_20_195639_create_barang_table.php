<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('merk',50)->nullable();
            $table->string('seri',50)->nullable();
            $table->text('spesifikasi')->nullable();
            $table->smallInteger('stok')->default(0);
            $table->tinyInteger('kategori_id')->unsigned(); // Change to tinyInteger
            $table->foreign('kategori_id')->references('id')->on('kategori')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
