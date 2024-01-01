<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class CreateVariantTypeRequest extends BaseRequest {
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
    ];
  }
}
