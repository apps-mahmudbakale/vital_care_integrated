<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\User;
use App\Models\Patient;
use App\Models\NextOfKin;
use Illuminate\Database\Seeder;
use sirajcse\UniqueIdGenerator\UniqueIdGenerator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PatientSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    for ($i = 0; $i < 10; $i++) {
      $userData = User::factory()->create();

      $userData->assignRole('patient');

      $hospital_no = UniqueIdGenerator::generate(['table' => 'patients', 'length' => 4]);
      $faker = Factory::create('en_NG');
      $gender = $faker->randomElement(['Male', 'Female']);
      $status = $faker->randomElement(['single', 'married', 'divorced']);
      $tribes = ['Hausa', 'Yoruba', 'Igbo', 'Fulani', 'Ijaw', 'Kanuri', 'Ibibio', 'Tiv']; // Add more tribes as needed

      $patient = Patient::create([
        'hospital_no' => $hospital_no,
        'user_id' => $userData->id,
        'middlename' => $faker->firstName($gender),
        'phone' => $faker->phoneNumber(),
        'date_of_birth' => $faker->dateTimeBetween('1990-01-01', '2024-04-31')->format('Y-m-d'),
        'gender' => $gender,
        'religion_id' => $faker->numberBetween(0, 1),
        'occupation' => $faker->jobTitle(),
        'marital_status' => $status,
        'state_of_residence' => $faker->county(),
        'lga_of_residence' => $faker->region(),
        'state_of_origin' => $faker->county(),
        'lga_of_origin' => $faker->region(),
        'residential_address' => $faker->streetAddress(),
        'tribe' => $faker->randomElement($tribes), // Add tribe here
      ]);


//      $nextOfKinData = NextOfKin::factory()->make()->toArray();
//      $next_of_kin = $patient->nextOfKin()->create($nextOfKinData);
    }
  }
}
