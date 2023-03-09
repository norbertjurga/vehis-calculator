<?php

namespace App\Http\Actions;

use App\Models\Calculation;

class CreateNewCalculation
{

    public function __construct(private array $data) {}

    public function execute(): Calculation
    {
        $netValue = $this->getNetValue();

        $calculationData = [
            'year_of_production' => $this->data['year_of_production'],
            'net_car_value' => $netValue,
            'with_gps' => $this->data['with_gps'],
            'number_of_payments' => $this->data['number_of_payments'],
        ];

        return Calculation::create($calculationData);
    }

    private function getNetValue(): float
    {
        if ($this->data['is_price_net']) {
            return $this->data['car_value'];
        }

        return round($this->data['car_value'] / config('calculations.gross_to_net_multiplier'), 2);
    }
}