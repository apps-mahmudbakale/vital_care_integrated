<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CashPointsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('cash_points')->delete();
        
        \DB::table('cash_points')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'OPD CASHIER',
                'location' => 'ACCOUNT DEPARTMENT',
                'created_at' => '2025-06-05 12:18:50',
                'updated_at' => '2025-06-05 12:18:50',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'ACCOUNT',
                'location' => 'DRUGS',
                'created_at' => '2025-06-05 12:20:29',
                'updated_at' => '2025-06-05 12:20:29',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'optical cashier',
                'location' => 'Optical',
                'created_at' => '2025-06-10 08:21:01',
                'updated_at' => '2025-06-10 08:21:01',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Drugs cashier',
                'location' => 'Admission',
                'created_at' => '2025-06-10 08:21:25',
                'updated_at' => '2025-06-10 08:21:25',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'VIP Cashier',
                'location' => 'VIP LOUNGE',
                'created_at' => '2025-06-10 08:21:48',
                'updated_at' => '2025-06-10 08:21:48',
            ),
        ));
        
        
    }
}