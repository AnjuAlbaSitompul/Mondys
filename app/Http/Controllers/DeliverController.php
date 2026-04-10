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
    public function camera($id)
    {
        $delivering = Delivering::find($id);

        // ❌ 1. tidak ditemukan
        if (!$delivering) {
            return redirect()->back()->with('error', 'Delivering tidak ditemukan');
        }

        // ❌ 2. bukan owner/driver
        if ($delivering->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Tidak memiliki akses');
        }

        // ❌ 3. belum mulai
        if ($delivering->started_at === null) {
            return redirect()->back()->with('error', 'Delivery belum dimulai');
        }

        // ❌ 4. sudah selesai
        if ($delivering->clock_out !== null) {
            return redirect()->back()->with('error', 'Delivery sudah selesai');
        }

        // ✅ semua valid
        return view('camera.index', compact('delivering'));
    }
    public function clockIn(Request $request)
    {
        $request->validate([
            'photo' => 'required|image'
        ]);

        $token = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        $file = $request->file('photo');

        $response = Http::attach(
            'photo',
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        )->post("https://api.telegram.org/bot{$token}/sendPhoto", [
            'chat_id' => $chatId,
            'caption' => 'Upload dari user 💕'
        ]);

        return $response->json();
    }
    public function create(Request $request)
    {
        DB::beginTransaction();

        try {
            $loading = Loading::where('surat_jalan', $request->id)
                ->whereNull('loading_end')
                ->first();

            if (!$loading) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Loading tidak ditemukan'
                ], 404);
            }

            // update loading_end
            $loading->update([
                'loading_end' => now(),

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
