<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function user_reviews(Request $request) {
        $reviews = Review::where('user_id', auth()->user()->id)
            ->paramQuery($request->query())
            ->get();

        if (!$reviews) {
            return $this->responseFailed("Not Found", 404, "Reviews not found");
        }

        return $this->responseSuccess($reviews);
    }

    /**
     * Display a listing of the resource.
     */
    public function paginated_user_reviews(Request $request) {
        $reviews = Review::where('user_id', auth()->user()->id)
            ->getDataTable($request->query());

        if (!$reviews) {
            return $this->responseFailed("Reviews not Found", 404, "Reviews not found");
        }

        return $this->responseSuccess($reviews);
    }

    public function create(Request $request) {
        $validatedData = $request->validate([
            'reviews' => ['required', 'array', 'min:1'],
            'reviews.*.rating' => ['required', 'integer', 'min:1'],
            'reviews.*.coment' => ['string', 'min:1', 'nullable'],
            'reviews.*.product_id' => ['required', 'string', 'min:1'],
            'reviews.*.product_variant_id' => ['required', 'string', 'min:1'],
            'reviews.*.order_item_id' => ['required', 'string', 'min:1'],
        ]);

        $createdReviews = [];

        foreach ($validatedData['reviews'] as $review) {
            $item = Review::updateOrCreate(
                [
                    'product_id' => $review['product_id'],
                    'product_variant_id' => $review['product_id'],
                    'order_item_id' => $review['order_item_id'],
                    'user_id' => auth()->user()->id
                ],
                [
                    'rating' => $review['rating'],
                    'coment' => $review['coment']
                ]
            );

            array_push($createdReviews, $item);
        }

        return $this->responseSuccess($createdReviews);
    }
}
