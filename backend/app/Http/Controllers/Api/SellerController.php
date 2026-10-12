<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function register(Request $request)
    {
        $user = $request->user();

        // Alternative flow: sudah punya toko
        if ($user->seller) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Anda sudah memiliki toko',
                'data'    => $user->seller,
            ]);
        }

        $data = $request->validate([
            'name' => 'required|string|max:100',
        ], [
            'name.required' => 'Nama toko wajib diisi',
        ]);

        $seller = Seller::create([
            'user_id' => $user->id,
            'name'    => $data['name'],
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Berhasil terdaftar sebagai seller',
            'data'    => $seller,
        ], 201);
    }
}