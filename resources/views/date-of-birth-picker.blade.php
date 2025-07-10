@php
    $statePath = $getStatePath();
    $currentDate = $getState();
    $currentDay = null;
    $currentMonth = null;
    $currentYear = null;
    
    if ($currentDate) {
        $dateParts = explode('-', $currentDate);
        if (count($dateParts) === 3) {
            $currentYear = (int) $dateParts[0];
            $currentMonth = (int) $dateParts[1];
            $currentDay = (int) $dateParts[2];
        }
    }
    
    $startYear = $getStartYear();
    $endYear = $getEndYear();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="flex gap-2">
        <!-- Day Dropdown -->
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Day
            </label>
            <select 
                wire:model.live="{{ $statePath }}.day"
                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:text-white"
                data-dob-day
            >
                <option value="">Day</option>
                @for ($day = 1; $day <= 31; $day++)
                    <option value="{{ $day }}" @selected($currentDay === $day)>
                        {{ $day }}
                    </option>
                @endfor
            </select>
        </div>

        <!-- Month Dropdown -->
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Month
            </label>
            <select 
                wire:model.live="{{ $statePath }}.month"
                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:text-white"
                data-dob-month
            >
                <option value="">Month</option>
                @foreach([
                    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                ] as $monthNum => $monthName)
                    <option value="{{ $monthNum }}" @selected($currentMonth === $monthNum)>
                        {{ $monthName }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Year Dropdown -->
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Year
            </label>
            <select 
                wire:model.live="{{ $statePath }}.year"
                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:text-white"
                data-dob-year
            >
                <option value="">Year</option>
                @for ($year = $endYear; $year >= $startYear; $year--)
                    <option value="{{ $year }}" @selected($currentYear === $year)>
                        {{ $year }}
                    </option>
                @endfor
            </select>
        </div>
    </div>

    <input type="hidden" wire:model="{{ $statePath }}" data-dob-hidden />
</x-dynamic-component>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const daySelect = document.querySelector('[data-dob-day]');
    const monthSelect = document.querySelector('[data-dob-month]');
    const yearSelect = document.querySelector('[data-dob-year]');
    const hiddenInput = document.querySelector('[data-dob-hidden]');

    function updateDays() {
        const month = parseInt(monthSelect.value);
        const year = parseInt(yearSelect.value);
        const currentDay = parseInt(daySelect.value);
        
        if (!month || !year) return;
        
        const daysInMonth = new Date(year, month, 0).getDate();
        
        // Clear existing options
        daySelect.innerHTML = '<option value="">Day</option>';
        
        // Add valid days
        for (let day = 1; day <= daysInMonth; day++) {
            const option = document.createElement('option');
            option.value = day;
            option.textContent = day;
            if (day === currentDay && day <= daysInMonth) {
                option.selected = true;
            }
            daySelect.appendChild(option);
        }
        
        updateHiddenInput();
    }

    function updateHiddenInput() {
        const day = daySelect.value;
        const month = monthSelect.value;
        const year = yearSelect.value;
        
        if (day && month && year) {
            const formattedDate = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
            hiddenInput.value = formattedDate;
            hiddenInput.dispatchEvent(new Event('input'));
        } else {
            hiddenInput.value = '';
            hiddenInput.dispatchEvent(new Event('input'));
        }
    }

    daySelect.addEventListener('change', updateHiddenInput);
    monthSelect.addEventListener('change', function() {
        updateDays();
        updateHiddenInput();
    });
    yearSelect.addEventListener('change', function() {
        updateDays();
        updateHiddenInput();
    });
});
</script>