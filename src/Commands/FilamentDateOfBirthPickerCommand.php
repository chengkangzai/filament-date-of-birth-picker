<?php

namespace CCK\FilamentDateOfBirthPicker\Commands;

use Illuminate\Console\Command;

class FilamentDateOfBirthPickerCommand extends Command
{
    public $signature = 'filament-date-of-birth-picker';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
