<?php 

namespace App\Traits;

trait CalculatesGrossValueTrait {
    public function getGrossCarValueAttribute(): float
    {
        return round($this->net_car_value * config('calculations.gross_to_net_multiplier'));
    }
}