<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'firstname' => 'Admin',
                'lastname' => 'John',
                'phone' => NULL,
                'email' => 'admin@admin.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$dCCb0Im3w2FLTM7rE5GRyufRl49oZE9ZQq3oD4WLIkvNRqR17iS6.',
                'remember_token' => NULL,
                'created_at' => '2025-06-01 18:12:32',
                'updated_at' => '2025-06-01 18:12:32',
            ),
            1 => 
            array (
                'id' => 2,
                'firstname' => 'Chinyere',
                'lastname' => 'Sulaimon',
                'phone' => NULL,
                'email' => 'delizabeth@example.net',
                'email_verified_at' => '2025-06-01 18:12:39',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'remember_token' => 'ZvmFmy7b54',
                'created_at' => '2025-06-01 18:12:39',
                'updated_at' => '2025-06-01 18:12:39',
            ),
            2 => 
            array (
                'id' => 3,
                'firstname' => 'Chukwu',
                'lastname' => 'Omolara',
                'phone' => NULL,
                'email' => 'temitope11@example.com',
                'email_verified_at' => '2025-06-01 18:12:40',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'remember_token' => 'IYn2rH9laY',
                'created_at' => '2025-06-01 18:12:40',
                'updated_at' => '2025-06-01 18:12:40',
            ),
            3 => 
            array (
                'id' => 4,
                'firstname' => 'Lola',
                'lastname' => 'Ogunwande',
                'phone' => NULL,
                'email' => 'efe.gbogboade@example.com',
                'email_verified_at' => '2025-06-01 18:12:40',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'remember_token' => 'WoxTZ5MOgx',
                'created_at' => '2025-06-01 18:12:40',
                'updated_at' => '2025-06-01 18:12:40',
            ),
            4 => 
            array (
                'id' => 5,
                'firstname' => 'Uzodimma',
                'lastname' => 'Oladimeji',
                'phone' => NULL,
                'email' => 'chizoba26@example.com',
                'email_verified_at' => '2025-06-01 18:12:40',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'remember_token' => 'qKnJArCAPg',
                'created_at' => '2025-06-01 18:12:40',
                'updated_at' => '2025-06-01 18:12:40',
            ),
            5 => 
            array (
                'id' => 6,
                'firstname' => 'Onome',
                'lastname' => 'Okunola',
                'phone' => NULL,
                'email' => 'olaide.babalola@example.com',
                'email_verified_at' => '2025-06-01 18:12:40',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'remember_token' => 'DMWYmhWPOK',
                'created_at' => '2025-06-01 18:12:40',
                'updated_at' => '2025-06-01 18:12:40',
            ),
            6 => 
            array (
                'id' => 7,
                'firstname' => 'Tope',
                'lastname' => 'Kimberly',
                'phone' => NULL,
                'email' => 'adeboye.balogun@example.com',
                'email_verified_at' => '2025-06-01 18:12:41',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'remember_token' => 'a63Bhynrnw',
                'created_at' => '2025-06-01 18:12:41',
                'updated_at' => '2025-06-01 18:12:41',
            ),
            7 => 
            array (
                'id' => 8,
                'firstname' => 'Zainab',
                'lastname' => 'Akeem-omosanya',
                'phone' => NULL,
                'email' => 'byussuf@example.com',
                'email_verified_at' => '2025-06-01 18:12:41',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'remember_token' => 'H1Wpa7AKsc',
                'created_at' => '2025-06-01 18:12:41',
                'updated_at' => '2025-06-01 18:12:41',
            ),
            8 => 
            array (
                'id' => 9,
                'firstname' => 'Ayebatari',
                'lastname' => 'Leonard',
                'phone' => NULL,
                'email' => 'temmanuel@example.com',
                'email_verified_at' => '2025-06-01 18:12:41',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'remember_token' => 'vapDx0QJ8B',
                'created_at' => '2025-06-01 18:12:41',
                'updated_at' => '2025-06-01 18:12:41',
            ),
            9 => 
            array (
                'id' => 10,
                'firstname' => 'Mohammed',
                'lastname' => 'Sekinat',
                'phone' => NULL,
                'email' => 'habiodun@example.net',
                'email_verified_at' => '2025-06-01 18:12:41',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'remember_token' => '54EXXQzJmS',
                'created_at' => '2025-06-01 18:12:41',
                'updated_at' => '2025-06-01 18:12:41',
            ),
            10 => 
            array (
                'id' => 11,
                'firstname' => 'Danjuma',
                'lastname' => 'Nojeem',
                'phone' => NULL,
                'email' => 'rasheedah28@example.net',
                'email_verified_at' => '2025-06-01 18:12:41',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'remember_token' => 'VHrFDYoBgu',
                'created_at' => '2025-06-01 18:12:41',
                'updated_at' => '2025-06-01 18:12:41',
            ),
            11 => 
            array (
                'id' => 12,
                'firstname' => 'douglas',
                'lastname' => 'isaac',
                'phone' => '09031234567',
                'email' => 'no-email-6847eb6416da8@example.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$dmx9RWzG1SmZg2X1SGt9gexlqAOpN1bg4CrgqcyuDfEAPCsz4KyKq',
                'remember_token' => NULL,
                'created_at' => '2025-06-10 08:23:00',
                'updated_at' => '2025-06-14 14:56:27',
            ),
            12 => 
            array (
                'id' => 13,
                'firstname' => 'Tennyson',
                'lastname' => 'David',
                'phone' => '09031234567',
                'email' => 'no-email-684abbaae7e7d@example.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$KXfj8Xxx7CM7n4pwFs96S.CiyKYSSKglYG/M5AGfz2sv.b7FWcvBG',
                'remember_token' => NULL,
                'created_at' => '2025-06-12 11:36:11',
                'updated_at' => '2025-06-14 10:37:28',
            ),
            13 => 
            array (
                'id' => 14,
                'firstname' => 'Suleiman',
                'lastname' => 'Umar',
                'phone' => '08165989386',
                'email' => 'suleimanxavieeh19@gmail.com',
                'email_verified_at' => '2025-06-14 10:02:23',
                'password' => '$2y$10$ZqR5FphSNDFVQm16dGqUSObevkfdeWyBrEwEbAyCKJ40iR0w/HJ22',
                'remember_token' => NULL,
                'created_at' => '2025-06-14 10:02:23',
                'updated_at' => '2025-06-14 10:02:23',
            ),
            14 => 
            array (
                'id' => 15,
                'firstname' => 'OPHTHALCARE',
                'lastname' => 'TEST',
                'phone' => '08165989386',
                'email' => 'no-email-685ac512da7f0@example.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$aBuTlYcAR..lBrqXlejoMeXcC8.9uFhTShVdDlQdh7K.2mqbsnpT6',
                'remember_token' => NULL,
                'created_at' => '2025-06-24 15:32:35',
                'updated_at' => '2025-06-24 15:32:35',
            ),
        ));
        
        
    }
}