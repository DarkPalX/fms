<?php

use Illuminate\Database\Seeder;

class RqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\RQ::insert([
            [
                'rq_no' => 'RQ202001',
                'dept' => 'IT/COMMUNICATIONS',
                'status' => 'VERIFIED',
                'isLock' => 0,
                'batch_no' => 'BATCH#20200213',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'rq_no' => 'RQ202002',
                'dept' => 'IT/COMMUNICATIONS',
                'status' => 'VERIFIED',
                'isLock' => 0,
                'batch_no' => 'BATCH#20200214',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'rq_no' => 'RQ202003',
                'dept' => 'IT/COMMUNICATIONS',
                'status' => 'PENDING',
                'isLock' => 0,
                'batch_no' => NULL,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'rq_no' => 'RQ202004',
                'dept' => 'IT/COMMUNICATIONS',
                'status' => 'PENDING',
                'isLock' => 0,
                'batch_no' => NULL,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'rq_no' => 'RQ202005',
                'dept' => 'IT/COMMUNICATIONS',
                'status' => 'PENDING',
                'isLock' => 0,
                'batch_no' => NULL,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'rq_no' => 'RQ202006',
                'dept' => 'IT/COMMUNICATIONS',
                'status' => 'PENDING',
                'isLock' => 0,
                'batch_no' => NULL,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
     
        ]);
    }
}
