<?php

namespace App\Http\Controllers;

use App\Models\Delivering;
use App\Models\Loading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DeliverController extends Controller
{
    public function picDetail($id)
    {
        $delivering = Delivering::with([
            'loading.details.boardingList.barang'
        ])->findOrFail($id);

        $details = $delivering->loading->details;

        return response()->json([
            'data' => $details
        ]);
    }
    public function camera($id)
    {
        $delivering = Delivering::find($id);

        // ❌ 1. tidak ditemukan
        if (!$delivering) {
            return redirect()->back()->with('error', 'Delivering tidak ditemukan');
        }

        // ❌ 2. bukan owner/driver
        if ($delivering->driver_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Tidak memiliki akses');
        }

        // ❌ 3. belum mulai
        if ($delivering->start_at === null) {
            return redirect()->back()->with('error', 'Delivery belum dimulai');
        }

        // ❌ 4. sudah selesai
        if ($delivering->clock_out !== null) {
            return redirect()->back()->with('error', 'Delivery sudah selesai');
        }

        // ✅ semua valid
        return view('camera.index', compact('delivering'));
    }
    public function clockOut($id)
    {
        DB::beginTransaction();

        try {
            $delivering = Delivering::with('loading.details.boardingList.barang')
                ->findOrFail($id);

            // ❌ belum clock in
            if (!$delivering->clock_in) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Belum melakukan clock in'
                ], 400);
            }

            // ❌ cegah double clock out
            if ($delivering->clock_out) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sudah melakukan clock out'
                ], 400);
            }

            // ✅ update clock out
            $delivering->update([
                'clock_out' => now()
            ]);

            // ✅ update semua barang jadi FINISHED
            $loading = $delivering->loading;

            if ($loading) {
                foreach ($loading->details as $detail) {
                    $boarding = $detail->boardingList;

                    if ($boarding && $boarding->barang) {
                        $boarding->barang->update([
                            'status' => 'FINISHED'
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Clock out berhasil',
                'data' => $delivering,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function clockIn(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required|image'
        ]);

        $delivering = Delivering::with('loading')->findOrFail($id);

        // optional: validasi basic
        if (!$delivering) {
            return response()->json([
                'status' => 'error',
                'message' => 'Delivering tidak ditemukan'
            ], 404);
        }

        $token = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        $file = $request->file('photo');

        // 🚀 kirim ke telegram
        $response = Http::attach(
            'photo',
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        )->post("https://api.telegram.org/bot{$token}/sendPhoto", [
            'chat_id' => $chatId,
            'caption' => 'Driver ID: ' . $delivering->loading->surat_jalan . ' sudah clock in'
        ]);

        $result = $response->json();

        // ✅ kalau berhasil dari Telegram
        if ($response->successful() && isset($result['ok']) && $result['ok'] === true) {

            $delivering->update([
                'clock_in' => now()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Clock in berhasil'
            ]);
        }

        // ❌ kalau gagal kirim telegram
        return response()->json([
            'status' => 'error',
            'message' => 'Gagal kirim ke Telegram'
        ], 500);
    }
    public function create(Request $request)
    {
        DB::beginTransaction();

        try {
            $loading = Loading::where('surat_jalan', $request->id)
                ->with('details.boardingList.barang')
                ->whereNull('loading_end')
                ->firstOrFail();

            // if (!$loading) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Loading tidak ditemukan'
            //     ], 404);
            // }


            foreach ($loading->details as $detail) {
                $boarding = $detail->boardingList;

                if ($boarding && $boarding->barang) {
                    $boarding->barang->update([
                        'status' => 'DEPARTURE'
                    ]);
                }
            }
            $loading->update([
                'loading_end' => now()
            ]);

            // create delivering
            $delivering = Delivering::create([
                'loading_id' => $loading->id,
                'start_at' => now(),
                'driver_id' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $delivering
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
