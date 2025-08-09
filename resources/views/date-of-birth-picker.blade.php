@php
    $statePath = $getStatePath();
    $childState = $getChildComponentState();
    $startYear = $getStartYear();
    $endYear = $getEndYear();
    $monthLabels = $getMonthLabels();
    $minDate = $getMinDate();
    $maxDate = $getMaxDate();
    $placeholders = config('date-of-birth-picker.localization.placeholder_labels', [
        'day' => 'Day',
        'month' => 'Month',
        'year' => 'Year'
    ]);
    $validationMessage = config('date-of-birth-picker.localization.validation_message', 'Please select a valid date.');
    
    // Prepare month labels for JavaScript
    $monthLabelsForJs = array_map(function($label, $index) {
        return ['value' => $index + 1, 'label' => $label];
    }, $monthLabels, array_keys($monthLabels));
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div 
        x-data="dateOfBirthPicker()"
        x-init="
            day = {{ $childState['day'] ?? 'null' }};
            month = {{ $childState['month'] ?? 'null' }};
            year = {{ $childState['year'] ?? 'null' }};
            startYear = {{ $startYear }};
            endYear = {{ $endYear }};
            statePath = '{{ $statePath }}';
            monthLabels = @json($monthLabelsForJs);
            minDate = {{ $minDate ? "'" . $minDate . "'" : 'null' }};
            maxDate = {{ $maxDate ? "'" . $maxDate . "'" : 'null' }};
        "
        class="flex gap-2"
        role="group"
        aria-labelledby="{{ $getId() }}-label"
    >
        <!-- Day Dropdown -->
        <div class="flex-1">
            <label 
                for="{{ $getId() }}-day"
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
            >
                {{ $placeholders['day'] }}
            </label>
            <select 
                id="{{ $getId() }}-day"
                x-model="day"
                wire:model.live="{{ $statePath }}.day"
                @keydown="handleKeyDown($event, 'day')"
                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:text-white"
                aria-label="Day of birth"
                aria-describedby="{{ $getId() }}-day-help"
                :aria-invalid="!isValidDate() && (day || month || year) ? 'true' : 'false'"
            >
                <option value="">{{ $placeholders['day'] }}</option>
                <template x-for="dayOption in getDays()" :key="dayOption">
                    <option 
                        :value="dayOption" 
                        x-text="dayOption"
                        :selected="day == dayOption"
                    ></option>
                </template>
            </select>
            <div id="{{ $getId() }}-day-help" class="sr-only">
                Select the day of your birth date
            </div>
        </div>

        <!-- Month Dropdown -->
        <div class="flex-1">
            <label 
                for="{{ $getId() }}-month"
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
            >
                {{ $placeholders['month'] }}
            </label>
            <select 
                id="{{ $getId() }}-month"
                x-model="month"
                wire:model.live="{{ $statePath }}.month"
                @keydown="handleKeyDown($event, 'month')"
                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:text-white"
                aria-label="Month of birth"
                aria-describedby="{{ $getId() }}-month-help"
                :aria-invalid="!isValidDate() && (day || month || year) ? 'true' : 'false'"
            >
                <option value="">{{ $placeholders['month'] }}</option>
                <template x-for="monthOption in getMonths()" :key="monthOption.value">
                    <option 
                        :value="monthOption.value" 
                        x-text="monthOption.label"
                        :selected="month == monthOption.value"
                    ></option>
                </template>
            </select>
            <div id="{{ $getId() }}-month-help" class="sr-only">
                Select the month of your birth date
            </div>
        </div>

        <!-- Year Dropdown -->
        <div class="flex-1">
            <label 
                for="{{ $getId() }}-year"
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
            >
                {{ $placeholders['year'] }}
            </label>
            <select 
                id="{{ $getId() }}-year"
                x-model="year"
                wire:model.live="{{ $statePath }}.year"
                @keydown="handleKeyDown($event, 'year')"
                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:text-white"
                aria-label="Year of birth"
                aria-describedby="{{ $getId() }}-year-help"
                :aria-invalid="!isValidDate() && (day || month || year) ? 'true' : 'false'"
            >
                <option value="">{{ $placeholders['year'] }}</option>
                <template x-for="yearOption in getYears()" :key="yearOption">
                    <option 
                        :value="yearOption" 
                        x-text="yearOption"
                        :selected="year == yearOption"
                    ></option>
                </template>
            </select>
            <div id="{{ $getId() }}-year-help" class="sr-only">
                Select the year of your birth date
            </div>
        </div>
    </div>

    <!-- Validation Message -->
    <div 
        x-show="!isValidDate() && day && month && year"
        x-transition
        class="mt-1 text-sm text-red-600 dark:text-red-400"
        role="alert"
        aria-live="polite"
    >
        {{ $validationMessage }}
    </div>

    <!-- Screen Reader Status -->
    <div class="sr-only" aria-live="polite" aria-atomic="true">
        <span x-show="isValidDate() && day && month && year">
            Selected date: <span x-text="getMonths().find(m => m.value == month) ? `${getMonths().find(m => m.value == month).label} ${day}, ${year}` : ''"></span>
        </span>
    </div>
</x-dynamic-component>