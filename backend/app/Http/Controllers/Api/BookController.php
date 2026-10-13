<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::with(['category:id,name', 'seller:id,name'])
            ->when($request->search, function ($q, $s) {
                $q->where(function ($w) use ($s) {
                    $w->where('title', 'like', "%{$s}%")
                      ->orWhere('author', 'like', "%{$s}%");
                });
            })
            ->when($request->category_id, fn ($q, $c) => $q->where('category_id', $c))
            ->when($request->seller_id, fn ($q, $s) => $q->where('seller_id', $s))
            ->latest()
            ->paginate(12);

        return response()->json([
            'status' => 'success',
            'data'   => $books->items(),
            'meta'   => [
                'current_page' => $books->currentPage(),
                'last_page'    => $books->lastPage(),
                'total'        => $books->total(),
            ],
        ]);
    }

    public function show($id)
    {
        $book = Book::with(['category:id,name', 'seller:id,name'])->find($id);

        if (!$book) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Buku tidak ditemukan',
            ], 404);
        }

        return response()->json(['status' => 'success', 'data' => $book]);
    }
}