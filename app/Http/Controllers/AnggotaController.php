<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggota = Anggota::with('user')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar data anggota',
            'data'    => $anggota
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'       => 'required|exists:users,id|unique:anggotas,user_id',
            'nomor_anggota' => 'required|string|unique:anggotas,nomor_anggota',
            'alamat'        => 'required|string',
            'status'        => 'required|in:aktif,non-aktif',
        ]);

        $anggota = Anggota::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data anggota berhasil ditambahkan',
            'data'    => $anggota
        ], 201);
    }

    public function show($id)
    {
        $anggota = Anggota::with('user')->find($id);

        if (!$anggota) {
            return response()->json([
                'success' => false,
                'message' => 'Data anggota tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail data anggota',
            'data'    => $anggota
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $anggota = Anggota::find($id);

        if (!$anggota) {
            return response()->json([
                'success' => false,
                'message' => 'Data anggota tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'user_id'       => 'required|exists:users,id|unique:anggotas,user_id,' . $id,
            'nomor_anggota' => 'required|string|unique:anggotas,nomor_anggota,' . $id,
            'alamat'        => 'required|string',
            'status'        => 'required|in:aktif,non-aktif',
        ]);

        $anggota->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data anggota berhasil diperbarui',
            'data'    => $anggota
        ], 200);
    }

    public function destroy($id)
    {
        $anggota = Anggota::find($id);

        if (!$anggota) {
            return response()->json([
                'success' => false,
                'message' => 'Data anggota tidak ditemukan'
            ], 404);
        }

        $anggota->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data anggota berhasil dihapus'
        ], 200);
    }
}