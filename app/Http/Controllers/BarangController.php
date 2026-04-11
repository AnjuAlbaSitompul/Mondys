<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Outlet;
use App\Models\PickList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{

    public function updatePicker(Request $request, $id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'pickerId' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Silahkan isi data dengan benar',
                'data' => $validator->errors()
            ], 422);
        }

        $picklist = $barang->picklist;

        if (!$picklist) {
            return response()->json([
                'status' => 'error',
                'message' => 'PickList untuk barang ini tidak ditemukan'
            ], 404);
        }

        $status = $picklist->status;
        if ($status === 'finished') {
            return response()->json([
                'status' => 'error',
                'message' => 'Barang sudah selesai dipick, tidak bisa diubah pickernya'
            ], 400);
        }



        $picklist->picker_id = $request->pickerId;
        $picklist->save();
        $barang->updated_by = Auth::id();
        $barang->updated_at = now();
        $barang->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Picker berhasil diperbarui'
        ]);
    }
    public function index()
    {
        $outlets = Outlet::pluck('name', 'codeOutlet')->toArray();

        return view('pages.barang', [
            'outlets' => $outlets
        ]);
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

        if ($barang->pickList->status === 'finished') {
            return response()->json([
                'status' => 'error',
                'message' => 'Barang sudah selesai dipick, tidak bisa dihapus'
            ], 400);
        }

        $barang->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Barang berhasil dihapus'
        ]);
    }

    public function create(Request $request)
    {
        $codes = json_decode($request->codesj, true);

        $request->merge([
            'codesj' => $codes
        ]);

        $validator = Validator::make($request->all(), [
            'pickerId' => 'required|exists:users,id',
            'codesj' => 'required|array|min:1',
            'codesj.*.value' => 'required|string|size:16'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Silahkan isi data dengan benar',
                'data' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {

            // // ambil semua outlet dari SJ
            // $outletCodes = collect($request->codesj)
            //     ->map(fn($item) => substr($item['value'], 0, 4))
            //     ->unique();

            // // cek ke DB sekali saja
            // $validOutlets = Outlet::whereIn('codeOutlet', $outletCodes)->pluck('codeOutlet');

            // if ($validOutlets->count() !== $outletCodes->count()) {
            //     throw new \Exception("SJ mengandung outlet yang tidak valid: " . implode(', ', $outletCodes->diff($validOutlets)->toArray()));
            // }


            $existing = Barang::whereIn(
                'sjcode',
                collect($request->codesj)->pluck('value')
            )->exists();

            if ($existing) {
                throw new \Exception("Ada SJ yang sudah pernah diinput");
            }

            $data = collect($request->codesj)->map(function ($item) {
                return [
                    'user_id'   => Auth::id(),
                    'sjcode'    => $item['value'],
                    'status'    => 'PICKED',
                    'type'    => 'REGULER',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            Barang::insert($data);
            $sjcodes = collect($request->codesj)->pluck('value');

            $barangs = Barang::whereIn('sjcode', $sjcodes)->get();
            $picklists = $barangs->map(function ($barang) use ($request) {
                return [
                    'barang_id'  => $barang->id,
                    'picker_id'  => $request->pickerId,
                    'status'     => 'picking',
                    'started_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => Auth::id(),
                ];
            })->toArray();

            PickList::insert($picklists);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Barang berhasil ditambahkan',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $th->getMessage() ?: 'Terjadi kesalahan server',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function getAll()
    {
        $barangs = Barang::where(['status' => 'PICKED', 'type' => 'REGULER'])->with(['picklist.picker'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $barangs
        ]);
    }
}
