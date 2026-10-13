<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data'   => Category::withCount('books')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(
            ['name' => 'required|string|max:100|unique:category,name'],
            [
                'name.unique'   => 'Nama kategori sudah ada',
                'name.required' => 'Nama kategori wajib diisi',
            ]
        );

        $category = Category::create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Kategori berhasil disimpan',
            'data'    => $category,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['status' => 'error', 'message' => 'Kategori tidak ditemukan'], 404);
        }

        $data = $request->validate(
            ['name' => 'required|string|max:100|unique:category,name,' . $id],
            [
                'name.unique'   => 'Nama kategori sudah ada',
                'name.required' => 'Nama kategori wajib diisi',
            ]
        );

        $category->update($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Kategori berhasil disimpan',
            'data'    => $category,
        ]);
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['status' => 'error', 'message' => 'Kategori tidak ditemukan'], 404);
        }

        if ($category->books()->exists()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Kategori masih digunakan oleh buku, tidak bisa dihapus',
            ], 409);
        }

        $category->delete();

        return response()->json(['status' => 'success', 'message' => 'Kategori berhasil dihapus']);
    }
}