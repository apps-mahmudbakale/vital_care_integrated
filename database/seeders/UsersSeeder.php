<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            User::create(
                [
                'firstname' => 'Admin',
                'lastname' => 'John',
                'email' => 'admin@admin.com',
                'password' => bcrypt('secret'),
            ]
        );
        // for ($i = 0; $i <= 10; $i++) {
        //     $user = User::create([
        //         'firstname' => fake()->name(),
        //         'lastname' => fake()->name(),
        //         'phone' => fake()->phoneNumber(),
        //         'email' => fake()->unique()->safeEmail(),
        //         'email_verified_at' => now(),
        //         'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //         'remember_token' => Str::random(10),
        //     ]);
        //     $userRole = User::find($user->id)->assignRole('manager');
        // }
    }
}
