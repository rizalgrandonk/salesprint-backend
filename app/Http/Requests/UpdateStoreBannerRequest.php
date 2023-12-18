<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class UpdateStoreBannerRequest extends BaseRequest {
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
      'description' => ['string', 'max:1000', 'nullable'],
      'image' => $this->getFileOrStringRule('image'),
    ];
  }

  /**
   * Get the error messages for the defined validation rules.
   *
   * @return array<string, string>
   */
  public function messages(): array {
    return [
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

  /**
   * @param string $key
   * @return array
   */
  public function getFileOrStringRule(string $key): array {
    if (request()->hasFile($key)) {
      return ['image', 'file', 'max:1024', 'nullable'];
    }
    return ['string', 'max:1000', 'nullable'];
  }
}
