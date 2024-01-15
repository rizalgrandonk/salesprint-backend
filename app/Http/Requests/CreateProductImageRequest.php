<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class CreateProductImageRequest extends BaseRequest {
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
      'main_image' => ['required', ...$this->getFileOrStringRule('main_image')],
      'images' => ['required', 'array', 'min:1', 'max:12'],
      'images.*' => ['required', ...$this->getFileOrStringRule('images.*')]
    ];
  }

  /**
   * Get the error messages for the defined validation rules.
   *
   * @return array<string, string>
   */
  public function messages(): array {
    return [
      'main_image' => [
        'required' => 'Gambar Utama harus diisi',
        'max' => "Ukuran Gambar Utama terlalu besar",
        'image' => 'Format Gambar Utama tidak sesuai',
        'file' => 'Format Gambar Utama tidak sesuai',
      ],
      'images' => [
        'required' => 'Gambar harus diisi',
        'array' => 'Format Gambar tidak sesuai',
      ],
      'images.*' => [
        'required' => 'Gambar Utama harus diisi',
        'max' => "Ukuran Gambar Utama terlalu besar",
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
