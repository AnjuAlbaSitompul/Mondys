<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MasterController extends Controller
{
    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // ================= VALIDATION =================
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:ADMIN,PIC,SPV,DRIVER,PICKER',
            'location_id' => 'nullable|exists:locations,id',
            'outlet_id' => 'nullable|exists:outlets,id',
        ]);

        // ================= LOGIC ROLE =================
        $locationId = null;
        $outletId = null;

        if (in_array($request->role, ['ADMIN', 'SPV'])) {
            $locationId = $request->location_id;
        }

        if ($request->role === 'PIC') {
            $outletId = $request->outlet_id;
        }

        // ================= UPDATE =================
        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'role' => $request->role,
            'location_id' => $locationId,
            'outlet_id' => $outletId,
        ];

        // update password kalau diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'User berhasil diupdate',
            'data' => $user
        ]);
    }

    public function userDelete($id)
    {
        $user = User::findOrFail($id);

        // 🔥 soft delete
        $user->update([
            'is_active' => 0
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User berhasil dinonaktifkan'
        ]);
    }

    public function userCreate(Request $request)
    {
        // ================= VALIDATION =================
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'role' => 'required|in:ADMIN,PIC,SPV,DRIVER,PICKER',
            'location_id' => 'nullable|exists:locations,id',
            'outlet_id' => 'nullable|exists:outlets,id',
        ]);

        // ================= LOGIC ROLE =================
        $locationId = null;
        $outletId = null;

        if ($request->role === 'PICKER') {
            $locationId = $request->location_id;
        }

        if ($request->role === 'PIC') {
            $outletId = $request->outlet_id;
        }

        // ================= CREATE USER =================
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'location_id' => $locationId,
            'outlet_id' => $outletId,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User berhasil dibuat',
            'data' => $user
        ]);
    }


    public function index()
    {
        $locations = Location::pluck('code', 'id')->toArray();
        $outlets = Outlet::pluck('codeOutlet', 'id')->toArray();

        return view('pages.master-user', ['locations' => $locations, 'outlets' => $outlets]);
    }

    public function userItems()
    {
        $users = User::with(['location', 'outlet'])
            ->where('id', '!=', Auth::id())
            ->where('is_active', 1) // 🔥 hanya yang aktif
            ->get();

        return response()->json([
            'data' => $users,
            'status' => 'success',
        ]);
    }
}
