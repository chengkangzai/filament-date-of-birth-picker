# Filament Date of Birth Picker

[![Latest Version on Packagist](https://img.shields.io/packagist/v/cck/filament-date-of-birth-picker.svg?style=flat-square)](https://packagist.org/packages/cck/filament-date-of-birth-picker)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/cck/filament-date-of-birth-picker/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/cck/filament-date-of-birth-picker/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/cck/filament-date-of-birth-picker/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/cck/filament-date-of-birth-picker/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/cck/filament-date-of-birth-picker.svg?style=flat-square)](https://packagist.org/packages/cck/filament-date-of-birth-picker)

A native dropdown picker for date of birth selection in Filament forms. This package provides three separate dropdown components for day, month, and year selection, making it easy for users to select their date of birth without using a calendar picker or manual input.

Features:
- Three separate dropdown components (day, month, year)
- Smart day calculation based on selected month and year
- Leap year handling for February
- Configurable year range
- Built-in validation
- Seamless integration with Filament forms

## Installation

You can install the package via composer:

```bash
composer require cck/filament-date-of-birth-picker
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-date-of-birth-picker-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-date-of-birth-picker-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-date-of-birth-picker-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

Add the DateOfBirthPicker to your Filament form:

```php
use CCK\FilamentDateOfBirthPicker\DateOfBirthPicker;

// In your Filament form
DateOfBirthPicker::make('date_of_birth')
    ->label('Date of Birth')
    ->required()
```

### Advanced Usage

You can customize the year range and date constraints:

```php
DateOfBirthPicker::make('date_of_birth')
    ->label('Date of Birth')
    ->startYear(1900)
    ->endYear(2010)
    ->minDate('1900-01-01')
    ->maxDate('2010-12-31')
    ->required()
```

### Internationalization

Customize month labels for different languages:

```php
DateOfBirthPicker::make('date_of_birth')
    ->monthLabels([
        'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
        'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
    ])
```

Or configure globally in the config file:

```php
// config/date-of-birth-picker.php
'localization' => [
    'month_labels' => [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ],
    'placeholder_labels' => [
        'day' => 'Día',
        'month' => 'Mes',
        'year' => 'Año',
    ],
    'validation_message' => 'Por favor seleccione una fecha válida.',
],
```

### Validation

The component includes built-in validation:
- Ensures the date is valid
- Checks that the date is not in the future (configurable)
- Handles leap years correctly
- Supports min/max date constraints

You can add additional validation rules:

```php
DateOfBirthPicker::make('date_of_birth')
    ->label('Date of Birth')
    ->rules(['required', 'date', 'before:2010-01-01'])
```

### Configuration

Publish the configuration file to customize default behavior:

```bash
php artisan vendor:publish --tag="filament-date-of-birth-picker-config"
```

Available configuration options:
- Default year range
- Date format
- Validation rules
- Internationalization settings
- Date constraints
- Accessibility features

### Performance Features

- **Cached Arrays**: Year and month options are cached for optimal performance
- **Smart Date Validation**: Client-side validation with leap year support
- **Optimized Assets**: Minified CSS and JavaScript with automatic purging

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [chengkangzai](https://github.com/chengkangzai)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
