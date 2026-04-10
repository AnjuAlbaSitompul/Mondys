<?php

namespace App\Http\Controllers;

use App\Models\Delivering;
use App\Models\Loading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DeliverController extends Controller
{

    public function sendPhoto(Request $request)
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
                'loading_end' => now()
            ]);

            // create delivering
            $delivering = Delivering::create([
                'loading_id' => $loading->id,
                'start_at' => now(),
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
