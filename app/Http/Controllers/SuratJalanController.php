<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Loading;
use App\Models\Delivering;
use App\Models\LoadingDetail;
use Carbon\Carbon;

class SuratJalanController extends Controller
{
    public function index()
    {
        return view('pages.surat-jalan');
    }

    /**
     * Mengambil Semua Data untuk DataTables
     */
    /**
     * Mengambil Semua Data untuk DataTables
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            // 1. Tangkap filter tanggal (Default ke hari ini jika kosong)
            $dateFrom = $request->input('date_from', Carbon::today()->toDateString());
            $dateTo = $request->input('date_to', Carbon::today()->toDateString());

            // 2. Format menjadi Start of Day dan End of Day agar presisi (00:00:00 - 23:59:59)
            $from = Carbon::parse($dateFrom)->startOfDay();
            $to = Carbon::parse($dateTo)->endOfDay();

            // 3. Ambil data Barang berdasarkan filter created_at
            // (Asumsi data masuk pertama kali dicatat di created_at tabel Barang)
            $barangs = Barang::with(['outlet', 'pickList', 'boarding'])
                ->whereBetween('created_at', [$from, $to])
                ->get()
                ->unique('sjcode');

            // --- [SISA KODE KE BAWAH TETAP SAMA] ---

            $boardingIds = $barangs->pluck('boarding.id')->filter()->toArray();

            $loadingDetails = LoadingDetail::with('loading.driver')
                ->whereIn('boarding_list_id', $boardingIds)
                ->get()
                ->keyBy('boarding_list_id');

            $loadingIds = $loadingDetails->pluck('loading_id')->filter()->unique()->toArray();

            $deliverings = Delivering::whereIn('loading_id', $loadingIds)->get()->keyBy('loading_id');

            $data = $barangs->map(function ($row, $index) use ($loadingDetails, $deliverings) {
                $boardingId = $row->boarding ? $row->boarding->id : null;
                $loadingDetail = $boardingId ? $loadingDetails->get($boardingId) : null;

                $loading = $loadingDetail ? $loadingDetail->loading : null;
                $delivering = $loading ? $deliverings->get($loading->id) : null;

                $statusHtml = '<span class="badge bg-secondary">Pesanan Baru</span>';

                if ($delivering) {
                    $statusHtml = $delivering->clock_out ? '<span class="badge bg-success">Selesai</span>' : '<span class="badge bg-primary">Delivering</span>';
                } elseif ($loading) {
                    $statusHtml = '<span class="badge bg-warning text-dark">Loading</span>';
                } elseif ($row->boarding) {
                    $statusHtml = '<span class="badge bg-info text-dark">Boarding</span>';
                } elseif ($row->pickList) {
                    $statusHtml = '<span class="badge bg-dark">Picking</span>';
                }

                return [
                    'DT_RowIndex' => $index + 1,
                    'surat_jalan' => $row->sjcode,
                    'outlet' => $row->outlet ? $row->outlet->name : '-',
                    'driver' => $loading && $loading->driver ? $loading->driver->name : '<span class="text-muted fst-italic">Belum sampai loading</span>',
                    'status' => $statusHtml,
                    'action' => '<button class="btn btn-sm btn-info text-white btn-detail" data-sj="' . $row->sjcode . '"><i class="bi bi-eye"></i> Detail Tracking</button>'
                ];
            })->values();

            return response()->json([
                'data' => $data
            ]);
        }
    }

    /**
     * Mengambil Detail Progress Tracking
     */
    public function getDetail(Request $request) // <-- Ubah parameter ini
    {
        // Ambil sjCode dari query string (?sjCode=...)
        $sjCode = $request->query('sjCode');

        if (!$sjCode) {
            return response()->json(['error' => 'Kode Surat Jalan tidak valid'], 400);
        }

        $barang = Barang::with(['pickList.picker', 'boarding'])->where('sjcode', $sjCode)->first();

        if (!$barang) {
            return response()->json(['error' => 'Data Barang tidak ditemukan'], 404);
        }
        $barang = Barang::with(['pickList.picker', 'boarding'])->where('sjcode', $sjCode)->first();

        if (!$barang) {
            return response()->json(['error' => 'Data Barang tidak ditemukan'], 404);
        }

        // 1. Picking
        $pickList = $barang->pickList;
        $pickStart = $pickList?->started_at;
        $pickEnd = $pickList?->finished_at;
        $pickDuration = ($pickStart && $pickEnd) ? Carbon::parse($pickStart)->diffForHumans(Carbon::parse($pickEnd), true) : 'Belum selesai / Belum ada';

        // 2. Boarding
        $boardingList = $barang->boarding;
        $boardStart = $boardingList?->boarding_start;
        $boardEnd = $boardingList?->boarding_end;
        $boardDuration = ($boardStart && $boardEnd) ? Carbon::parse($boardStart)->diffForHumans(Carbon::parse($boardEnd), true) : 'Belum selesai / Belum ada';
        $stockStatus = $boardingList ? "{$boardingList->qty} Qty / {$boardingList->koli} Koli" : 'Belum sampai boarding';

        // 3. Loading (Dicari melalui LoadingDetail yang berelasi dengan Boarding List)
        $loadingDetail = $boardingList ? LoadingDetail::with(['loading.driver', 'loading.coDriver'])->where('boarding_list_id', $boardingList->id)->first() : null;
        $loading = $loadingDetail ? $loadingDetail->loading : null;

        $loadStart = $loading?->loading_start;
        $loadEnd = $loading?->loading_end;
        $loadDuration = ($loadStart && $loadEnd) ? Carbon::parse($loadStart)->diffForHumans(Carbon::parse($loadEnd), true) : 'Belum selesai / Belum ada';

        // 4. Delivering
        $delivering = $loading ? Delivering::where('loading_id', $loading->id)->first() : null;
        $delStart = $delivering?->start_at;
        $delClockIn = $delivering?->clock_in;
        $delClockOut = $delivering?->clock_out;

        $delDuration = 'Belum sampai delivering';
        if ($delStart && $delClockOut) {
            $delDuration = Carbon::parse($delStart)->diffForHumans(Carbon::parse($delClockOut), true);
        } elseif ($delStart) {
            $delDuration = 'Sedang dalam perjalanan';
        }

        return response()->json([
            'sj_code' => $sjCode,
            'picking' => [
                'picker' => $pickList?->picker?->name ?? 'Belum sampai picking',
                'time' => $this->formatTime($pickStart, $pickEnd),
                'duration' => $pickDuration,
            ],
            'boarding' => [
                'time' => $this->formatTime($boardStart, $boardEnd),
                'duration' => $boardDuration,
                'stock' => $stockStatus,
            ],
            'loading' => [
                'id' => $loading?->id, // Kirimkan ID loading untuk URL Print
                'driver' => $loading?->driver?->name ?? 'Belum sampai loading',
                'co_driver' => $loading?->coDriver?->name ?? '-',
                'time' => $this->formatTime($loadStart, $loadEnd),
                'duration' => $loadDuration,
            ],
            'delivering' => [
                'start' => $delStart ? Carbon::parse($delStart)->format('H:i WIB') : 'Belum sampai delivering',
                'clock' => $this->formatTime($delClockIn, $delClockOut),
                'duration' => $delDuration,
            ],
            'claim' => 'Tidak ada record claim pada surat jalan ini.'
        ]);
    }

    private function formatTime($start, $end)
    {
        if (!$start && !$end)
            return '<span class="text-danger fst-italic">Belum sampai di tahap ini</span>';

        $startStr = $start ? Carbon::parse($start)->format('H:i') : '...';
        $endStr = $end ? Carbon::parse($end)->format('H:i') : '...';
        return "{$startStr} s/d {$endStr} WIB";
    }
}