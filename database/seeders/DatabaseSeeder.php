<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Religion;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {

//    $this->call(StateSeeder::class);

    $this->call(UsersSeeder::class);
    $this->call(RolesAndPermissionsSeeder::class);
    $this->call(PatientSeeder::class);
//    $this->call(CashPointsTableSeeder::class);
//    $this->call(DepartmentsTableSeeder::class);
//    $this->call(DrugsTableSeeder::class);
//    $this->call(StaffSeeder::class);

    $religions = ['Islam', 'Christianity', 'Others'];

    foreach ($religions as $religion) {
      Religion::create(['name' => $religion]);
//        $this->call(UsersTableSeeder::class);
//        $this->call(PatientsTableSeeder::class);
//        $this->call(CashPointsTableSeeder::class);
//        $this->call(DepartmentsTableSeeder::class);
//        $this->call(DrugsTableSeeder::class);
    }
  }
}
