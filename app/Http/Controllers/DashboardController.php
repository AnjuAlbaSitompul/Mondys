<?php

namespace App\Http\Controllers;

use App\Models\Loading;
use App\Models\Outlet;
use App\Models\PickList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function printBarcode(Request $request)
    {
        $id = $request->id;
        $qty = (int) $request->qty;

        // contoh format barcode: ID|QTY
        $barcodeValue = $id . '|' . $qty;

        // generate sebanyak qty
        $barcodes = array_fill(0, $qty, $barcodeValue);

        return view('print.print-barcode', compact('barcodes', 'id', 'qty'));
    }

    public function index()
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
                // contoh driver
                $data['totalDelivery'] = Loading::where('driver_id', $user->id)->count();
                break;

            case 'SPV':
                // contoh supervisor
                $data['totalPicker'] = User::where('role', 'PICKER')->count();
                break;

            case 'ADMIN':
                // admin full akses
                $data['totalUser'] = User::count();
                $data['totalOutlet'] = Outlet::count();
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
}
