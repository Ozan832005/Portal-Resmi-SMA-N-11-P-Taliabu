<?php

namespace Modules\Perpustakaan\Http\Controllers;

use App\Helpers\GlobalHelpers;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Modules\Perpustakaan\Entities\Author;
use Modules\Perpustakaan\Entities\Book;
use Modules\Perpustakaan\Entities\Category;
use Modules\Perpustakaan\Entities\Publisher;
use Modules\Perpustakaan\Http\Requests\BookRequest;
use RuntimeException;
use Throwable;

class BooksController extends Controller
{
    use GlobalHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $book = Book::with(['publisher', 'author', 'category'])
            ->withCount('borrowings')
            ->get();

        return view('perpustakaan::backend.books.index', compact('book'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        $publisher = Publisher::all();
        $author = Author::all();
        $category = Category::all();

        if ($publisher->isEmpty() || $author->isEmpty() || $category->isEmpty()) {
            Session::flash('error', 'Data Publisher, Author atau Category Buku Belum Ada!');

            return redirect()->route('books.index');
        }

        return view('perpustakaan::backend.books.create', compact('publisher', 'author', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(BookRequest $request)
    {
        $thumbnailPath = $request->file('thumbnail')->store('images/thumbnail', 'public');

        if ($thumbnailPath === false) {
            throw new RuntimeException('Sampul buku tidak berhasil disimpan.');
        }

        $book = new Book;
        $book->book_code = $this->generateNumber($book);
        $book->name = $request->name;
        $book->description = $request->description;
        $book->category_id = $request->category_id;
        $book->publisher_id = $request->publisher_id;
        $book->author_id = $request->author_id;
        $book->publication_year = (int) $request->publication_year;
        $book->isbn = $request->isbn;
        $book->stock = $request->stock;
        $book->thumbnail = $thumbnailPath;

        try {
            DB::transaction(function () use ($book): void {
                $book->save();
                $book->book_code = $this->generateNumber($book);
                $book->save();
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($thumbnailPath);

            throw $exception;
        }

        Session::flash('success', 'Buku Berhasil ditambah.');

        return redirect()->route('books.index');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('perpustakaan::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $publisher = Publisher::all();
        $author = Author::all();
        $category = Category::all();

        return view('perpustakaan::backend.books.create', compact('book', 'publisher', 'author', 'category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(BookRequest $request, $id)
    {
        $book = Book::findOrFail($id);
        $oldThumbnail = $book->thumbnail;
        $newThumbnailPath = null;

        if ($request->hasFile('thumbnail')) {
            $newThumbnailPath = $request->file('thumbnail')->store('images/thumbnail', 'public');

            if ($newThumbnailPath === false) {
                throw new RuntimeException('Sampul buku tidak berhasil disimpan.');
            }

            $book->thumbnail = $newThumbnailPath;
        }

        $book->name = $request->name;
        $book->description = $request->description;
        $book->category_id = $request->category_id;
        $book->publisher_id = $request->publisher_id;
        $book->author_id = $request->author_id;
        $book->publication_year = (int) $request->publication_year;
        $book->isbn = $request->isbn;
        $book->stock = $request->stock;

        try {
            DB::transaction(fn () => $book->save());
        } catch (Throwable $exception) {
            if ($newThumbnailPath !== null) {
                Storage::disk('public')->delete($newThumbnailPath);
            }

            throw $exception;
        }

        if ($newThumbnailPath !== null) {
            $this->deleteThumbnailIfUnused($oldThumbnail);
        }

        Session::flash('success', 'Data buku berhasil diperbarui.');

        return redirect()->route('books.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        if ($book->borrowings()->exists()) {
            Session::flash('error', 'Buku tidak dapat dihapus karena memiliki riwayat peminjaman.');

            return redirect()->route('books.index');
        }

        $thumbnail = $book->thumbnail;
        $book->delete();
        $this->deleteThumbnailIfUnused($thumbnail);

        Session::flash('success', 'Data buku berhasil dihapus.');

        return redirect()->route('books.index');
    }

    private function deleteThumbnailIfUnused(string $thumbnail): void
    {
        $filename = basename(str_replace('\\', '/', $thumbnail));
        $thumbnailPaths = [$thumbnail, 'images/thumbnail/'.$filename, $filename];

        if (Book::query()->whereIn('thumbnail', $thumbnailPaths)->exists()) {
            return;
        }

        Storage::disk('public')->delete('images/thumbnail/'.$filename);
        Storage::disk('local')->delete('public/images/thumbnail/'.$filename);
    }
}
