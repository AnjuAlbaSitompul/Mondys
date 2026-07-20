<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function index()
    {
        return view('pages.master-outlet');
    }

    public function items()
    {
        $outlet = Outlet::all();

        return response()->json([
            'data' => $outlet
        ]);
    }

    public function store(Request $request)
    {
        $request->merge([
            'codeOutlet' => strtoupper(str_replace(' ', '', (string) $request->input('codeOutlet'))),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'codeOutlet' => 'required|string|max:100|regex:/^\S+$/|unique:outlets,codeOutlet',
            'alamat' => 'nullable|string',
        ]);


        $outlet = Outlet::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Outlet berhasil dibuat',
            'data' => $outlet
        ], 201);
    }


    public function update(Request $request, $id)
    {
        $request->merge([
            'codeOutlet' => str_replace(' ', '', (string) $request->input('codeOutlet')),
        ]);

        $outlet = Outlet::find($id);

        if (!$outlet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Outlet tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'codeOutlet' => 'required|string|max:100|regex:/^\S+$/|unique:outlets,codeOutlet,' . $id,
            'alamat' => 'nullable|string',
        ]);


        $outlet->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Outlet berhasil diupdate',
            'data' => $outlet
        ]);
    }

    public function destroy($id)
    {
        $outlet = Outlet::find($id);

        if (!$outlet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Outlet tidak ditemukan'
            ], 404);
        }

        $outlet->update(['is_active' => 0]);

        return response()->json([
            'status' => 'success',
            'message' => 'Outlet berhasil dihapus'
        ]);
    }

    public function activate($id)
    {
        $outlet = Outlet::find($id);

        if (!$outlet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Outlet tidak ditemukan'
            ], 404);
        }

        $outlet->update(['is_active' => 1]);

        return response()->json([
            'status' => 'success',
            'message' => 'Outlet berhasil diaktifkan'
        ]);
    }
}
