<?php

namespace Database\Seeders;

use App\Models\LabParameter;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LabParameterSeeder extends Seeder
{


public function run()
{
    $parameters = [
        'Hemoglobin', 'WBC', 'Platelets', 'Creatinine',
        'Urea', 'Potassium', 'Sodium'
    ];

    foreach ($parameters as $p) {
        LabParameter::create(['name' => $p]);
    }
}

}
