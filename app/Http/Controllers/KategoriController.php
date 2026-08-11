<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $semuaKategori = Kategori::all();

        return response()->json([
            'success' => true,
            'message' => 'Daftar kategori buku',
            'data' => $semuaKategori,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'nama_kategori' => 'required|string|min:3|max:50',
        ]);

        $kategori = Kategori::create($validate);

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $kategori,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return response()->json([
                'message' => 'Kategori tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'data' => $kategori,
        ], 200);
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return response()->json([
                'message' => 'Kategori tidak ditemukan',
            ], 404);
        }

        $validate = $request->validate([
            'nama_kategori' => 'required|string|max:50|unique:kategoris,nama_kategori',
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori
        ]);

        return response()->json([
            'message' => 'Kategori berhasil diupdate',
            'data' => $kategori,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = Kategori::find($id);

        if(!$kategori){
            return response()->json([
                'message' => 'Kategori tidak ditemukan',
                'data' => $kategori
            ]);
        }

        $kategori->delete();

        return response()->json([
            'message' => 'Kategori berhasil dihapus',
            'data' => $kategori
        ]);
    }
}
