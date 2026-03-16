<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Outlet;
use App\Models\PickList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index()
    {
        $outlets = Outlet::pluck('name', 'codeOutlet')->toArray();

        return view('pages.barang', [
            'outlets' => $outlets
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:sj,titip',
            'codesj' => 'required_if:type,sj',
            'pickerId' => 'required_if:type,sj',
            'outletId' => 'required_if:type,titip',
            'qty' => 'required|numeric|min:1',
            'desc' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Silahkan isi data dengan benar',
                'data' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        try {

            $result = DB::transaction(function () use ($validated) {

                $barang = Barang::create([
                    'status' => 'PICKED',
                    'type' => strtoupper($validated['type']),
                    'sjcode' => $validated['type'] === 'sj'
                        ? $validated['codesj']
                        : null,
                    'id_outlet' => $validated['type'] === 'titip'
                        ? $validated['outletId']
                        : null,
                    'boxqty' => $validated['qty'],
                    'desc' => $validated['desc'] ?? null,
                ]);

                if ($validated['type'] === 'sj') {
                    PickList::create([
                        'barang_id' => $barang->id,
                        'picker_id' => $validated['pickerId'],
                        'started_at' => now(),
                    ]);
                }

                return $barang;
            });

            return response()->json([
                'status' => true,
                'message' => 'Barang berhasil ditambahkan',
                'data' => $result
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan server',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function getAll()
    {
        $barangs = Barang::with('outlet')->get();

        return response()->json([
            'status' => 'success',
            'data' => $barangs
        ]);
    }
}
