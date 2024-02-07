<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest {
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
            'total' => ['integer', 'nullable'],
            'serial_order' => ['string', 'nullable'],
            'snap_token' => ['string', 'nullable'],
            'transaction_id' => ['string', 'nullable'],
            'payment_status' => ['string', 'nullable'],
            'status_code' => ['string', 'nullable'],
            'status_message' => ['string', 'nullable'],
            'payment_type' => ['string', 'nullable'],
            'payment_code' => ['string', 'nullable'],
            'pdf_url' => ['string', 'nullable'],
            'receipt_number' => ['string', 'nullable'],
            'user_id' => ['string', 'nullable'],
        ];
    }
}
