<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCalculationRequest;
use App\Models\Calculation;
use App\Http\Actions\CreateNewCalculation;
use App\Http\Resources\CalculationResource;

class CalculationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCalculationRequest $request)
    {
        $calculation = (new CreateNewCalculation(
            $request->validated()
        ))->execute();

        return new CalculationResource( $calculation );
    }
}
