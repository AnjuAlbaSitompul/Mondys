<?php

namespace App\Http\Controllers;

use App\Models\PickList;
use Illuminate\Http\Request;

class PickController extends Controller
{
    public function index()
    {
        return view('pages.pick');
    }

    public function getAll()
    {
        $picklist = PickList::with(['barang', 'picker'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $picklist,
            'message' => 'Data Pick Lists Berhasil Diambil'
        ]);
    }
}
