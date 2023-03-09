<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\CalculatesGrossValueTrait;
use App\Traits\CalculatesInsuranceCostTrait;

class Calculation extends Model
{
    use HasFactory;
    use CalculatesInsuranceCostTrait;
    use CalculatesGrossValueTrait;

    protected $fillable = [
        'year_of_production',
        'net_car_value',
        'with_gps',
        'number_of_payments',
    ];

    protected $casts = [
        'with_gps' => 'boolean',
    ];

}
