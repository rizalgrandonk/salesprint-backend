<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class UpdateProductRequest extends BaseRequest {
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
      'name' => ['required', 'string', 'max:1000',],
      'slug' => ['required', 'string', 'max:2000',],
      'slug_with_store' => ['required', 'string', 'max:2000'],
      'description' => ['required', 'string', 'max:5000',],
      'category_id' => ['required', 'string',],
      'store_category_id' => ['string', 'nullable'],
      'weight' => ['required', 'integer', 'max:5000',],
      'length' => ['required', 'integer', 'max:1000',],
      'width' => ['required', 'integer', 'max:1000',],
      'height' => ['required', 'integer', 'max:1000',],
      'variants' => ['array'],
      'variants.*.variant_type' => ['required', 'string'],
      'variants.*.variant_options' => ['array', 'min:1'],
      'variants.*.variant_options.*' => ['required', 'string'],
      'variant_combinations' => ['required', 'array', 'min:1'],
      'variant_combinations.*.price' => ['required',],
      'variant_combinations.*.stok' => ['required',],
      'variant_combinations.*.sku' => ['required', 'string'],
      'variant_combinations.*.*' => ['required', 'string']
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
        'max' => "Nama tidak boleh lebih dari 1000 karakter",
        'string' => 'Format Nama tidak sesuai',
      ],
      'slug' => [
        'required' => 'Nama harus diisi',
        'max' => "Nama tidak boleh lebih dari 2000 karakter",
        'string' => 'Format Nama tidak sesuai',
      ],
      'slug_with_store' => [
        'required' => 'Nama harus diisi',
        'max' => "Nama tidak boleh lebih dari 2000 karakter",
        'string' => 'Format Nama tidak sesuai',
        'unique' => "Nama sudah digunakan"
      ],
      'description' => [
        'required' => 'Deskripsi harus diisi',
        'max' => "Deskripsi tidak boleh lebih dari 5000 karakter",
        'string' => 'Format Deskripsi tidak sesuai',
      ],
      'category_id' => [
        'required' => 'Kategori harus diisi',
        'string' => 'Format Kategori tidak sesuai',
      ],
      'weight' => [
        'required' => 'Berat harus diisi',
        'max' => "Berat tidak boleh lebih dari 5000",
        'integer' => 'Format Berat tidak sesuai',
      ],
      'width' => [
        'required' => 'Lebar harus diisi',
        'max' => "Lebar tidak boleh lebih dari 1000",
        'integer' => 'Format Lebar tidak sesuai',
      ],
      'length' => [
        'required' => 'Panjang harus diisi',
        'max' => "Panjang tidak boleh lebih dari 1000",
        'integer' => 'Format Panjang tidak sesuai',
      ],
      'height' => [
        'required' => 'Tinggi harus diisi',
        'max' => "Tinggi tidak boleh lebih dari 1000",
        'integer' => 'Format Tinggi tidak sesuai',
      ],
      'variants' => [
        'array' => 'Format Varian tidak sesuai',
      ],
      'variants.*.variant_type' => [
        'required' => 'Tipe Varian harus diisi',
        'string' => 'Format Tipe Varian tidak sesuai',
      ],
      'variants.*.variant_options' => [
        'array' => 'Format Opsi Varian tidak sesuai',
      ],
      'variants.*.variant_options.*' => [
        'required' => 'Opsi Varian harus diisi',
        'string' => 'Format Opsi Varian tidak sesuai',
      ],
      'variant_combinations' => [
        'required' => 'Kombinasi Varian harus diisi',
        'array' => 'Format Kombinasi Varian tidak sesuai',
      ],
      'variant_combinations.*.sku' => [
        'required' => 'SKU Varian harus diisi',
        'string' => 'Format SKU Varian tidak sesuai',
      ],
      'variant_combinations.*.price' => [
        'required' => 'Harga Varian harus diisi',
      ],
      'variant_combinations.*.stok' => [
        'required' => 'Stok Varian harus diisi',
      ],
      'variant_combinations.*.*' => [
        'required' => 'Input Varian harus diisi',
        'string' => 'Format Input Varian tidak sesuai',
      ],
    ];
  }

  /**
   * @param string $key
   * @return array
   */
  public function getFileOrStringRule(string $key): array {
    if (request()->hasFile($key)) {
      return ['image', 'file', 'max:1024'];
    }
    return ['string', 'max:1000'];
  }
}
