<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UnderMaxNetValueRule;

class StoreCalculationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'year_of_production' => [
                'required',
                'integer',
                'min:' . config('calculations.min_car_production_year'),
                'max:' . date("Y"),
            ],
            'car_value' => [
                'required',
                'integer',
                'min:' . 1,
                new UnderMaxNetValueRule()
            ],
            'is_price_net'   => 'boolean',
            'with_gps'       => 'boolean',
            'payments_no'    => 'required|integer|in:1,2,4',
        ];
    }
}
