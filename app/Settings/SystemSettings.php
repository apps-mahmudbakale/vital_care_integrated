<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SystemSettings extends Settings
{
    public bool $show_laboratory;
    public bool $show_pharmacy;
    public bool $show_radiology;
    public bool $show_billing;
    public bool $show_reports;
    public bool $show_admission;
    public bool $show_appointments;
    public bool $show_patients;
    public bool $show_opticals;
    
    public ?string $system_email;
    public ?string $system_phone;

    public string $system_name;
    public ?string $system_logo;
    public string $system_address;
    public string $hospital_number_prefix;
    public bool $auto_checkin;
    public bool $insurance_billers;
    public string $clinic_type;

    public static function group(): string
    {
        return 'system';
    }
}
