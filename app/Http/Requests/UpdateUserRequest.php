<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class UpdateUserRequest extends BaseRequest {
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
      'name' => ['nullable', 'string', 'max:100',],
      'username' => ['nullable', 'string', 'max:100',],
      'email' => ['nullable', 'string', 'max:100',],
      'phone_number' => ['nullable', 'string', 'max:100',],
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
        'max' => "Nama tidak boleh lebih dari 100 karakter",
        'string' => 'Format Nama tidak sesuai',
      ],
      'username' => [
        'max' => "Nama Pengguna tidak boleh lebih dari 100 karakter",
        'string' => 'Format Nama Pengguna tidak sesuai',
        'unique' => 'Nama Pengguna sudah digunakan'
      ],
      'email' => [
        'max' => "Email tidak boleh lebih dari 100 karakter",
        'string' => 'Format Email tidak sesuai',
        'unique' => 'Email sudah digunakan'
      ],
      'phone_number' => [
        'max' => "Nomor Telepon tidak boleh lebih dari 100 karakter",
        'string' => 'Format Nomor Telepon tidak sesuai',
        'unique' => 'Nomor Telepon sudah digunakan'
      ],
      'image' => [
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
      return ['nullable', 'image', 'file', 'max:1024'];
    }
    return ['nullable', 'string', 'max:1000'];
  }
}
