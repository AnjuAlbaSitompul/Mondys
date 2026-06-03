<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BoardingList;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BoardingListController extends Controller
{

    public function index()
    {
        $outlets = Outlet::pluck('codeOutlet', 'id')->toArray();
        $jenisBarangs = DB::table('jenis_barangs')->pluck('name', 'id')->toArray();
        return view('pages.boarding', [
            'outlets' => $outlets,
            'jenisBarangs' => $jenisBarangs
        ]);
    }
    public function store(Request $request)
    {
        $codes = json_decode($request->codeBoarding, true);

        $request->merge([
            'codeBoarding' => $codes
        ]);

        $validator = Validator::make($request->all(), [
            'codeBoarding' => 'required|array|min:1',
            'codeBoarding.*.value' => 'required|string|size:22',
            'qty' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Silahkan isi data dengan benar',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {

            $codesCollection = collect($request->codeBoarding);
            $qty = (int) $request->qty;

            // =====================
            // PARSING
            // =====================
            $parsed = $codesCollection->map(function ($item) {
                $code = $item['value'];

                return [
                    'full' => $code,
                    'outlet_code' => substr($code, 0, 4),
                    'sj_barang' => substr($code, 4, 16),
                    'koli' => substr($code, -2),
                ];
            });

            // =====================
            // VALIDASI OUTLET
            // =====================
            $outletCodes = $parsed->pluck('outlet_code')->unique();

            $outlets = Outlet::whereIn('codeOutlet', $outletCodes)
                ->get()
                ->keyBy('codeOutlet');

            if ($outlets->count() !== $outletCodes->count()) {
                throw new \Exception(
                    "Outlet tidak valid: " .
                    implode(', ', $outletCodes->diff($outlets->keys())->toArray())
                );
            }

            // =====================
            // VALIDASI BARANG
            // =====================
            $sjBarangs = $parsed->pluck('sj_barang')->unique();

            $barangs = Barang::whereIn('sjcode', $sjBarangs)
                ->get()
                ->keyBy('sjcode');

            if ($barangs->count() !== $sjBarangs->count()) {
                throw new \Exception(
                    "Barang tidak ditemukan dari SJ: " .
                    implode(', ', $sjBarangs->diff($barangs->keys())->toArray())
                );
            }

            if ($barangs->where('status', '!=', 'PICK END')->isNotEmpty()) {
                throw new \Exception(
                    "Ada barang yang belum selesai pick: " .
                    implode(', ', $barangs->where('status', '!=', 'PICK END')->pluck('sjcode')->toArray()) . ' Harap Perhatikan Pekerjaan Anda'
                );
            }

            // =====================
            // CEK DUPLIKAT
            // =====================
            $existing = BoardingList::whereIn(
                'code_boarding',
                $parsed->pluck('full')
            )->pluck('code_boarding');

            if ($existing->isNotEmpty()) {
                throw new \Exception(
                    "Code sudah pernah diinput: " .
                    implode(', ', $existing->toArray())
                );
            }

            // =====================
            // PREPARE INSERT
            // =====================
            $insertData = $parsed->map(function ($item) use ($outlets, $barangs, $qty) {
                return [
                    'barang_id' => $barangs[$item['sj_barang']]->id,
                    'outlet_id' => $outlets[$item['outlet_code']]->id,
                    'code_boarding' => $item['full'],
                    'qty' => $qty, // 🔥 dari input, bukan dari code
                    'koli' => $item['koli'], // 🔥 dari code
                    'created_at' => now(),
                    'boarding_start' => now(),
                    'created_by' => Auth::id(),
                    'updated_at' => now(),
                ];
            })->toArray();

            BoardingList::insert($insertData);

            // 🔥 Update status barang
            Barang::whereIn('sjcode', $sjBarangs)
                ->update(['status' => 'BOARDING']);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Boarding list berhasil ditambahkan',
                'total' => count($insertData),
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $th->getMessage() ?: 'Terjadi kesalahan server',
            ], 500);
        }
    }

    public function getAll()
    {
        $data = BoardingList::with(['barang', 'outlet', 'creator'])
            ->whereNull('boarding_end')
            ->whereHas('barang', function ($q) {
                $q->where('type', 'REGULER');
            })
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil mengambil data',
            'data' => $data->map(function ($item) {
                return [
                    'id' => $item->id,
                    'outlet' => $item->outlet->name ?? null,
                    'qty' => $item->qty,
                    'code' => $item->code_boarding,
                    'status' => $item->barang->status ?? null,
                    'type' => $item->barang->type ?? null,
                    'started_at' => $item->boarding_start,
                    'ended_at' => $item->boarding_end,
                    'created_by' => $item->creator->name ?? null,

                ];
            })
        ]);
    }
}
