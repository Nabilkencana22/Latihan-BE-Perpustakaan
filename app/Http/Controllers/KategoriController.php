<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $semuaKategori = Kategori::all();

        return ApiResponse::success($semuaKategori, 'Daftar kategori buku', 200);
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

        return ApiResponse::success($kategori, 'Kategori berhasil ditambahkan', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kategori = Kategori::find($id);

        if (! $kategori) {
            return ApiResponse::error('Kategori tidak ditemukan', 404);
        }

        return ApiResponse::success($kategori, 'Detail kategori', 200);
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

        if (! $kategori) {
            return ApiResponse::error('Kategori tidak ditemukan', 404);
        }

        $validate = $request->validate([
            'nama_kategori' => 'required|string|max:50|unique:kategoris,nama_kategori',
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return ApiResponse::success($kategori, 'Kategori berhasil diupdate', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, string $role)
    {
        $kategori = Kategori::find($id);

        if (auth()->user()->role !== $role) {
            return ApiResponse::error('Anda tidak memiliki akses untuk mengakses operasi ini!', 403);
        }

        if (! $kategori) {
            return response()->json([
                'message' => 'Kategori tidak ditemukan',
                'data' => $kategori,
            ]);
        }

        $kategori->delete();

        return ApiResponse::success($kategori, 'Kategori berhasil dihapus', 200);
    }
}
