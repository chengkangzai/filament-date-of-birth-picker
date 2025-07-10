<?php

namespace CCK\FilamentDateOfBirthPicker;

use Filament\Forms\Components\Field;
use Illuminate\Support\Carbon;

class DateOfBirthPicker extends Field
{
    protected string $view = 'filament-date-of-birth-picker::date-of-birth-picker';

    protected int $startYear = 0;

    protected int $endYear = 0;

    protected ?array $monthLabels = null;

    protected ?string $minDate = null;

    protected ?string $maxDate = null;

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
        return $this->startYear ?: config('date-of-birth-picker.default_start_year', 1920);
    }

    public function getEndYear(): int
    {
        return $this->endYear ?: config('date-of-birth-picker.default_end_year') ?: (int) date('Y');
    }

    public function monthLabels(array $labels): static
    {
        $this->monthLabels = $labels;

        return $this;
    }

    public function getMonthLabels(): array
    {
        if ($this->monthLabels) {
            return $this->monthLabels;
        }

        $configLabels = config('date-of-birth-picker.localization.month_labels');
        if ($configLabels && count($configLabels) === 12) {
            return $configLabels;
        }

        // Default English labels
        return [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December',
        ];
    }

    public function minDate(string $date): static
    {
        $this->minDate = $date;

        return $this;
    }

    public function maxDate(string $date): static
    {
        $this->maxDate = $date;

        return $this;
    }

    public function getMinDate(): ?string
    {
        return $this->minDate ?: config('date-of-birth-picker.constraints.min_date');
    }

    public function getMaxDate(): ?string
    {
        if ($this->maxDate) {
            return $this->maxDate;
        }

        $configMaxDate = config('date-of-birth-picker.constraints.max_date');
        if ($configMaxDate) {
            return $configMaxDate;
        }

        // Default to today if future dates are disabled
        if (! config('date-of-birth-picker.constraints.enable_future_dates', false)) {
            return date('Y-m-d');
        }

        return null;
    }

    public function getState(): mixed
    {
        $state = parent::getState();

        // If state is already a structured array, convert to date string
        if (is_array($state) && isset($state['year'], $state['month'], $state['day'])) {
            return $this->convertComponentPartsToDateString($state);
        }

        if (is_string($state)) {
            return $this->normalizeStringDate($state);
        }

        if ($state instanceof Carbon) {
            return $state->format('Y-m-d');
        }

        return $state;
    }

    public function getChildComponentStatePaths(): array
    {
        return [
            'day' => $this->getStatePath() . '.day',
            'month' => $this->getStatePath() . '.month',
            'year' => $this->getStatePath() . '.year',
        ];
    }

    public function getChildComponentState(): array
    {
        $dateString = $this->getState();

        return $this->parseDateStringToParts($dateString);
    }

    public function hydrateState(?array &$hydratedDefaultState, bool $andCallHydrationHooks = true): void
    {
        parent::hydrateState($hydratedDefaultState, $andCallHydrationHooks);

        if (! $hydratedDefaultState) {
            return;
        }

        $statePath = $this->getStatePath();

        // Check if we have nested component state
        if (isset($hydratedDefaultState[$statePath]) && is_array($hydratedDefaultState[$statePath])) {
            $componentState = $hydratedDefaultState[$statePath];

            if (isset($componentState['year'], $componentState['month'], $componentState['day'])) {
                $year = (int) $componentState['year'];
                $month = (int) $componentState['month'];
                $day = (int) $componentState['day'];

                if ($year && $month && $day) {
                    try {
                        $date = Carbon::createFromDate($year, $month, $day);
                        $hydratedDefaultState[$statePath] = $date->format('Y-m-d');
                    } catch (\Exception $e) {
                        $hydratedDefaultState[$statePath] = null;
                    }
                } else {
                    $hydratedDefaultState[$statePath] = null;
                }
            }
        }
    }

    public function dehydrateState(array &$state, bool $isDehydrated = true): void
    {
        $statePath = $this->getStatePath();
        $currentState = $state[$statePath] ?? null;

        // Convert date string to component structure for frontend
        if (is_string($currentState) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $currentState)) {
            $parts = explode('-', $currentState);
            $state[$statePath] = [
                'year' => (int) $parts[0],
                'month' => (int) $parts[1],
                'day' => (int) $parts[2],
            ];
        } elseif (! is_array($currentState)) {
            $state[$statePath] = ['year' => null, 'month' => null, 'day' => null];
        }
    }

    public function formatState($state): array
    {
        return $this->parseDateStringToParts($state);
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Apply default validation rules from config
        $defaultRules = config('date-of-birth-picker.default_validation_rules', ['date', 'before_or_equal:today']);
        $this->rules($defaultRules);
    }

    public static function make(string $name): static
    {
        return new static($name);
    }

    /**
     * Convert component parts (year, month, day) to a date string
     */
    private function convertComponentPartsToDateString(array $parts): ?string
    {
        $year = (int) ($parts['year'] ?? 0);
        $month = (int) ($parts['month'] ?? 0);
        $day = (int) ($parts['day'] ?? 0);

        if ($year && $month && $day) {
            try {
                $date = Carbon::createFromDate($year, $month, $day);

                return $date->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }

    /**
     * Normalize a string date to Y-m-d format
     */
    private function normalizeStringDate(string $dateString): ?string
    {
        try {
            return Carbon::parse($dateString)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Parse a date string into component parts
     */
    private function parseDateStringToParts($dateString): array
    {
        if (is_string($dateString) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateString)) {
            $parts = explode('-', $dateString);

            return [
                'year' => (int) $parts[0],
                'month' => (int) $parts[1],
                'day' => (int) $parts[2],
            ];
        }

        return ['year' => null, 'month' => null, 'day' => null];
    }
}
