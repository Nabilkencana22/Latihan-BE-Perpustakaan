<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalian = Pengembalian::with(['peminjaman.anggota.user', 'peminjaman.buku', 'petugas'])->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar transaksi pengembalian',
            'data'    => $pengembalian
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peminjaman_id'        => 'required|exists:peminjamans,id',
            'petugas_id'           => 'required|exists:users,id',
            'tanggal_pengembalian' => 'required|date',
            'denda'                => 'nullable|numeric|min:0',
        ]);

        $peminjaman = Peminjaman::find($request->peminjaman_id);

        if ($peminjaman->status === 'dikembalikan') {
            return response()->json([
                'success' => false,
                'message' => 'Buku untuk transaksi peminjaman ini sudah dikembalikan sebelumnya'
            ], 400);
        }

        $pengembalian = Pengembalian::create([
            'peminjaman_id'        => $request->peminjaman_id,
            'petugas_id'           => $request->petugas_id,
            'tanggal_pengembalian' => $request->tanggal_pengembalian,
            'denda'                => $request->denda ?? 0,
        ]);

        $peminjaman->update(['status' => 'dikembalikan']);

        $buku = Buku::find($peminjaman->buku_id);
        $buku->increment('stok');

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dikembalikan dan stok diperbarui',
            'data'    => $pengembalian->load(['peminjaman', 'petugas'])
        ], 201);
    }

    public function show($id)
    {
        $pengembalian = Pengembalian::with(['peminjaman.anggota.user', 'peminjaman.buku', 'petugas'])->find($id);

        if (!$pengembalian) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengembalian tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail data pengembalian',
            'data'    => $pengembalian
        ], 200);
    }

    public function destroy($id)
    {
        $pengembalian = Pengembalian::find($id);

        if (!$pengembalian) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengembalian tidak ditemukan'
            ], 404);
        }

        $pengembalian->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pengembalian berhasil dihapus'
        ], 200);
    }
}