<?php

namespace CCK\FilamentDateOfBirthPicker\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CCK\FilamentDateOfBirthPicker\FilamentDateOfBirthPicker
 */
class FilamentDateOfBirthPicker extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \CCK\FilamentDateOfBirthPicker\FilamentDateOfBirthPicker::class;
    }
}
