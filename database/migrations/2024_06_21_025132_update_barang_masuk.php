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
        CREATE TRIGGER after_barang_masuk_update
        AFTER UPDATE ON barangmasuk FOR EACH ROW
        BEGIN
        DECLARE qty_diff INT;
        SET qty_diff = NEW.qty_masuk - OLD.qty_masuk;
        UPDATE barang
        SET stok = stok + qty_diff
        WHERE id = NEW.barang_id;
        END;
    ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_barang_masuk_update');
    }
};