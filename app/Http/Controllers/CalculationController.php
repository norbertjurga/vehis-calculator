<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCalculationRequest;
use App\Models\Calculation;

use App\Http\Resources\CalculationResource;

class CalculationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCalculationRequest $request)
    {
        $calculation = Calculation::create($request->validated());
        return new CalculationResource( $calculation  );
    }
}
