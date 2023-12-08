<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class StoreRequest extends BaseRequest {
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
            'slug' => ['string', 'max:255', 'unique:stores', 'nullable'],
            'phone_number' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'city_id' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'province_id' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:255'],
            'store_description' => ['string', 'max:1000', 'nullable'],
            'image' => ['image', 'file', 'max:1024', 'nullable'],
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
                'required' => 'Nama toko harus diisi',
                'max' => "Nama toko tidak boleh lebih dari 255 karakter",
                'string' => 'Format Nama toko tidak sesuai',
            ],
            'slug' => [
                'max' => "Domain toko tidak boleh lebih dari 255 karakter",
                'string' => 'Format Domain toko tidak sesuai',
                'unique' => 'Domain toko sudah dipakai',
            ],
            'phone_number' => [
                'required' => 'Nomor telepon harus diisi',
                'max' => "Nomor telepon tidak boleh lebih dari 255 karakter",
                'string' => 'Format Nomor telepon tidak sesuai',
            ],
            'address' => [
                'required' => 'Alamat toko harus diisi',
                'max' => "Alamat toko tidak boleh lebih dari 255 karakter",
                'string' => 'Format Alamat toko tidak sesuai',
            ],
            'city' => [
                'required' => 'Kota harus diisi',
                'max' => "Kota tidak boleh lebih dari 255 karakter",
                'string' => 'Format Kota tidak sesuai',
            ],
            'city_id' => [
                'required' => 'Kota harus diisi',
                'max' => "Kota tidak boleh lebih dari 255 karakter",
                'string' => 'Format Kota tidak sesuai',
            ],
            'province' => [
                'required' => 'Provinsi harus diisi',
                'max' => "Provinsi tidak boleh lebih dari 255 karakter",
                'string' => 'Format Provinsi tidak sesuai',
            ],
            'province_id' => [
                'required' => 'Provinsi harus diisi',
                'max' => "Provinsi tidak boleh lebih dari 255 karakter",
                'string' => 'Format Provinsi tidak sesuai',
            ],
            'postal_code' => [
                'required' => 'Kode pos harus diisi',
                'max' => "Kode pos tidak boleh lebih dari 255 karakter",
                'string' => 'Format Kode pos tidak sesuai',
            ],
            'image' => [
                'max' => "Foto tidak boleh lebih dari 1 MB",
                'image' => 'Format Foto tidak sesuai',
                'file' => 'Format Foto tidak sesuai',
            ],
            'store_description' => [
                'max' => "Deskripsi tidak boleh lebih dari 255 karakter",
                'string' => 'Format Deskripsi tidak sesuai',
            ],
        ];
    }
}
