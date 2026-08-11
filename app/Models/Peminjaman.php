<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{

    protected $table = 'peminjamans';

    protected $fillable = [
        'anggota_id',
        'buku_id',
        'petugas_id',
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        'status',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}