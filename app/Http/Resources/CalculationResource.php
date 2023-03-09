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
            'id' => $this->id,
            'year_of_production' => $this->year_of_production,
            'price_net' => $this->net_car_value,
            'price_gross' => $this->gross_car_value,
            'with_gps' => $this->with_gps,
            'number_of_payments' => $this->number_of_payments,
            'insurance_cost' => $this->insurance_cost,
            'installment_cost' => $this->installment_cost,
        ];
    }
}
