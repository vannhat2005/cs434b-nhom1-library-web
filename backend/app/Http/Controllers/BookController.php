<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\HttpCache\Store;

class BookController extends Controller
{
    public function getAllSach()
    {
        $data = Book::all();
        return response()->json([
            'status' => true,
            'message' => 'Lấy danh sách sách thành công',
            'data' => $data

        ]);
    }
    public function addDataSach(StoreBookRequest $request)
    {
        $data = $request->validated();

        //Tự động gán số lượng sách có sẵn bằng số lượng sách khi thêm mới
        $data['available'] = $data['quantity'];
        $book = Book::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Thêm sách ' . $book->title . ' thành công!',
            'data' => $book
        ]);

    }
    public function updateDataSach(UpdateBookRequest $request, Book $book)
    {
        $data = $request->validated();

        $book->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật sách ' . $book->title . ' thành công!',
            'data' => $book
        ]);
    }
    public function deleteDataSach(Book $book)
    {

        $book->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xóa sách ' . $book->title . ' thành công!',
        ]);
    }
    public function searchSach(Request $request)
    {
        $keyword = $request->keyword;

        $results = Book::join('authors', 'authors.id', '=', 'books.author_id')
            ->where('books.title', 'LIKE', '%' . $keyword . '%')
            ->orWhere('authors.name', 'LIKE', '%' . $keyword . '%')
            ->select('books.*', 'authors.name as author_name')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Tìm kiếm sách thành công!',
            'data' => $results
        ]);
    }

    //Admin
    

}
