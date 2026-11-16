<?php

use Illuminate\Database\Seeder;

class FuelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Fuels::insert([
            [
                'fuel_type' => 'Fuel,Diesel Max,Petron',
                'isActive' => 1,
                'stock_code' => 17226,
            ],
            [
                'fuel_type' => 'Fuel,Biodiesel B-2,Phoenix',
                'isActive' => 1,
                'stock_code' => 17227,
            ],
            [
                'fuel_type' => 'Fuel,Gasoline Extra Advance',
                'isActive' => 1,
                'stock_code' => 11368,
            ],
            [
                'fuel_type' => 'Fuel,Gasoline XCS (Special)',
                'isActive' => 1,
                'stock_code' => 11369,
            ]
     
        ]);
    }
}
