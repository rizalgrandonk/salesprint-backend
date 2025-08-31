<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LogisticController extends Controller {
  /**
   * Get list available province
   */
  public function get_province() {
    $res = Http::retry(3, 1000)->withHeaders([
            'key' => env('RAJAONGKIR_API_KEY', ''),
        ])->get(
                env(
                    'RAJAONGKIR_BASE_URL',
                    'https://rajaongkir.komerce.id/api/v1'
                ) . '/destination/province'
            );

    if ($res->failed()) {
      return $this->responseFailed("Not Found", 500, "List Province not found");
    }

    $data = $res->json();
    $listProvince = $data['data'];
    return $this->responseSuccess($listProvince);
  }

  public function get_cities(Request $request) {
    $province_id = $request->query('province_id');

    if (!$province_id) {
      return $this->responseFailed("Invalid params", 500, "No province_id provided");
    }

    $res = Http::retry(3, 1000)->withHeaders([
            'key' => env('RAJAONGKIR_API_KEY', ''),
        ])->get(
                env(
                    'RAJAONGKIR_BASE_URL',
                    'https://rajaongkir.komerce.id/api/v1'
                ) . '/destination/city/' . $province_id
            );

    if ($res->failed()) {
      return $this->responseFailed("Not Found", 500, "List Cities not found");
    }

    $data = $res->json();
    // dd($data['rajaongkir']['results']);
    $listCity = $data['data'];

    return $this->responseSuccess($listCity);
  }

  public function get_districts(Request $request) {
    $city_id = $request->query('city_id');

    if (!$city_id) {
      return $this->responseFailed("Invalid params", 500, "No city_id provided");
    }

    $res = Http::retry(3, 1000)->withHeaders([
            'key' => env('RAJAONGKIR_API_KEY', ''),
        ])->get(
                env(
                    'RAJAONGKIR_BASE_URL',
                    'https://rajaongkir.komerce.id/api/v1'
                ) . '/destination/district/' . $city_id
            );

    if ($res->failed()) {
      return $this->responseFailed("Not Found", 500, "List Districts not found");
    }

    $data = $res->json();
    // dd($data['rajaongkir']['results']);
    $listDistrict = $data['data'];

    return $this->responseSuccess($listDistrict);
  }

  public function cost(Request $request) {
    $validatedData = $request->validate([
      'origin' => ['required', 'string'],
      'destination' => ['required', 'string'],
      'weight' => ['required', 'integer'],
    ]);

    $res = Http::withHeaders([
      'key' => env('RAJAONGKIR_API_KEY', ''),
    ])
      ->post(
        env(
          'RAJAONGKIR_BASE_URL',
          'https://rajaongkir.komerce.id/api/v1'
        ) . '/calculate/district/domestic-cost',
        [
          "origin" => $validatedData['origin'],
          "destination" => $validatedData['destination'],
          "weight" => (int) $validatedData['weight'],
          "courier" => "jne:sicepat:ide:sap:jnt:ninja:tiki:lion:anteraja:pos:ncs:rex:rpx:sentral:star:wahana:dse"
        ]
      );

    if ($res->failed()) {
      return $this->responseFailed("Not Found", 500, "List Costs not found");
    }

    $data = $res->json();
    $list = $data['data'];

    return $this->responseSuccess($list);
  }
}
