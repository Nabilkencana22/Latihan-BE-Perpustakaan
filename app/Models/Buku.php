<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'bukus';

    protected $fillable = [
        'kategori_id',
        'judul',
        'isbn',
        'stok',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
