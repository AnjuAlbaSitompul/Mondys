<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BoardingList;
use App\Models\Loading;
use App\Models\LoadingDetail;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoadingContoroller extends Controller
{

    public function checkId($id)
    {
        $exists = Loading::where('surat_jalan', $id)
            ->where('driver_id', Auth::id())
            ->whereNull('loading_end')
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data ditemukan'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data tidak ditemukan'
        ], 404);
    }
    public function history()
    {
        $loading = Loading::with(['driver', 'coDriver', 'outlet'])
            ->whereNotNull('loading_end')
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $loading,
            'message' => 'Berhasil'
        ]);
    }
    public function printById($id)
    {
        $loading = Loading::with([
            'details.boardingList.barang.jenisBarang',
            'driver',
            'coDriver',
            'creator.location',
            'outlet',
        ])->findOrFail($id);

        // Pisahin data
        $titipItems = $loading->details
            ->filter(
                fn($d) =>
                $d->boardingList?->barang?->type === 'TITIP'
            )
            ->sortBy(
                fn($d) =>
                $d->boardingList?->barang?->sjcode ?? ''
            );

        $regularItems = $loading->details
            ->filter(
                fn($d) =>
                $d->boardingList?->barang?->type === 'REGULER'
            )
            ->sortBy(
                fn($d) =>
                $d->boardingList?->barang?->sjcode ?? ''
            );

        // Rekap jenis barang (TITIP saja)
        $rekapJenis = $titipItems
            ->groupBy(
                fn($d) =>
                $d->boardingList?->barang?->jenisBarang?->name ?? 'LAINNYA'
            )
            ->map(fn($items) => $items->sum('koli'));

        $rekapJenis = $titipItems
            ->groupBy(
                fn($d) =>
                $d->boardingList?->barang?->jenisBarang?->name ?? 'LAINNYA'
            )
            ->map(fn($items) => $items->sum('koli'));

        // TOTAL QTY SEMUA TITIP
        $totalQtyTitip = $titipItems->sum('koli');

        // TOTAL BOX (dari REGULER)
        $totalBox = $regularItems->sum('box');

        // Tambahkan "BOX" ke rekap jenis
        $rekapJenis = $rekapJenis->put('BOX', $totalBox);

        // GRAND TOTAL (kalau mau dipakai di view)
        $grandTotal = $totalQtyTitip + $totalBox;

        return view('print.print-loading', compact(
            'loading',
            'titipItems',
            'regularItems',
            'rekapJenis'
        ));
    }
    public function updateLoading(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:boarding_lists,id',
            'items.*.koli' => 'required|integer|min:0',
            'items.*.qty' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            $loading = Loading::findOrFail($id);

            // 🔹 ambil detail lama
            $oldDetails = LoadingDetail::where('loading_id', $loading->id)->get();

            // index by boarding_list_id
            $oldMap = $oldDetails->keyBy('boarding_list_id');

            foreach ($request->items as $item) {

                $boarding = BoardingList::lockForUpdate()->findOrFail($item['id']);

                $newKoli = (int) $item['koli'];
                $newBox = (int) ($item['qty'] ?? 0);

                $old = $oldMap[$item['id']] ?? null;

                $oldKoli = $old?->koli ?? 0;
                $oldBox = $old?->box ?? 0;

                // 🔥 VALIDASI (pakai stok + data lama)
                $availableKoli = $boarding->koli + $oldKoli;
                $availableBox = ($boarding->qty ?? 0) + $oldBox;

                if ($newKoli > $availableKoli) {
                    throw new \Exception("Koli melebihi stok item ");
                }

                if ($newBox > $availableBox) {
                    throw new \Exception("Box melebihi stok item ");
                }

                // 🔹 update boarding (balikin dulu stok lama, lalu kurangi baru)
                $boarding->koli = $availableKoli - $newKoli;
                $boarding->qty = $availableBox - $newBox;

                if ($boarding->koli == 0 && $boarding->qty == 0) {
                    $boarding->boarding_end = now();
                } else {
                    $boarding->boarding_end = null;
                }

                $boarding->save();

                // 🔹 update / create detail
                if ($old) {
                    $old->update([
                        'koli' => $newKoli,
                        'box' => $newBox,
                    ]);
                } else {
                    LoadingDetail::create([
                        'loading_id' => $loading->id,
                        'boarding_list_id' => $boarding->id,
                        'koli' => $newKoli,
                        'barang_id' => $boarding->barang_id,
                        'box' => $newBox,
                    ]);
                }
            }

            // 🔥 OPTIONAL: hapus item yang tidak ada di input
            $inputIds = collect($request->items)->pluck('id')->toArray();

            foreach ($oldDetails as $old) {
                if (!in_array($old->boarding_list_id, $inputIds)) {

                    $boarding = BoardingList::find($old->boarding_list_id);

                    if ($boarding) {
                        // balikin stok
                        $boarding->koli += $old->koli;
                        $boarding->qty += $old->box;
                        $boarding->boarding_end = null;
                        $boarding->save();
                    }

                    $old->delete();
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Loading berhasil diupdate'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function loadingDetail($id)
    {
        // 🔹 1. ambil loading + detail
        $loading = Loading::with([
            'details.boardingList.barang.jenisBarang',
            'details.boardingList.outlet',
            'details.boardingList',
            'driver',
            'coDriver'
        ])
            ->findOrFail($id);

        // ambil outlet dari loading
        $outletId = $loading->outlet_id;

        // ambil semua boarding_list_id yang sudah masuk loading ini
        $loadedIds = $loading->details->pluck('boarding_list_id')->toArray();

        // 🔹 base query (biar gak duplikat logic)
        $baseQuery = BoardingList::with(['barang.jenisBarang', 'outlet'])
            ->where('outlet_id', $outletId)
            ->whereNull('boarding_end')
            ->whereNotIn('id', $loadedIds)
            ->whereHas('barang', function ($q) {
                $q->whereIn('type', ['TITIP', 'REGULER']);
            });

        // 🔹 TITIP (FIFO)
        $titip = (clone $baseQuery)
            ->whereHas('barang', fn($q) => $q->where('type', 'TITIP'))
            ->orderBy('boarding_start', 'asc') // FIFO
            ->get();

        // 🔹 REGULER (FIFO)
        $reguler = (clone $baseQuery)
            ->whereHas('barang', fn($q) => $q->where('type', 'REGULER'))
            ->orderBy('boarding_start', 'asc') // FIFO
            ->get();

        return response()->json([
            'loading' => $loading,
            'loadedItems' => $loading->details,
            'availableBoarding' => [
                'titip' => $titip,
                'reguler' => $reguler,
            ]
        ]);
    }
    private function generateSjCode()
    {
        $prefix = 'HS' . now()->format('ym'); // HS + YYMM

        // ambil terakhir di bulan yang sama
        $last = Loading::where('surat_jalan', 'like', $prefix . '%')
            ->lockForUpdate()
            ->orderBy('surat_jalan', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->surat_jalan, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $running = str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return $prefix . $running;
    }

    public function loading(Request $request)
    {
        $request->validate([
            'driverId' => 'required|exists:users,id',
            'coDriverId' => 'nullable|exists:users,id',
            'outletId' => 'required|exists:outlets,id',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:boarding_lists,id',
            'items.*.koli' => 'required|integer|min:0',
            'items.*.qty' => 'nullable|integer|min:0',
            // 'sjCode' => 'required|string|unique:loadings,surat_jalan',
        ]);

        DB::beginTransaction();

        try {
            // 🔹 1. create loading (header)
            $loading = Loading::create([
                'surat_jalan' => $this->generateSjCode(),
                'driver_id' => $request->driverId,
                'co_driver_id' => $request->coDriverId,
                'outlet_id' => $request->outletId,
                'loading_start' => now(),
                'created_by' => Auth::id() ?? 1,
            ]);

            // 🔹 2. loop items → insert ke loading_details
            foreach ($request->items as $item) {

                $boarding = BoardingList::lockForUpdate()->findOrFail($item['id']);

                // optional: validasi agar tidak melebihi stok
                if ($item['koli'] > $boarding->koli) {
                    throw new \Exception("Koli melebihi stok untuk item ID {$item['id']}");
                }

                if (($item['qty'] ?? 0) > ($boarding->qty ?? 0)) {
                    throw new \Exception("Box melebihi stok untuk item ID {$item['id']}");
                }

                // 🔹 insert detail
                LoadingDetail::create([
                    'loading_id' => $loading->id,
                    'barang_id' => $boarding->barang_id,
                    'boarding_list_id' => $boarding->id,
                    'koli' => $item['koli'],
                    'box' => $item['qty'] ?? 0,
                ]);

                // 🔹 update boarding (kurangi stok)
                $boarding->koli -= $item['koli'];
                $boarding->qty -= ($item['qty'] ?? 0);

                // optional: tandai sudah loading
                if ($boarding->koli == 0 && $boarding->qty == 0) {
                    $boarding->boarding_end = now();
                }

                $boarding->save();
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Loading berhasil dibuat'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function index()
    {
        $outlets = Outlet::pluck('name', 'id')->toArray();
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
