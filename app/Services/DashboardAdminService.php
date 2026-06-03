<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\BoardingList;
use App\Models\LoadingDetail;
use App\Models\PickList;
use Carbon\Carbon;

class DashboardAdminService
{
    /**
     * Create a new class instance.
     */
    public function getDashboardData($startDate = null, $endDate = null)
    {
        $startDate = $startDate
            ? Carbon::parse($startDate)->startOfDay()
            : Carbon::now()->startOfHour();

        $endDate = $endDate
            ? Carbon::parse($endDate)->endOfDay()
            : Carbon::now()->endOfHour();

        // 🔹 1. total barang
        $totalBarang = Barang::whereBetween('created_at', [$startDate, $endDate])->count();

        // 🔹 2. barang sudah loading (loading_end NOT NULL)
        $barangLoaded = LoadingDetail::whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('loading', function ($q) {
                $q->whereNotNull('loading_end');
            })
            ->distinct('barang_id')
            ->count('barang_id');

        // 🔹 3. barang masih dipick (belum selesai)
        $barangPicking = PickList::whereBetween('created_at', [$startDate, $endDate])
            ->whereNull('finished_at')
            ->distinct('barang_id')
            ->count('barang_id');

        // 🔹 4. barang sudah boarding
        $barangBoarded = BoardingList::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('boarding_end')
            ->distinct('barang_id')
            ->count('barang_id');

        // 🔹 5. total picker unik
        $totalPicker = Picklist::whereBetween('created_at', [$startDate, $endDate])
            ->distinct('picker_id')
            ->count('picker_id');

        // 🔹 6. picking > 1 hari
        $slowPicking = Picklist::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('started_at')
            ->whereNotNull('finished_at')
            ->whereRaw('TIMESTAMPDIFF(DAY, started_at, finished_at) >= 1')
            ->count();

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,

            'total_barang' => $totalBarang,
            'barang_loaded' => $barangLoaded,
            'barang_picking' => $barangPicking,
            'barang_boarded' => $barangBoarded,

            'total_picker' => $totalPicker,
            'slow_picking' => $slowPicking,
        ];
    }
}
