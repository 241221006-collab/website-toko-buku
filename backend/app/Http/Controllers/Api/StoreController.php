<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seller;

class StoreController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data'   => Seller::withCount('books')->latest()->get(),
        ]);
    }

    public function show($id)
    {
        $seller = Seller::withCount('books')->find($id);

        if (!$seller) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Toko tidak ditemukan',
            ], 404);
        }

        return response()->json(['status' => 'success', 'data' => $seller]);
    }
}