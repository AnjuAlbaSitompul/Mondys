<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BoardingList;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveringController extends Controller
{

    public function update(Request $request, $id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'outletId' => 'required|exists:outlets,id',
            'koli' => 'required|integer|min:1',
            'jenisBarangId' => 'required|exists:jenis_barangs,id',
            'namaBarang' => 'required|string|max:255'
        ]);

        DB::beginTransaction();
        try {
            $barang->update([
                'outlet_id' => $request->outletId,
                'jenis_barang_id' => $request->jenisBarangId,
                'nama_barang' => $request->namaBarang,
                'updated_by' => Auth::id(),
                'updated_at' => now(),
            ]);

            if ($barang->boarding) {
                $barang->boarding()->update([
                    'koli' => $request->koli,
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $barang,
                'message' => 'Barang titipan berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupdate barang titipan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        try {
            $barang->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Barang berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus barang',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function index()
    {
        $outlets = Outlet::pluck('codeOutlet', 'id')->toArray();
        $jenisBarangs = DB::table('jenis_barangs')->pluck('name', 'id')->toArray();
        return view('pages.titip', [
            'outlets' => $outlets,
            'jenisBarangs' => $jenisBarangs
        ]);
    }
    public function getAll()
    {
        $items = BoardingList::with(['barang.jenisBarang', 'outlet'])->whereHas('barang', function ($query) {
            $query->where('type', 'TITIP');
        })->wherenull('boarding_end')->get();
        return response()->json([
            'status' => 'success',
            'data' => $items,
            'message' => 'Data Barang Berhasil Diambil'
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'outletId' => 'required|exists:outlets,id',
            'koli' => 'required|integer|min:1',
            'jenisBarangId' => 'required|exists:jenis_barangs,id',
            'namaBarang' => 'required|string|max:255'
        ]);

        DB::beginTransaction();
        try {
            $barang = Barang::create([
                'type' => 'TITIP',
                'outlet_id' => $request->outletId,
                'user_id' => Auth::id(),
                'status' => 'BOARDING',
                'jenis_barang_id' => $request->jenisBarangId,
                'created_at' => now(),
                'updated_at' => now(),
                'nama_barang' => $request->namaBarang,
            ]);

            $boarding = $barang->boarding()->create([
                'koli' => $request->koli,
                'created_by' => Auth::id(),
                'outlet_id' => $request->outletId,
                'boarding_start' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $boarding,
                'message' => 'Barang titipan berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan barang titipan' . $request->outletId,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
