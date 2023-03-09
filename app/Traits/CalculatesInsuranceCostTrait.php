<?php
namespace App\Traits;

trait CalculatesInsuranceCostTrait {

    private function getAgeMultiplier() : int
    {
        return $this->year_of_production >= config('calculations.new_car_production_year') ? 0 : config('calculations.used_car_multiplier');
    }
    
    private function getValueMultiplier(): float
    {
        foreach(config('calculations.multiplier_tresholds') as $treshold) 
        {
            if ($treshold['from'] <= $this->net_car_value && $treshold['to'] >= $this->net_car_value) 
            {
                return $treshold['value'];
            }
        }
    }

    private function getTotalMultiplier(): float
    {
        $baseMultiplier = ($this->getValueMultiplier() + $this->getAgeMultiplier()) / 100;

        $gpsAccountedMuliplier = $this->with_gps 
            ? $baseMultiplier 
            : $baseMultiplier * config('calculations.gps_multiplier');

        return $this->number_of_payments > 1 
            ? $gpsAccountedMuliplier * ((100 + $this->number_of_payments) / 100) 
            : $gpsAccountedMuliplier;
    }

    public function getInstallmentCostAttribute(): float
    {
        return $this->number_of_payments > 1 
            ? round($this->insurance_cost / $this->number_of_payments, 2) 
            : $this->insurance_cost;
    }

    public function getInsuranceCostAttribute(): float
    {

        $totalMultiplier = $this->getTotalMultiplier();
        
        
        $totalInsuranceCost = $this->net_car_value * $totalMultiplier;

        if ($this->number_of_payments > 1) {
            $totalInsuranceCost += config('calculations.installment_fixed_fee');
        }

        return round($totalInsuranceCost, 2);
    }
}