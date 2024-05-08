<?php

namespace App\Http\Requests;


class CreateWithdrawRequest extends BaseRequest {
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
            'bank_code' => ['nullable', 'string', 'max:1000',],
            'bank_name' => ['required', 'string', 'max:1000',],
            'bank_account_number' => ['required', 'string', 'max:1000',],
            'bank_account_name' => ['required', 'string', 'max:1000',],
            'denied_reason' => ['nullable', 'string', 'max:1000',],
            'receipt' => ['nullable', ...$this->getFileOrStringRule('receipt')],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array {
        return [
            'bank_code' => [
                'required' => 'Kode Bank harus diisi',
                'max' => "Kode Bank tidak boleh lebih dari 1000 karakter",
                'string' => 'Format Kode Bank tidak sesuai',
            ],
            'bank_name' => [
                'required' => 'Nama Bank harus diisi',
                'max' => "Nama Bank tidak boleh lebih dari 1000 karakter",
                'string' => 'Format Nama Bank tidak sesuai',
            ],
            'bank_account_number' => [
                'required' => 'Nomor Rekening harus diisi',
                'max' => "Nomor Rekening tidak boleh lebih dari 1000 karakter",
                'string' => 'Format Nomor Rekening tidak sesuai',
            ],
            'bank_account_name' => [
                'required' => 'Rekening Atas Nama harus diisi',
                'max' => "Rekening Atas Nama tidak boleh lebih dari 1000 karakter",
                'string' => 'Format Rekening Atas Nama tidak sesuai',
            ],
            'denied_reason' => [
                'max' => "Alasan Ditolak tidak boleh lebih dari 1000 karakter",
                'string' => 'Format Alasan Ditolak tidak sesuai',
            ],
            'receipt' => [
                'max' => "Format File tidak boleh lebih dari 1 MB",
                'image' => 'Format Format File tidak sesuai',
                'file' => 'Format Format File tidak sesuai',
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
