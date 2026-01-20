<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('system.show_laboratory', true);
        $this->migrator->add('system.show_pharmacy', true);
        $this->migrator->add('system.show_radiology', true);
        $this->migrator->add('system.show_billing', true);
        $this->migrator->add('system.show_reports', true);
        $this->migrator->add('system.show_admission', true);
        $this->migrator->add('system.show_appointments', true);
        $this->migrator->add('system.show_patients', true);
        $this->migrator->add('system.show_opticals', true);
    }
};
