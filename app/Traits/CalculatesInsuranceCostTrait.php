<?php
namespace App\Traits;

trait CalculatesInsuranceCostTrait {

    public function getAgeMultiplierAttribute() : bool
    {
        return $this->year_of_production >= config('calculations.new_car_production_year') ? 0 : config('calculations.used_car_multiplier');
    }

    public function getBaseMultiplierAttribute(): float
    {

        $multipliersTresholds = config('calculations.multiplier_tresholds');

        foreach($multipliersTresholds as $treshold) {

            if ($treshold['from'] <= $this->net_car_value && $treshold['to'] >= $this->net_car_value) {
                return ($treshold['value'] + $this->age_multiplier) / 100;
            }

        }
       
        return throw new \Exception('No multiplier found for car value: ' . $this->car_value);
        
    }

    public function getGrossCarValueAttribute(): float
    {
        return !$this->is_price_net ? $this->car_value : round($this->car_value * config('calculations.gross_to_net_multiplier'));
    }

    public function getNetCarValueAttribute(): float
    {
        return $this->is_price_net ? $this->car_value : round($this->car_value / config('calculations.gross_to_net_multiplier'));
    }

    public function getTotalMultiplierAttribute(): float
    {
        $gpsAccountedMuliplier = $this->with_gps ? $this->base_multiplier : $this->base_multiplier * config('calculations.gps_multiplier');
        return $this->payments_no > 1 ? $gpsAccountedMuliplier * ((100 + $this->payments_no) / 100) : $gpsAccountedMuliplier;
    }

    public function getInstallmentCostAttribute(): float
    {
        return $this->payments_no > 1 ? round($this->insurance_cost / $this->payments_no, 2) : $this->insurance_cost;
    }

    public function getInsuranceCostAttribute(): float
    {

        $totalMultiplier = $this->total_multiplier;
        
        $totalInsuranceCost = $this->payments_no > 1 
                ? $this->net_car_value * $totalMultiplier + config('calculations.installment_fixed_fee')
                : $this->net_car_value * $totalMultiplier;

        return round($totalInsuranceCost, 2);
    }
}