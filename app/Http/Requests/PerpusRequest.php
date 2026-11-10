<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PerpusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'nip' => ['required', 'numeric'],
            'foto_profile' => ['required', 'image', 'max:1024'],
            'website' => ['nullable', 'string', 'max:255'],
            'linkedln' => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'youtube' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama Petugas Perpustakaan tidak boleh kosong.',
            'name.unique' => 'Nama Petugas Perpustakaan sudah pernah digunakan.',
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Email yang dimasukkan tidak valid.',
            'email.unique' => 'Email sudah pernah digunakan.',
            'nip.required' => 'NIP tidak boleh kosong.',
            'nip.numeric' => 'NIP yang dimasukkan tidak valid.',
            'foto_profile.required' => 'Foto profil tidak boleh kosong.',
            'foto_profile.image' => 'Foto yang dimasukkan tidak valid.',
            'foto_profile.max' => 'Maksimal ukuran foto adalah 1MB.',
        ];
    }
}
