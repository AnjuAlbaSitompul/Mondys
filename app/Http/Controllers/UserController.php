<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getPicker()
    {
        $pickers = User::where('role', 'PICKER')
            ->where('is_active', 1)
            ->withCount([
                'pickLists as picking_today' => function ($q) {
                    $q->whereDate('started_at', today())
                        ->where('status', 'picking');
                }
            ])
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $pickers,
            'message' => 'List of pickers retrieved successfully'
        ]);
    }
}
