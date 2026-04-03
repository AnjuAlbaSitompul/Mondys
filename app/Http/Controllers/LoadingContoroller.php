<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BoardingList;
use App\Models\Loading;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoadingContoroller extends Controller
{

    public function updateById(Request $request, $id)
    {
        $request->validate([
            'field' => 'required|string|in:koli,qty',
            'value' => 'required'
        ]);

        $Boarding = BoardingList::where('barang_id', $id)->first();

        if (!$Boarding) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barang tidak ditemukan Di Boarding List'
            ], 404);
        }

        $Boarding->{$request->field} = $request->value;
        $Boarding->updated_by = Auth::id();
        $Boarding->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data Boarding updated successfully'
        ]);
    }

    public function loading(Request $request)
    {
        $request->validate([
            'driverId' => 'required|exists:users,id',
            'coDriverId' => 'nullable|exists:users,id',
            'outletId' => 'required|exists:outlets,id',
            'sjIds' => 'required|array',
            'sjIds.*' => 'exists:boarding_lists,id',
            'sjCode' => 'required|string|unique:loadings,surat_jalan',
        ]);

        DB::beginTransaction();

        try {

            // 🔥 ambil sekali semua data penting
            $boardings = BoardingList::whereIn('id', $request->sjIds)
                ->where('outlet_id', $request->outletId)
                ->whereNull('boarding_end')
                ->get(['id', 'barang_id']);

            if ($boardings->count() !== count($request->sjIds)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ada data boarding tidak valid'
                ], 422);
            }

            // 🚚 create loading
            $loading = Loading::create([
                'surat_jalan' => $request->sjCode,
                'driver_id' => $request->driverId,
                'co_driver_id' => $request->coDriverId,
                'outlet_id' => $request->outletId,
                'created_by' => Auth::id() ?? 1,
                'loading_start' => now(),
            ]);

            // 📦 bulk insert detail (NO N+1)
            $details = $boardings->map(fn($b) => [
                'loading_id' => $loading->id,
                'barang_id' => $b->barang_id,
            ])->toArray();

            $loading->details()->createMany($details);

            // 🔥 ambil semua barang_id tanpa query ulang
            $barangIds = $boardings->pluck('barang_id');

            // update boarding
            BoardingList::whereIn('id', $boardings->pluck('id'))->update([
                'boarding_end' => now(),
            ]);

            // update barang
            Barang::whereIn('id', $barangIds)->update([
                'status' => 'LOADING',
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Loading berhasil dibuat',
                'data' => $loading->load('details')
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function index()
    {
        $outlets = Outlet::pluck('codeOutlet', 'id')->toArray();
        $drivers = User::where('role', 'driver')->pluck('name', 'id')->toArray();
        return view('pages.loading', [
            'outlets' => $outlets,
            'drivers' => $drivers
        ]);
    }

    public function getLoadingItems()
    {
        $loadings = Loading::with(['driver', 'coDriver', 'outlet'])->whereNull('loading_end')->get();
        return response()->json($loadings);
    }

    public function getLoadingItemsByOutlet($outletId)
    {
        $baseQuery = BoardingList::where('outlet_id', $outletId)
            ->whereNull('boarding_end')
            ->with(['barang.jenisBarang', 'creator', 'outlet'])
            ->whereHas('barang', function ($query) {
                $query->whereIn('type', ['TITIP', 'REGULER']);
            });

        $titip = (clone $baseQuery)
            ->whereHas('barang', fn($q) => $q->where('type', 'TITIP'))
            ->orderBy('boarding_start', 'asc')
            ->get();

        $reguler = (clone $baseQuery)
            ->whereHas('barang', fn($q) => $q->where('type', 'REGULER'))
            ->orderBy('boarding_start', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'titip' => $titip,
                'reguler' => $reguler,
            ]
        ]);
    }
}
