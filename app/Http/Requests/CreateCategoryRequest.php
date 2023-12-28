<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class CreateCategoryRequest extends BaseRequest {
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
      'slug' => ['required', 'string', 'max:1200', 'unique:categories'],
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
      'name' => [
        'required' => 'Nama harus diisi',
        'max' => "Nama tidak boleh lebih dari 1000 karakter",
        'string' => 'Format Nama tidak sesuai',
      ],
      'slug' => [
        'required' => 'Nama harus diisi',
        'max' => "Nama tidak boleh lebih dari 1200 karakter",
        'string' => 'Format Nama tidak sesuai',
        'unique' => 'Nama sudah digunakan'
      ],
      'image' => [
        'required' => 'Gambar harus diisi',
        'max' => "Foto tidak boleh lebih dari 1 MB",
        'image' => 'Format Foto tidak sesuai',
        'file' => 'Format Foto tidak sesuai',
      ],
    ];
  }

  /**
   * @param string $key
   * @return array
   */
  public function getFileOrStringRule(string $key): array {
    if (request()->hasFile($key)) {
      return ['required', 'image', 'file', 'max:1024'];
    }
    return ['required', 'string', 'max:1000'];
  }
}
