<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class barang extends Model
{
    use HasFactory;
    protected $table='barang';
    protected $primaryKey='id';
    protected $fillable = [
        'merk',
        'seri',
        'spesifikasi',
        'stok',
        'kategori_id'
    ];

    public function kategori() {
        return $this->belongsTo(Kategori::class);
    }
}
