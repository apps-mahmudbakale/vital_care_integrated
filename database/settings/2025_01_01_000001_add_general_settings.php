<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('system.system_name', 'Vital Care');
        $this->migrator->add('system.system_logo', null);
        $this->migrator->add('system.system_address', '123 Health Street');
        $this->migrator->add('system.hospital_number_prefix', 'EMR');
        $this->migrator->add('system.auto_checkin', false);
        $this->migrator->add('system.insurance_billers', false);
    }
};
