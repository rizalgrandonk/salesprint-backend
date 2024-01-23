<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class DeleteProductImageRequest extends BaseRequest {
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
      'image_url' => ['required', 'string', 'max:1000'],
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
        'required' => 'Gambar harus diisi',
        'max' => "Ukuran Gambar terlalu besar",
      ],
    ];
  }
}
