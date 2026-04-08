<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LicenseController;
use App\Models\PromoCode;
use Illuminate\Http\Request;

Route::middleware('throttle:30,1')->group(function () {
    Route::post('/license/validate', [LicenseController::class, 'validate']);
});

Route::middleware('throttle:10,1')->post('/promo/validate', function (Request $request) {
    $request->validate([
        'code' => 'required|string|max:50',
        'price' => 'required|numeric|min:0.01',
    ]);

    $promo = PromoCode::where('code', strtoupper(trim($request->code)))->first();

    if (!$promo || !$promo->isValid()) {
        return response()->json(['valid' => false, 'message' => 'Invalid or expired promo code.'], 422);
    }

    $price = (float) $request->price;
    $discount = $promo->calculateDiscount($price);

    if ($discount <= 0) {
        return response()->json(['valid' => false, 'message' => 'This code does not apply to this order.'], 422);
    }

    return response()->json([
        'valid' => true,
        'code' => $promo->code,
        'type' => $promo->type,
        'value' => $promo->value,
        'discount' => round($discount, 2),
        'final_price' => round($price - $discount, 2),
        'label' => $promo->type === 'percentage' ? $promo->value . '% off' : '$' . number_format($promo->value, 2) . ' off',
    ]);
});
