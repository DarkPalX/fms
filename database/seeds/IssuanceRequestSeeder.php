<?php

use Illuminate\Database\Seeder;

class IssuanceRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\IssuanceRequest::insert([
            [
                'uniq_code' => '20200214RiD9',
                'rq' => 'RQ202001',
                'batch_no' => 'BATCH#20200213',
                'cost_code' => 'HDZRD5N01.DE02A.158',
                'requested_qty' => '40.00',
                'actual_qty' => NULL,
                'dept_id' => 43,	
                'dept' => 'IT/COMMUNICATIONS',
                'driver_id' => 1,
                'driver' => 'EDGAR JR. FELIX SAGARIO',
                'vehicle_id' => 40,
                'plate_no' => 'XZ-8397',
                'vehicle' => 'ROUSER 135',
                'odo' => 500,
                'fuel_type' => 'Fuel,Diesel Max,Petron',
                'item_code' => 17226,
                'status' => 'PENDING',
                'purpose' => 'lorem ipsum dolor',
                'is_outside' => 0,
                'processed_by' => NULL,  
                'added_by' => 'pmcadmin',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'uniq_code' => '20200214zwjD',
                'rq' => 'RQ202002',
                'batch_no' => 'BATCH#20200214',
                'cost_code' => 'HDZRD5N01.DE02A.158',
                'requested_qty' => '100.00',
                'actual_qty' => NULL,
                'dept_id' => 43,	
                'dept' => 'IT/COMMUNICATIONS',
                'driver_id' => 14,
                'driver' => 'ELEZALDE SOLIS YAKIT',
                'vehicle_id' => 43,
                'plate_no' => '9753-QJ',
                'vehicle' => 'TMX 155',
                'odo' => 1500,
                'fuel_type' => 'Fuel,Gasoline XCS (Special)',
                'item_code' => 11369,
                'status' => 'PENDING',
                'purpose' => 'lorem ipsum dolor',
                'is_outside' => 0,
                'processed_by' => NULL,
                'added_by' => 'pmcadmin',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
     
        ]);
    }
}
