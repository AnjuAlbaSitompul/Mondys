<?php

namespace App\Http\Controllers;

use App\Models\PickList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PickController extends Controller
{
    public function index()
    {
        return view('pages.pick');
    }

    public function getAll()
    {
        $picklist = PickList::with(['barang', 'picker'])->where('status', '!=', 'finished')->get();

        return response()->json([
            'status' => 'success',
            'data' => $picklist,
            'message' => 'Data Pick Lists Berhasil Diambil'
        ]);
    }

    public function end(Request $request)
    {
        $codes = json_decode($request->codesj, true);
        $request->merge([
            'codesj' => $codes
        ]);

        $validator = Validator::make($request->all(), [
            'codesj' => 'required|array|min:1',
            'codesj.*.value' => 'required|string|min:4'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {

            $pickLists = PickList::where('status', '!=', 'finished')->get();

            foreach ($pickLists as $pickList) {
                $pickList->update([
                    'status' => 'finished',
                    'finished_at' => now(),
                    'ended_by' => Auth::id()
                ]);

                if ($pickList->barang) {
                    $pickList->barang->update([
                        'status' => 'PICK END'
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Semua Pick List berhasil diakhiri'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage() ?: 'Terjadi kesalahan server'
            ], 500);
        }
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
                'finished_at' => now(),
                'ended_by' => Auth::id(),
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
