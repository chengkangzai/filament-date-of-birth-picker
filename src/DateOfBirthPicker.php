<?php

namespace CCK\FilamentDateOfBirthPicker;

use Filament\Forms\Components\Field;
use Illuminate\Support\Carbon;

class DateOfBirthPicker extends Field
{
    protected string $view = 'filament-date-of-birth-picker::date-of-birth-picker';

    protected int $startYear = 1920;

    protected int $endYear = 0;

    public function startYear(int $year): static
    {
        $this->startYear = $year;

        return $this;
    }

    public function endYear(int $year): static
    {
        $this->endYear = $year;

        return $this;
    }

    public function getStartYear(): int
    {
        return $this->startYear;
    }

    public function getEndYear(): int
    {
        return $this->endYear ?: (int) date('Y');
    }

    public function getState(): mixed
    {
        $state = parent::getState();

        if (is_string($state)) {
            try {
                return Carbon::parse($state)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        if ($state instanceof Carbon) {
            return $state->format('Y-m-d');
        }

        return $state;
    }

    public function dehydrateState(array &$state, bool $isDehydrated = true): void
    {
        $currentState = $this->getState();

        if (is_string($currentState) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $currentState)) {
            $state[$this->getStatePath()] = $currentState;
        } else {
            $state[$this->getStatePath()] = null;
        }
    }

    public function formatState($state): array
    {
        if (is_string($state) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $state)) {
            $parts = explode('-', $state);

            return [
                'year' => (int) $parts[0],
                'month' => (int) $parts[1],
                'day' => (int) $parts[2],
            ];
        }

        return ['year' => null, 'month' => null, 'day' => null];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->endYear = (int) date('Y');
        $this->rules(['date', 'before_or_equal:today']);
    }

    public static function make(string $name): static
    {
        return new static($name);
    }
}
