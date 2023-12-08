<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'image' => ['string', 'max:255'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array {
        return [
            'name' => [
                'required' => 'Nama harus diisi',
                'max' => "Nama tidak boleh lebih dari 255 karakter",
                'string' => 'Format nama tidak sesuai',
            ],
            'email' => [
                'required' => 'Email harus diisi',
                'email' => 'Format email tidak sesuai',
                'string' => 'Format email tidak sesuai',
                'max' => "Email tidak boleh lebih dari 255 karakter",
                'unique' => 'Email sudah dipakai'
            ],
            'username' => [
                'required' => 'Nama pengguna harus diisi',
                'max' => "Nama pengguna tidak boleh lebih dari 255 karakter",
                'unique' => 'Nama pengguna sudah dipakai',
                'string' => 'Format nama pengguna tidak sesuai',
            ],
            'phone_number' => [
                'required' => 'Nomor telepon harus diisi',
                'max' => "Nomor telepon tidak boleh lebih dari 255 karakter",
                'unique' => 'Nomor telepon sudah dipakai',
                'string' => 'Format nomor telepon tidak sesuai',
            ],
            'password' => [
                'required' => 'Password harus diisi',
                'min' => "Password harus lebih dari 8 karakter",
                'string' => 'Format nomor telepon tidak sesuai',
            ],
            'image' => [
                'required' => 'Foto profil harus diisi',
                'max' => "Foto profil tidak boleh lebih dari 255 karakter",
                'string' => 'Format foto profil tidak sesuai',
            ],
        ];
    }
}
