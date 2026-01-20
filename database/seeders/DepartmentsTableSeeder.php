<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('departments')->delete();
        
        \DB::table('departments')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'GLAUCOMA',
                'created_at' => '2025-06-13 13:10:54',
                'updated_at' => '2025-06-13 13:10:54',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'MEDICINE',
                'created_at' => '2025-06-24 14:19:55',
                'updated_at' => '2025-06-24 14:19:55',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'NURSING',
                'created_at' => '2025-06-24 14:20:11',
                'updated_at' => '2025-06-24 14:20:11',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'MEDICAL RECORDS',
                'created_at' => '2025-06-24 14:20:27',
                'updated_at' => '2025-06-24 14:20:27',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'OPTICAL',
                'created_at' => '2025-06-24 14:20:45',
                'updated_at' => '2025-06-24 14:20:45',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'LABORATORY',
                'created_at' => '2025-06-24 14:21:01',
                'updated_at' => '2025-06-24 14:21:01',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'PHARMACY',
                'created_at' => '2025-06-24 14:21:15',
                'updated_at' => '2025-06-24 14:21:15',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'ACCOUNTS',
                'created_at' => '2025-06-24 14:21:31',
                'updated_at' => '2025-06-24 14:21:31',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'HUMAN RESOURCE',
                'created_at' => '2025-06-24 14:21:48',
                'updated_at' => '2025-06-24 14:21:48',
            ),
        ));
        
        
    }
}