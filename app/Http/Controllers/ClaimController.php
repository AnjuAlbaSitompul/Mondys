<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ClaimController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'desc' => 'required|string|max:1000',
            'image' => 'required|image|max:2048'
        ]);
        $existing = Claim::where('barang_id', $request->barang_id)->first();

        if ($existing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barang sudah pernah di-claim'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // optional: cek barang ada
            $barang = Barang::findOrFail($request->barang_id);

            // create claim
            $claim = Claim::create([
                'barang_id' => $barang->id,
                'claimed_by' => Auth::id(),
                'desc' => $request->desc
            ]);

            if ($request->hasFile('image')) {
                $image = $request->file('image');

                Http::attach(
                    'photo',
                    file_get_contents($image->getRealPath()),
                    $image->getClientOriginalName()
                )->post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendPhoto", [
                    'chat_id' => env('TELEGRAM_CHAT_ID'),
                    'caption' => "📦 Claim Barang\n\nNo SJ: {$barang->sjcode}\nUser: " . Auth::user()->name . "\nDeskripsi: {$request->desc}"
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Claim berhasil dibuat',
                'data' => $claim
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
