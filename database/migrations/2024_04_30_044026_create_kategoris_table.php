<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('deskripsi',100)->nullable();
            $table->enum('kategori',['M','A','BHP','BTHP'])->default('A');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Drop the 'kategori' table
        Schema::dropIfExists('kategori');

    }
};
