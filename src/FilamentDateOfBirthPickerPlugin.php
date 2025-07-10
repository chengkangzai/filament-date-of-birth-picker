<?php

namespace CCK\FilamentDateOfBirthPicker;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentDateOfBirthPickerPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-date-of-birth-picker';
    }

    public function register(Panel $panel): void
    {
        // The component is automatically registered through the service provider
        // Additional panel-specific registration can be done here if needed
    }

    public function boot(Panel $panel): void
    {
        // Plugin is booted after all other plugins are registered
        // Any additional setup can be done here
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
