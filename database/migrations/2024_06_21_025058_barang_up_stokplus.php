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
        DB::unprepared('
        CREATE TRIGGER barang_up_stokplus 
        AFTER INSERT ON barangmasuk
        FOR EACH ROW
        BEGIN
        UPDATE barang
        SET barang.stok = barang.stok + NEW.qty_masuk
        WHERE barang.id=NEW.barang_id; 
        END;
    ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS barang_up_stokplus');
    }
};