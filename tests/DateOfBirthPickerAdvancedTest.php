<?php

namespace CCK\FilamentDateOfBirthPicker\Tests;

use CCK\FilamentDateOfBirthPicker\DateOfBirthPicker;

class DateOfBirthPickerAdvancedTest extends TestCase
{
    /** @test */
    public function it_can_set_custom_month_labels()
    {
        $customLabels = [
            'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre',
        ];

        $field = DateOfBirthPicker::make('date_of_birth')
            ->monthLabels($customLabels);

        $this->assertEquals($customLabels, $field->getMonthLabels());
    }

    /** @test */
    public function it_uses_config_month_labels_when_available()
    {
        config(['date-of-birth-picker.localization.month_labels' => [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
        ]]);

        $field = DateOfBirthPicker::make('date_of_birth');

        $this->assertEquals([
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
        ], $field->getMonthLabels());
    }

    /** @test */
    public function it_falls_back_to_english_labels_when_config_invalid()
    {
        config(['date-of-birth-picker.localization.month_labels' => ['Only', 'Two']]);

        $field = DateOfBirthPicker::make('date_of_birth');

        $labels = $field->getMonthLabels();
        $this->assertCount(12, $labels);
        $this->assertEquals('January', $labels[0]);
    }

    /** @test */
    public function it_can_set_min_and_max_dates()
    {
        $field = DateOfBirthPicker::make('date_of_birth')
            ->minDate('1900-01-01')
            ->maxDate('2000-12-31');

        $this->assertEquals('1900-01-01', $field->getMinDate());
        $this->assertEquals('2000-12-31', $field->getMaxDate());
    }

    /** @test */
    public function it_uses_config_date_constraints()
    {
        config([
            'date-of-birth-picker.constraints.min_date' => '1950-01-01',
            'date-of-birth-picker.constraints.max_date' => '2010-12-31',
        ]);

        $field = DateOfBirthPicker::make('date_of_birth');

        $this->assertEquals('1950-01-01', $field->getMinDate());
        $this->assertEquals('2010-12-31', $field->getMaxDate());
    }

    /** @test */
    public function it_defaults_max_date_to_today_when_future_dates_disabled()
    {
        config([
            'date-of-birth-picker.constraints.enable_future_dates' => false,
            'date-of-birth-picker.constraints.max_date' => null,
        ]);

        $field = DateOfBirthPicker::make('date_of_birth');

        $this->assertEquals(date('Y-m-d'), $field->getMaxDate());
    }

    /** @test */
    public function it_parses_date_string_to_parts()
    {
        $field = DateOfBirthPicker::make('date_of_birth');

        $parts = $field->formatState('1990-05-15');

        $this->assertEquals([
            'year' => 1990,
            'month' => 5,
            'day' => 15,
        ], $parts);
    }

    /** @test */
    public function it_handles_invalid_date_string_parts()
    {
        $field = DateOfBirthPicker::make('date_of_birth');

        $parts = $field->formatState('invalid-date');

        $this->assertEquals([
            'year' => null,
            'month' => null,
            'day' => null,
        ], $parts);
    }

    /** @test */
    public function it_uses_helper_methods_for_date_conversion()
    {
        $field = DateOfBirthPicker::make('date_of_birth');

        // Test the helper methods directly through public methods
        $this->assertEquals([
            'year' => 2020,
            'month' => 2,
            'day' => 29,
        ], $field->formatState('2020-02-29'));

        $this->assertEquals([
            'year' => null,
            'month' => null,
            'day' => null,
        ], $field->formatState(null));
    }
}
