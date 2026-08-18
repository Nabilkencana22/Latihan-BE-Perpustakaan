<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with(['anggota.user', 'buku', 'petugas'])->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar transaksi peminjaman',
            'data'    => $peminjaman
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'anggota_id'          => 'required|exists:anggotas,id',
            'buku_id'             => 'required|exists:bukus,id',
            'petugas_id'          => 'required|exists:users,id',
            'tanggal_pinjam'      => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        $buku = Buku::find($request->buku_id);
        if (!$buku) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        if ($buku->stok <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Stok buku habis, tidak dapat dipinjam'
            ], 400);
        }

        $validated['status'] = 'dipinjam';

        $peminjaman = Peminjaman::create($validated);

        $buku->decrement('stok');

        return response()->json([
            'success' => true,
            'message' => 'Transaksi peminjaman berhasil dicatat',
            'data'    => $peminjaman->load(['anggota.user', 'buku', 'petugas'])
        ], 201);
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['anggota.user', 'buku', 'petugas'])->find($id);

        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Data peminjaman tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail data peminjaman',
            'data'    => $peminjaman
        ], 200);
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Data peminjaman tidak ditemukan'
            ], 404);
        }

        if ($peminjaman->status === 'dipinjam') {
            $buku = Buku::find($peminjaman->buku_id);
            if ($buku) {
                $buku->increment('stok');
            }
        }

        $peminjaman->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data peminjaman berhasil dihapus'
        ], 200);
    }
}