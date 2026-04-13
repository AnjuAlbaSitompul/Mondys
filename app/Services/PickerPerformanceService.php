<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PickerPerformanceService
{
    /**
     * Create a new class instance.
     */
    public function getPickerStats($startDate = null, $endDate = null)
    {
        $startDate = $startDate
            ? Carbon::parse($startDate)->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $endDate
            ? Carbon::parse($endDate)->endOfDay()
            : Carbon::now()->endOfDay();

        $data = DB::table('users')
            ->leftJoin('locations', 'users.location_id', '=', 'locations.id') // ✅ join location
            ->leftJoin('pick_lists', function ($join) use ($startDate, $endDate) {
                $join->on('users.id', '=', 'pick_lists.picker_id')
                    ->whereBetween('pick_lists.created_at', [$startDate, $endDate]);
            })
            ->where('users.role', 'PICKER')
            ->where('users.is_active', 1)
            ->select(
                'users.id as picker_id',
                'users.name as picker_name',
                'locations.name as picker_department', // ✅ ini yang kamu mau

                DB::raw('COUNT(pick_lists.barang_id) as total_barang'),

                DB::raw('AVG(
            CASE 
                WHEN pick_lists.started_at IS NOT NULL 
                AND pick_lists.finished_at IS NOT NULL
                THEN TIMESTAMPDIFF(MINUTE, pick_lists.started_at, pick_lists.finished_at)
                ELSE NULL
            END
        ) as avg_duration'),

                DB::raw('SUM(
            CASE 
                WHEN pick_lists.started_at IS NOT NULL 
                AND pick_lists.finished_at IS NOT NULL
                AND TIMESTAMPDIFF(DAY, pick_lists.started_at, pick_lists.finished_at) >= 1 
                THEN 1 ELSE 0 
            END
        ) as total_error')
            )
            ->groupBy('users.id', 'users.name', 'locations.name') // ✅ jangan lupa ini
            ->get();

        // 🔥 scoring
        $data = $data->map(function ($item) {

            $avgMinutes = (float) ($item->avg_duration ?? 0);
            $total = (int) ($item->total_barang ?? 0);
            $error = (int) ($item->total_error ?? 0);

            $score = 100;

            if ($total === 0) {
                $score = 0; // atau 0 kalau mau lebih strict
            } else {

                // penalti durasi
                if ($avgMinutes > 120) {
                    $score -= min(30, ($avgMinutes - 120) * 0.2);
                }

                // penalti error
                $errorRate = ($error / $total) * 100;
                $score -= $errorRate;

                // 🔥 bonus kalau produktif (optional)
                if ($total > 50) {
                    $score += 5;
                }
            }

            return [
                'picker_id' => $item->picker_id,
                'picker_name' => $item->picker_name,
                'picker_department' => $item->picker_department, // ✅ kirim ke frontend
                'total_barang' => $total,
                'avg_duration_minutes' => round($avgMinutes, 2),
                'total_error' => $error,
                'performance_score' => max(0, round($score, 2)),
            ];
        });

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'data' => $data
        ];
    }
}
