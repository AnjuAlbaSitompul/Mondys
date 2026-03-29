<?php

namespace App\Http\Controllers;

use App\Models\PickList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PickController extends Controller
{
    public function index()
    {
        return view('pages.pick');
    }

    public function getAll()
    {
        $picklist = PickList::with(['barang', 'picker'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $picklist,
            'message' => 'Data Pick Lists Berhasil Diambil'
        ]);
    }


    public function endPick($id)
    {
        DB::beginTransaction();

        try {

            $pickList = PickList::find($id);

            if (!$pickList) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pick List tidak ditemukan'
                ], 404);
            }

            if ($pickList->status === 'finished') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pick List sudah selesai'
                ], 400);
            }

            // update picklist
            $pickList->update([
                'status' => 'finished',
                'finished_at' => now()
            ]);

            if ($pickList->barang) {
                $pickList->barang->update([
                    'status' => 'PICK END'
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pick List berhasil diakhiri'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage() ?: 'Terjadi kesalahan server'
            ], 500);
        }
    }
}
