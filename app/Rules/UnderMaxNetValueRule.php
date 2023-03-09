<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\Lang;
use Illuminate\Contracts\Validation\Rule;

class UnderMaxNetValueRule implements Rule
{


    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        $netValue = request()->is_price_net ? $value : $value / config('calculations.gross_to_net_multiplier');

        if ($netValue > config('calculations.max_car_value')) {
            return false;
        }

        return true;
   
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {

        return Lang::get('validation.net_value_exceded', [
            'value' => config('calculations.max_car_value'),
        ]);
    }
}
