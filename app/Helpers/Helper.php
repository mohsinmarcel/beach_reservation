<?php

namespace App\Helpers;

use App\Models\Pricing;

/**
 * Class Helper
 * @package App\Helpers
 */
class Helper
{

    public static function getActivePricing()
    {
        $pricing = Pricing::where('is_active', 1)->first();
        $seatPrice = $pricing->price_per_seat ?? 10;
        $umbrellaPrice = $pricing->price_per_umbrella ?? 5;
        $baseSetPrice = ($seatPrice * 2) + $umbrellaPrice;
        return [
            'base_set' => $baseSetPrice,
            'seat' => $seatPrice,
            'umbrella' => $umbrellaPrice,
            'priceId' => $pricing->id,
        ];
    }


}
