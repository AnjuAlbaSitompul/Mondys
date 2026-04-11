<?php

namespace App\Http\Controllers;

use App\Models\BoardingList;
use App\Models\Delivering;
use App\Models\Loading;
use App\Models\Outlet;
use App\Models\PickList;
use App\Models\User;
use App\Services\DashboardAdminService;
use App\Services\PickerPerformanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function summary(Request $request, DashboardAdminService $service)
    {
        $data = $service->getDashboardData(
            $request->start_date,
            $request->end_date
        );

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }


    public function loadingData(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfDay();

        $data = Loading::with([
            'outlet',
            'driver',
            'coDriver',
            'details'
        ])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->map(function ($item) {

                $totalBox = $item->details->sum('box');
                $totalKoli = $item->details->sum('koli');

                return [
                    'surat_jalan' => $item->surat_jalan,
                    'outlet_name' => $item->outlet->name ?? '-',
                    'total_box' => $totalBox,
                    'total_koli' => $totalKoli,
                    'driver' => trim(
                        ($item->driver->name ?? '-') .
                            ($item->coDriver ? ' / ' . $item->coDriver->name : '')
                    ),
                    'loading_start' => $item->loading_start,
                    'loading_end' => $item->loading_end,
                ];
            });

        return response()->json([
            'data' => $data
        ]);
    }
    public function boardingData(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfDay();

        $data = BoardingList::with([
            'barang',
            'outlet',
            'creator'
        ])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->map(function ($item) {

                return [
                    'surat_jalan' => $item->barang->sjcode ?? '-',
                    'outlet_name' => $item->outlet->name ?? '-',
                    'box' => $item->qty ?? 0,
                    'koli' => $item->koli ?? 0,
                    'pic_boarding' => $item->creator->name ?? '-',
                    'started_at' => $item->boarding_start,
                    'finished_at' => $item->boarding_end,
                ];
            });

        return response()->json([
            'data' => $data
        ]);
    }

    public function pickingData(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfDay();

        $data = PickList::with([
            'barang',
            'picker',
            'creator',   // nanti kita tambah relasi
            'ender'      // nanti kita tambah relasi
        ])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->map(function ($item) {

                return [
                    'surat_jalan' => $item->barang->sjcode ?? '-',
                    'picker_name' => $item->picker->name ?? '-',
                    'pic_start'   => $item->creator->name ?? '-',
                    'started_at'  => $item->started_at,
                    'pic_end'     => $item->ender->name ?? '-',
                    'finished_at' => $item->finished_at,
                ];
            });

        return response()->json([
            'data' => $data
        ]);
    }

    public function pickerPerformance(Request $request, PickerPerformanceService $service)
    {
        try {
            $result = $service->getPickerStats(
                $request->start_date,
                $request->end_date
            );

            return response()->json([
                'status' => 'success',
                'data' => $result['data']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function picDashboard()
    {
        $user = Auth::user();

        $data = Delivering::with('loading') // eager load
            ->whereHas('loading', function ($q) use ($user) {
                $q->where('outlet_id', $user->outlet_id);
            })
            ->latest() // optional: urut terbaru
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function printBarcode(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'qty' => 'required|integer|min:1'
        ]);

        $id = $request->id;
        $qty = (int) $request->qty;

        // ========================
        // SPLIT DATA
        // ========================

        $codeOutlet = substr($id, 0, 4);     // 4 huruf awal
        $sjcode     = substr($id, 4);        // sisanya = sjcode asli

        // ========================
        // VALIDASI OUTLET
        // ========================

        $outlet = Outlet::where('codeOutlet', $codeOutlet)->first();
        if (!$outlet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode outlet tidak valid'
            ], 422);
        }

        // ========================
        // VALIDASI PICKLIST
        // ========================

        $picklist = Picklist::whereHas('barang', function ($q) use ($sjcode) {
            $q->where('sjcode', $sjcode);
        })->where('picker_id', Auth::id())->first();

        if (!$picklist) {
            return response()->json([
                'status' => 'error',
                'message' => 'SJ tidak ditemukan / bukan milik kamu'
            ], 403);
        }

        // ========================
        // GENERATE BARCODE
        // ========================
        $formattedQty = str_pad($qty, 2, '0', STR_PAD_LEFT);

        // gabungkan TANPA separator
        $barcodeFullValue = $id . $formattedQty; // full (prefix + sj + qty)
        $barcodeSjValue   = $sjcode;          // hanya SJ

        $barcodes = [];

        for ($i = 0; $i < $qty; $i++) {
            $barcodes[] = [
                'full' => $barcodeFullValue,
                'sj'   => $barcodeSjValue,
                'outlet' => $codeOutlet,
            ];
        }

        return view('print.print-barcode', compact('barcodes'));
    }

    public function index(Request $request, DashboardAdminService $service)
    {
        $user = Auth::user();
        $role = strtoupper($user->role);

        $data = [
            'user' => $user,
        ];

        switch ($role) {

            case 'PICKER':
                $data['finishedPick'] = PickList::where('picker_id', $user->id)
                    ->whereNotNull('finished_at')
                    ->count();

                $data['upComingTask'] = PickList::where('picker_id', $user->id)
                    ->whereNull('finished_at')
                    ->count();
                break;

            case 'DRIVER':

                $data['totalDelivery'] = Loading::where('driver_id', $user->id)->count();

                $data['isDelivering'] = Delivering::whereNull('clock_out')
                    ->whereNull('clock_in')
                    ->whereNotNull('start_at')
                    ->where('driver_id', $user->id)
                    ->with('loading')
                    ->first();

                $data['isClockingIn'] = Delivering::whereNull('clock_out')
                    ->whereNotNull('start_at')
                    ->whereNotNull('clock_in')
                    ->where('driver_id', $user->id)
                    ->exists();

                break;

            case 'PIC':

                $deliverings = Delivering::whereHas('loading', function ($q) use ($user) {
                    $q->where('outlet_id', $user->outlet_id);
                })
                    ->whereNull('clock_out')
                    ->get();

                $data['hasClockIn'] = $deliverings->filter(function ($item) {
                    return !is_null($item->clock_in);
                })->values();

                $data['totalDelivering'] = $deliverings->count();
                break;

            case 'ADMIN':

                $adminData = $service->getDashboardData(
                    $request->start_date,
                    $request->end_date
                );

                $data = array_merge($data, $adminData);
                break;
        }

        return view('pages.dashboard', $data);
    }
    public function pickerDashboard()
    {
        $data = Picklist::with(['barang', 'picker'])->whereNull('finished_at')->where('picker_id', Auth::id())->get();

        return response()->json([
            'data' => $data,
            'status' => 'success'
        ]);
    }

    public function driverDashboard()
    {
        $data = Loading::with(['outlet'])->whereNull('loading_end')->where('driver_id', Auth::id())->get();

        return response()->json([
            'data' => $data,
            'status' => 'success'
        ]);
    }
}
