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
    $res = Http::withHeaders([
      'key' => env('RAJAONGKIR_API_KEY', ''),
    ])->get('https://api.rajaongkir.com/starter/province');

    if ($res->failed()) {
      return $this->responseFailed("Not Found", 500, "List Province not found");
    }

    $data = $res->json();
    $listProvince = $data['rajaongkir']['results'];
    return $this->responseSuccess($listProvince);
  }

  public function get_cities(Request $request) {
    $province_id = $request->query('province_id');

    if (!$province_id) {
      return $this->responseFailed("Invalid params", 500, "No province_id provided");
    }

    $res = Http::withHeaders([
      'key' => env('RAJAONGKIR_API_KEY', ''),
    ])->get('https://api.rajaongkir.com/starter/city', [
          'province' => $province_id
        ]);

    if ($res->failed()) {
      return $this->responseFailed("Not Found", 500, "List Cities not found");
    }

    $data = $res->json();
    // dd($data['rajaongkir']['results']);
    $listCity = $data['rajaongkir']['results'];

    return $this->responseSuccess($listCity);
  }
}