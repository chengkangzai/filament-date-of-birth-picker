<?php

// config for CCK/FilamentDateOfBirthPicker
return [
    /*
    |--------------------------------------------------------------------------
    | Default Year Range
    |--------------------------------------------------------------------------
    |
    | Configure the default year range for the date of birth picker.
    | These values can be overridden on individual field instances.
    |
    */
    'default_start_year' => 1920,
    'default_end_year' => null, // null means current year

    /*
    |--------------------------------------------------------------------------
    | Date Format
    |--------------------------------------------------------------------------
    |
    | The internal date format used by the component. This should typically
    | remain as 'Y-m-d' to ensure compatibility with database storage.
    |
    */
    'date_format' => 'Y-m-d',

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    |
    | Default validation rules applied to all date of birth picker fields.
    | These can be overridden on individual field instances.
    |
    */
    'default_validation_rules' => [
        'date',
        'before_or_equal:today',
    ],

    /*
    |--------------------------------------------------------------------------
    | Accessibility
    |--------------------------------------------------------------------------
    |
    | Configuration for accessibility features.
    |
    */
    'accessibility' => [
        'enable_screen_reader_announcements' => true,
        'enable_keyboard_navigation' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | UI Preferences
    |--------------------------------------------------------------------------
    |
    | Configure the user interface behavior and appearance.
    |
    */
    'ui' => [
        'show_validation_messages' => true,
        'enable_live_validation' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Internationalization
    |--------------------------------------------------------------------------
    |
    | Configure month labels and other localized content.
    |
    */
    'localization' => [
        'month_labels' => null, // null = use default English, or provide array of 12 month names
        'placeholder_labels' => [
            'day' => 'Day',
            'month' => 'Month',
            'year' => 'Year',
        ],
        'validation_message' => 'Please select a valid date.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Date Constraints
    |--------------------------------------------------------------------------
    |
    | Configure minimum and maximum date constraints.
    |
    */
    'constraints' => [
        'min_date' => null, // Y-m-d format or null
        'max_date' => null, // Y-m-d format or null, defaults to today
        'enable_future_dates' => false,
    ],
];
