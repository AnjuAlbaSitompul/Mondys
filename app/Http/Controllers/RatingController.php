<?php

namespace App\Http\Controllers;

use App\Models\DriverRating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        DriverRating::create([
            'driver_id' => $id,
            'rating' => $request->rating,
            'feedback' => $request->comment
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Rating berhasil disimpan'
        ]);
    }
}
