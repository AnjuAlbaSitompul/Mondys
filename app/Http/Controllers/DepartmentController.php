<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('pages.master-department');
    }
    public function items()
    {
        $locations = Location::where('is_active', '1')->get();

        return response()->json([
            'data' => $locations
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:locations,code',
            'name' => 'required',
            'address' => 'nullable'
        ]);

        $location = Location::create($request->all());

        return response()->json([
            'message' => 'Location berhasil dibuat',
            'data' => $location
        ]);
    }
    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:locations,code,' . $id,
            'name' => 'required',
        ]);

        $location->update($request->all());

        return response()->json([
            'message' => 'Location berhasil diupdate'
        ]);
    }
    public function delete($id)
    {
        $location = Location::findOrFail($id);

        $location->update(['is_active' => 0]);

        return response()->json([
            'message' => 'Location berhasil dihapus'
        ]);
    }
}
