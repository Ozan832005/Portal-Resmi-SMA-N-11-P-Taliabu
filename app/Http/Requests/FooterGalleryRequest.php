<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FooterGalleryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => [$this->isMethod('POST') ? 'required' : 'nullable', 'image', 'max:2048'],
            'caption' => ['nullable', 'string', 'max:120'],
            'urutan' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['required', 'in:0,1'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'image.required' => 'Foto galeri tidak boleh kosong.',
            'image.image' => 'Berkas harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal 2 MB.',
            'is_active.required' => 'Status tidak boleh kosong.',
        ];
    }
}
