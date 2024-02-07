<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class TokenOrderRequest extends BaseRequest {
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
      'orders' => ['required', 'array', 'min:1'],
      'orders.*.store_id' => ['required', 'string', 'exists:stores,id'],
      'orders.*.items' => ['required', 'array'],
      'orders.*.items.*.id' => ['required', 'string', 'exists:products,id'],
      'orders.*.items.*.product_variant_id' => ['required', 'string', 'exists:product_variants,id'],
      'orders.*.items.*.quantity' => ['required', 'integer'],
      'orders.*.shipping.shipping_courier' => ['required', 'string'],
      'orders.*.shipping.delivery_service' => ['required', 'string'],
      'orders.*.shipping.delivery_cost' => ['required', 'integer'],
      'shipping_detail.reciever_name' => ['required', 'string'],
      'shipping_detail.reciever_phone' => ['required', 'string'],
      'shipping_detail.delivery_province_id' => ['required', 'string'],
      'shipping_detail.delivery_province' => ['required', 'string'],
      'shipping_detail.delivery_city_id' => ['required', 'string'],
      'shipping_detail.delivery_city' => ['required', 'string'],
      'shipping_detail.delivery_postal_code' => ['required', 'string'],
      'shipping_detail.delivery_address' => ['required', 'string'],
    ];
  }

  /**
   * Get the error messages for the defined validation rules.
   *
   * @return array<string, string>
   */
  public function messages(): array {
    return [
      'variants' => [
        'array' => 'Format Varian tidak sesuai',
      ],
    ];
  }
}
