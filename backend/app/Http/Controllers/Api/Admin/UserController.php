<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('seller:id,user_id,name')
            ->where('role', 'user')
            ->latest()
            ->get();

        return response()->json(['status' => 'success', 'data' => $users]);
    }

    public function update(Request $request, $id)
    {
        $user = User::where('role', 'user')->find($id);

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Akun tidak ditemukan'], 404);
        }

        $data = $request->validate(['status' => 'required|in:active,inactive']);

        $user->update($data);

        if ($data['status'] === 'inactive') {
            $user->tokens()->delete();
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Data pengguna berhasil diperbarui',
            'data'    => $user->load('seller:id,user_id,name'),
        ]);
    }

    public function destroy($id)
    {
        $user = User::where('role', 'user')->find($id);

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Akun tidak ditemukan'], 404);
        }

        try {
            $user->delete();
        } catch (QueryException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akun tidak bisa dihapus karena memiliki riwayat transaksi. Nonaktifkan saja.',
            ], 409);
        }

        return response()->json(['status' => 'success', 'message' => 'Akun berhasil dihapus']);
    }
}