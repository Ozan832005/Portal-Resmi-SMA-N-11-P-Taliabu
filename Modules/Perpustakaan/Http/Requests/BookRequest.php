<?php

namespace Modules\Perpustakaan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRequest extends FormRequest
{
    public function rules(): array
    {
        $uniqueName = Rule::unique('books', 'name');

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $uniqueName->ignore($this->route('book'));
        }

        return [
            'name' => ['required', 'string', 'max:255', $uniqueName],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'publisher_id' => ['required', 'integer', 'exists:publishers,id'],
            'author_id' => ['required', 'integer', 'exists:authors,id'],
            'publication_year' => ['required', 'integer', 'between:1000,9999'],
            'isbn' => ['required', 'string', 'max:50'],
            'stock' => ['required', 'integer', 'min:0'],
            'thumbnail' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120',
            ],
            'pdf_file' => ['nullable', 'file', 'mimes:pdf', 'extensions:pdf', 'max:20480'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Judul Buku tidak boleh kosong.',
            'name.unique' => 'Judul Buku sudah pernah dibuat.',
            'publication_year.required' => 'Tahun Terbit tidak boleh kosong.',
            'publication_year.integer' => 'Tahun Terbit hanya mendukung angka.',
            'publication_year.between' => 'Tahun Terbit harus berisi 4 angka.',
            'description.required' => 'Deskripsi tidak boleh kosong.',
            'publisher_id.required' => 'Nama Penerbit tidak boleh kosong.',
            'publisher_id.exists' => 'Nama Penerbit yang dipilih tidak tersedia.',
            'category_id.required' => 'Kategori Buku tidak boleh kosong.',
            'category_id.exists' => 'Kategori Buku yang dipilih tidak tersedia.',
            'author_id.required' => 'Nama Penulis tidak boleh kosong.',
            'author_id.exists' => 'Nama Penulis yang dipilih tidak tersedia.',
            'isbn.required' => 'Nomor ISBN tidak boleh kosong.',
            'stock.required' => 'Stok Buku tidak boleh kosong.',
            'stock.integer' => 'Stok Buku hanya mendukung angka.',
            'stock.min' => 'Stok Buku tidak boleh kurang dari nol.',
            'thumbnail.required' => 'Gambar atau Cover Buku tidak boleh kosong.',
            'thumbnail.image' => 'Berkas sampul harus berupa gambar.',
            'thumbnail.mimes' => 'Gambar atau Cover Buku hanya mendukung .jpg .jpeg atau .png.',
            'thumbnail.max' => 'Ukuran sampul buku maksimal 5 MB.',
            'pdf_file.mimes' => 'File buku harus berupa PDF.',
            'pdf_file.extensions' => 'File buku harus menggunakan ekstensi .pdf.',
            'pdf_file.max' => 'Ukuran file buku maksimal 20 MB.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
