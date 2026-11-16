<?php

use Illuminate\Database\Seeder;

class DepartmentCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\DeptCode::insert([
            [
                'dept_code' => 'DE01A',
                'dept_desc' => 'Mine Engineering'
            ],
            [
                'dept_code' => 'DE01B',
                'dept_desc' => 'Mine Operation'
            ],
            [
                'dept_code' => 'DE01C',
                'dept_desc' => 'Mine Geology'
            ],
            [
                'dept_code' => 'DE01D',
                'dept_desc' => 'Mine Electrical'
            ],
            [
                'dept_code' => 'DE01E',
                'dept_desc' => 'Mine Mechanical'
            ],
            [
                'dept_code' => 'DE01F',
                'dept_desc' => 'Mine Explosive'
            ],
            [
                'dept_code' => 'DE02A',
                'dept_desc' => 'Mill Mechanical'
            ],
            [
                'dept_code' => 'DE02B',
                'dept_desc' => 'Mill Metalurgy'
            ],
            [
                'dept_code' => 'DE02C',
                'dept_desc' => 'Mill Electrical'
            ],
            [
                'dept_code' => 'DE03A',
                'dept_desc' => 'Assay'
            ],
            [
                'dept_code' => 'DE04A',
                'dept_desc' => 'ECS Office'
            ],
            [
                'dept_code' => 'DE04B',
                'dept_desc' => 'Motorpool'
            ],
            [
                'dept_code' => 'DE04C',
                'dept_desc' => 'Civil Works'
            ],
            [
                'dept_code' => 'DE04D',
                'dept_desc' => 'Electrical'
            ],
            [
                'dept_code' => 'DE05A',
                'dept_desc' => 'Security'
            ],
            [
                'dept_code' => 'DE06A',
                'dept_desc' => 'Mine Safety'
            ],
            [
                'dept_code' => 'DE06B',
                'dept_desc' => 'Mill Safety'
            ],
            [
                'dept_code' => 'DE07A',
                'dept_desc' => 'Environment'
            ],
            [
                'dept_code' => 'DE08A',
                'dept_desc' => 'Admin Office'
            ],
            [
                'dept_code' => 'DE08B',
                'dept_desc' => 'Medical'
            ],
            [
                'dept_code' => 'DE08C',
                'dept_desc' => 'Accounting'
            ],
            [
                'dept_code' => 'DE08D',
                'dept_desc' => 'MCD'
            ],
            [
                'dept_code' => 'DE08E',
                'dept_desc' => 'GSD'
            ],
            [
                'dept_code' => 'DE08F',
                'dept_desc' => 'IT'
            ],
            [
                'dept_code' => 'DE08H',
                'dept_desc' => 'HR'
            ],
            [
                'dept_code' => 'DE08I',
                'dept_desc' => 'Tenements'
            ],
            [
                'dept_code' => 'DE08J',
                'dept_desc' => 'Executive'
            ],
            [
                'dept_code' => 'DE08K',
                'dept_desc' => 'Legal'
            ],
            [
                'dept_code' => 'DE08M',
                'dept_desc' => 'Loss Control'
            ],
            [
                'dept_code' => 'DE10A',
                'dept_desc' => 'Exploration'
            ],
            [
                'dept_code' => 'DE21A',
                'dept_desc' => 'RCV Project'
            ],

        ]);
    }
}
