<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\CalculatesInsuranceCostTrait;

class Calculation extends Model
{
    use HasFactory;
    use CalculatesInsuranceCostTrait;

    protected $fillable = [
        'year_of_production',
        'car_value',
        'is_price_net',
        'with_gps',
        'payments_no',
    ];

    protected $casts = [
        'is_price_net' => 'boolean',
        'with_gps' => 'boolean',
    ];

    public $appends = [
        'insurance_cost',
    ];

}
