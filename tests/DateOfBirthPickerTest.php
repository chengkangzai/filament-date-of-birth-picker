<?php

namespace CCK\FilamentDateOfBirthPicker\Tests;

use CCK\FilamentDateOfBirthPicker\DateOfBirthPicker;

class DateOfBirthPickerTest extends TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $field = DateOfBirthPicker::make('date_of_birth');

        $this->assertInstanceOf(DateOfBirthPicker::class, $field);
        $this->assertEquals('date_of_birth', $field->getName());
    }

    /** @test */
    public function it_has_default_year_range()
    {
        $field = DateOfBirthPicker::make('date_of_birth');

        $this->assertEquals(1920, $field->getStartYear());
        $this->assertEquals((int) date('Y'), $field->getEndYear());
    }

    /** @test */
    public function it_can_set_custom_year_range()
    {
        $field = DateOfBirthPicker::make('date_of_birth')
            ->startYear(1900)
            ->endYear(2010);

        $this->assertEquals(1900, $field->getStartYear());
        $this->assertEquals(2010, $field->getEndYear());
    }

    /** @test */
    public function it_formats_state_into_parts()
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
    public function it_returns_null_parts_for_invalid_state()
    {
        $field = DateOfBirthPicker::make('date_of_birth');

        $parts = $field->formatState('invalid');

        $this->assertEquals([
            'year' => null,
            'month' => null,
            'day' => null,
        ], $parts);
    }
}
