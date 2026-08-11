<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $bukus = Buku::with('kategori')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar data buku',
            'data'    => $bukus
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'judul'       => 'required|string|max:255',
            'isbn'        => 'required|string|unique:bukus,isbn',
            'stok'        => 'required|integer|min:0',
        ]);

        $buku = Buku::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil ditambahkan',
            'data'    => $buku
        ], 201);
    }

    public function show($id)
    {
        $buku = Buku::with('kategori')->find($id);

        if (!$buku) {
            return response()->json([
                'success' => false,
                'message' => 'Data buku tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail data buku',
            'data'    => $buku
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'success' => false,
                'message' => 'Data buku tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'judul'       => 'required|string|max:255',
            'isbn'        => 'required|string|unique:bukus,isbn,' . $id,
            'stok'        => 'required|integer|min:0',
        ]);

        $buku->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil diperbarui',
            'data'    => $buku
        ], 200);
    }

    
    public function destroy($id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'success' => false,
                'message' => 'Data buku tidak ditemukan'
            ], 404);
        }

        $buku->delete();

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dihapus'
        ], 200);
    }
}