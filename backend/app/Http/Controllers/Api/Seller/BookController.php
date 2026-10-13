<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    private function rules(): array
    {
        return [
            'category_id' => 'required|exists:category,id',
            'title'       => 'required|string|max:200',
            'author'      => 'required|string|max:100',
            'price'       => 'required|integer|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'cover_img'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    private function messages(): array
    {
        return [
            'required'        => ':attribute wajib diisi',
            'cover_img.image' => 'Format file harus berupa gambar',
            'cover_img.mimes' => 'Format file harus berupa gambar',
            'cover_img.max'   => 'Ukuran file melebihi batas (maks 2 MB)',
        ];
    }

    public function index(Request $request)
    {
        $books = $request->user()->seller->books()
            ->with('category:id,name')
            ->latest()
            ->get();

        return response()->json(['status' => 'success', 'data' => $books]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());

        if ($request->hasFile('cover_img')) {
            $data['cover_img'] = $request->file('cover_img')->store('books', 'public');
        }

        $book = $request->user()->seller->books()->create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data produk buku berhasil ditambahkan',
            'data'    => $book,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $book = $request->user()->seller->books()->find($id);

        if (!$book) {
            return response()->json(['status' => 'error', 'message' => 'Buku tidak ditemukan'], 404);
        }

        $data = $request->validate($this->rules(), $this->messages());

        if ($request->hasFile('cover_img')) {
            if ($book->cover_img) {
                Storage::disk('public')->delete($book->cover_img);
            }
            $data['cover_img'] = $request->file('cover_img')->store('books', 'public');
        } else {
            unset($data['cover_img']);
        }

        $book->update($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data produk buku berhasil diperbarui',
            'data'    => $book,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $book = $request->user()->seller->books()->find($id);

        if (!$book) {
            return response()->json(['status' => 'error', 'message' => 'Buku tidak ditemukan'], 404);
        }

        try {
            $book->delete();
        } catch (QueryException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Buku tidak bisa dihapus karena sudah ada di pesanan. Ubah stok menjadi 0 saja.',
            ], 409);
        }

        if ($book->cover_img) {
            Storage::disk('public')->delete($book->cover_img);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Buku berhasil dihapus',
        ]);
    }
}