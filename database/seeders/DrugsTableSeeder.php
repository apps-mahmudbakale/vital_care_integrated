<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DrugsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('drugs')->delete();
        
        \DB::table('drugs')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Misopt',
                'price' => '5000',
                'quantity' => 4993,
                'category_id' => 3,
                'threshold' => '1000',
                'expiry_date' => '2027-10-06',
                'is_active' => 1,
                'dispense_qty' => 0,
                'created_at' => '2025-06-10 08:42:30',
                'updated_at' => '2025-06-24 15:46:43',
                'store_id' => 2,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Amethocaine',
                'price' => '3000',
                'quantity' => 13,
                'category_id' => 7,
                'threshold' => '5',
                'expiry_date' => '2027-11-01',
                'is_active' => 1,
                'dispense_qty' => 0,
                'created_at' => '2025-06-14 09:08:59',
                'updated_at' => '2025-06-14 09:31:20',
                'store_id' => 2,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Olopatadine Eye Drop',
                'price' => '3000',
                'quantity' => 21,
                'category_id' => 5,
                'threshold' => '10',
                'expiry_date' => '2028-01-28',
                'is_active' => 1,
                'dispense_qty' => 0,
                'created_at' => '2025-06-14 09:10:40',
                'updated_at' => '2025-06-14 09:10:40',
                'store_id' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Antropine Eye Drop',
                'price' => '1500',
                'quantity' => 15,
                'category_id' => 6,
                'threshold' => '5',
                'expiry_date' => '2027-12-30',
                'is_active' => 1,
                'dispense_qty' => 0,
                'created_at' => '2025-06-14 09:11:37',
                'updated_at' => '2025-06-14 09:11:37',
                'store_id' => 2,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Betoptic Eye Drop',
                'price' => '4000',
                'quantity' => 16,
                'category_id' => 4,
                'threshold' => '10',
                'expiry_date' => '2027-02-28',
                'is_active' => 1,
                'dispense_qty' => 0,
                'created_at' => '2025-06-14 09:12:36',
                'updated_at' => '2025-06-14 09:12:36',
                'store_id' => 2,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Chloramphenicol Eye Ointment',
                'price' => '1000',
                'quantity' => 6,
                'category_id' => 2,
                'threshold' => '2',
                'expiry_date' => '2028-02-28',
                'is_active' => 1,
                'dispense_qty' => 0,
                'created_at' => '2025-06-14 09:16:25',
                'updated_at' => '2025-06-14 09:16:25',
                'store_id' => 2,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Cipro Eye Drops',
                'price' => '1500',
                'quantity' => 46,
                'category_id' => 2,
                'threshold' => '20',
                'expiry_date' => '2025-08-09',
                'is_active' => 1,
                'dispense_qty' => 0,
                'created_at' => '2025-06-14 09:19:32',
                'updated_at' => '2025-06-24 15:46:43',
                'store_id' => 2,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Dexamethasone Eye Drop',
                'price' => '2000',
                'quantity' => 38,
                'category_id' => 8,
                'threshold' => '10',
                'expiry_date' => '2026-02-27',
                'is_active' => 1,
                'dispense_qty' => 0,
                'created_at' => '2025-06-14 09:23:02',
                'updated_at' => '2025-06-14 09:23:02',
                'store_id' => 2,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Dilcofenac Eye Drop',
                'price' => '2000',
                'quantity' => 30,
                'category_id' => 7,
                'threshold' => '10',
                'expiry_date' => '2027-02-08',
                'is_active' => 1,
                'dispense_qty' => 0,
                'created_at' => '2025-06-14 09:24:53',
                'updated_at' => '2025-06-14 09:24:53',
                'store_id' => 2,
            ),
        ));
        
        
    }
}