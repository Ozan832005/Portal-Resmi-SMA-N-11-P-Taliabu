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
        $thumbnailPath = null;
        $pdfPath = null;

        try {
            $thumbnailPath = $request->file('thumbnail')->store('images/thumbnail', 'public');

            if ($thumbnailPath === false) {
                throw new RuntimeException('Sampul buku tidak berhasil disimpan.');
            }

            if ($request->hasFile('pdf_file')) {
                $pdfPath = $request->file('pdf_file')->store('books/pdf', 'public');

                if ($pdfPath === false) {
                    throw new RuntimeException('File buku tidak berhasil disimpan.');
                }
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
            $book->pdf_path = $pdfPath;

            DB::transaction(function () use ($book): void {
                $book->save();
                $book->book_code = $this->generateNumber($book);
                $book->save();
            });
        } catch (Throwable $exception) {
            if (is_string($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }

            if (is_string($pdfPath)) {
                Storage::disk('public')->delete($pdfPath);
            }

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
        $oldPdfPath = $book->pdf_path;
        $newThumbnailPath = null;
        $newPdfPath = null;

        try {
            if ($request->hasFile('thumbnail')) {
                $newThumbnailPath = $request->file('thumbnail')->store('images/thumbnail', 'public');

                if ($newThumbnailPath === false) {
                    throw new RuntimeException('Sampul buku tidak berhasil disimpan.');
                }

                $book->thumbnail = $newThumbnailPath;
            }

            if ($request->hasFile('pdf_file')) {
                $newPdfPath = $request->file('pdf_file')->store('books/pdf', 'public');

                if ($newPdfPath === false) {
                    throw new RuntimeException('File buku tidak berhasil disimpan.');
                }

                $book->pdf_path = $newPdfPath;
            }

            $book->name = $request->name;
            $book->description = $request->description;
            $book->category_id = $request->category_id;
            $book->publisher_id = $request->publisher_id;
            $book->author_id = $request->author_id;
            $book->publication_year = (int) $request->publication_year;
            $book->isbn = $request->isbn;
            $book->stock = $request->stock;

            DB::transaction(fn () => $book->save());
        } catch (Throwable $exception) {
            if (is_string($newThumbnailPath)) {
                Storage::disk('public')->delete($newThumbnailPath);
            }

            if (is_string($newPdfPath)) {
                Storage::disk('public')->delete($newPdfPath);
            }

            throw $exception;
        }

        if ($newThumbnailPath !== null) {
            $this->deleteThumbnailIfUnused($oldThumbnail);
        }

        if ($newPdfPath !== null && $oldPdfPath !== null) {
            $this->deletePdf($oldPdfPath);
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
        $pdfPath = $book->pdf_path;
        $book->delete();
        $this->deleteThumbnailIfUnused($thumbnail);

        if ($pdfPath !== null) {
            $this->deletePdf($pdfPath);
        }

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

    private function deletePdf(string $path): void
    {
        if (! Storage::disk('public')->delete($path)) {
            throw new RuntimeException('File PDF buku tidak berhasil dihapus.');
        }
    }
}
