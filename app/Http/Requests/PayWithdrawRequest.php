<?php

namespace App\Http\Requests;


class PayWithdrawRequest extends BaseRequest {
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
      'receipt' => ['image', 'file', 'max:1024', 'required'],
    ];
  }

  /**
   * Get the error messages for the defined validation rules.
   *
   * @return array<string, string>
   */
  public function messages(): array {
    return [
      'receipt' => [
        'required' => 'Gambar harus diisi',
        'max' => "Format File tidak boleh lebih dari 1 MB",
        'image' => 'Format Format File tidak sesuai',
        'file' => 'Format Format File tidak sesuai',
      ],
    ];
  }
}
