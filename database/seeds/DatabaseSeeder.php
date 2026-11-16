<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([

            RqSeeder::class,
            FuelSeeder::class,
            IssuanceRequestSeeder::class,
            DepartmentCodeSeeder::class

        ]);

        $this->users();
    }

    public function users()
    {
        $users = [
            [
                'name' => 'ICT ADMIN',
                'domainAccount' => 'ictadmin',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
                'role' => 'super admin',
                'dept' => 'ICT/COMMUNICATIONS',
                'isActive' => 1,
                'remember_token' => 'lveHarp2XhkoYSw13CbkRpc6FX4xDBa2Q5B15Q7HBztUb79WcbZhRtDKinFO',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Myrna Improso',
                'domainAccount' => 'mimproso',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
                'role' => 'admin',
                'dept' => 'Material Control Department',
                'isActive' => 1,
                'remember_token' => 'lveHarp2XhkoYSw13CbkRpc6FX4xDBa2Q5B15Q7HBztUb79WcbZhRtDKinFO',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Gas Man',
                'domainAccount' => 'gasman',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
                'role' => 'gas man',
                'dept' => 'Material Control Department',
                'isActive' => 1,
                'remember_token' => 'lveHarp2XhkoYSw13CbkRpc6FX4xDBa2Q5B15Q7HBztUb79WcbZhRtDKinFO',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]
        ];

        DB::table('fms_users_tbl')->insert($users);
    }
}
