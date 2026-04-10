<?php

namespace App\Http\Controllers;

use App\Models\Delivering;
use App\Models\Loading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliverController extends Controller
{
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
