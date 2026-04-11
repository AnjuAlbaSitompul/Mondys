<?php

namespace App\Http\Controllers;

use App\Models\JenisBarang;
use Illuminate\Http\Request;

class JenisController extends Controller
{
    public function index()
    {
        return view('pages.master-jenis');
    }
    public function items()
    {
        $outlet = JenisBarang::where('is_active', '1')->get();

        return response()->json([
            'data' => $outlet
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $outlet = JenisBarang::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Jenis Barang Berhasil Di Tambahkan',
            'data' => $outlet
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $outlet = JenisBarang::find($id);

        if (!$outlet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Outlet tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);


        $outlet->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Jenis Barang berhasil diupdate',
            'data' => $outlet
        ]);
    }

    public function destroy($id)
    {
        $outlet = JenisBarang::find($id);

        if (!$outlet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Outlet tidak ditemukan'
            ], 404);
        }

        $outlet->update(['is_active' => 0]);

        return response()->json([
            'status' => 'success',
            'message' => 'Jenis Barang berhasil dihapus'
        ]);
    }
}
