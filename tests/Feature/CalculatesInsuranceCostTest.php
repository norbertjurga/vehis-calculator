<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Calculation;

class CalculatesInsuranceCostTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculates_insurance_for_new_cars()
    {
        $response = $this->post('/api/v1/calculations', [
            'year_of_production' => 2023,
            'car_value' => 170000,
            'is_price_net' => true,
            'with_gps' => true,
            'payments_no' => 1,
        ]);

        $response->assertSuccessful();

        $this->assertEquals(6800, Calculation::first()->insurance_cost);
    }

    public function test_calculates_insurance_for_used_cars()
    {
        $response = $this->post('/api/v1/calculations', [
            'year_of_production' => 1998,
            'car_value' => 12400,
            'is_price_net' => true,
            'with_gps' => true,
            'payments_no' => 1,
        ]);

        $response->assertSuccessful();

        $this->assertEquals(992, Calculation::first()->insurance_cost);
    }

    public function test_doesnt_calculate_insurance_for_cars_worth_over_treshold()
    {

        $response = $this->post('/api/v1/calculations', [
            'year_of_production' => 1948,
            'car_value' => 400500,
            'is_price_net' => true,
            'with_gps' => true,
            'payments_no' => 1,
        ]);

        $this->assertEquals(0, Calculation::count());

    }

    public function test_doesnt_calculate_insurance_for_cars_worth_less_than_one()
    {

        $response = $this->post('/api/v1/calculations', [
            'year_of_production' => 1948,
            'car_value' => -10,
            'is_price_net' => true,
            'with_gps' => true,
            'payments_no' => 1,
        ]);

        $this->assertEquals(0, Calculation::count());

    }

    public function test_calculates_for_two_installments()
    {
        $response = $this->post('/api/v1/calculations', [
            'year_of_production' => 2023,
            'car_value' => 100000,
            'is_price_net' => true,
            'with_gps' => true,
            'payments_no' => 2,
        ]);

        $this->assertEquals(5300, Calculation::first()->insurance_cost);
    }


    public function test_calculates_for_multiple_installments_and_no_gps()
    {
        $response = $this->post('/api/v1/calculations', [
            'year_of_production' => 2023,
            'car_value' => 100000,
            'is_price_net' => true,
            'with_gps' => false,
            'payments_no' => 2,
        ]);

        $this->assertEquals(5861, Calculation::first()->insurance_cost);
    }

    public function test_calculates_for_single_payment_and_no_gps()
    {
        $response = $this->post('/api/v1/calculations', [
            'year_of_production' => 2023,
            'car_value' => 100000,
            'is_price_net' => true,
            'with_gps' => false,
            'payments_no' => 1,
        ]);

        $this->assertEquals(5550, Calculation::first()->insurance_cost);
    }

    public function test_doesnt_calculate_for_ten_installments()
    {
        $response = $this->post('/api/v1/calculations', [
            'year_of_production' => 2023,
            'car_value' => 100000,
            'is_price_net' => true,
            'with_gps' => true,
            'payments_no' => 10,
        ]);

        $this->assertEquals(0, Calculation::count());
    }

    public function test_calculates_with_net_prices()
    {
        $response = $this->post('/api/v1/calculations', [
            'year_of_production' => 2023,
            'car_value' => 100000,
            'is_price_net' => true,
            'with_gps' => true,
            'payments_no' => 1,
        ]);

        $this->assertEquals(100000, Calculation::first()->net_car_value);
        $this->assertEquals(5000, Calculation::first()->insurance_cost);
    }

    public function test_calculates_with_gross_prices()
    {
        $response = $this->post('/api/v1/calculations', [
            'year_of_production' => 2023,
            'car_value' => 100000 * config('calculations.gross_to_net_multiplier'),
            'is_price_net' => false,
            'with_gps' => true,
            'payments_no' => 1,
        ]);

        $this->assertEquals(100000, Calculation::first()->net_car_value);
        $this->assertEquals(5000, Calculation::first()->insurance_cost);
    }

}