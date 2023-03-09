<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalculationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'year_of_production' => $this->year_of_production,
            'is_price_net' => $this->is_price_net,
            'price_net' => $this->net_car_value,
            'with_gps' => $this->with_gps,
            'price_gross' => $this->gross_car_value,
            'payments_no' => $this->payments_no,
            'insurance_cost' => $this->insurance_cost,
            'installment_cost' => $this->installment_cost,
        ];
    }
}
