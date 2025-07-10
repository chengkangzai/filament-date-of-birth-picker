// Date of Birth Picker Component for Filament
document.addEventListener('alpine:init', () => {
    Alpine.data('dateOfBirthPicker', () => ({
        day: null,
        month: null,
        year: null,
        startYear: 1920,
        endYear: new Date().getFullYear(),
        statePath: null,
        
        // Cached arrays for performance
        _cachedYears: null,
        _cachedMonths: null,
        
        // Configuration from backend
        monthLabels: null,
        minDate: null,
        maxDate: null,
        
        init() {
            // Cache years and months arrays
            this._cacheYears();
            this._cacheMonths();
            
            // Watch for changes and update wire model
            this.$watch('day', () => this.updateWireModel());
            this.$watch('month', () => {
                this.updateDaysInMonth();
                this.updateWireModel();
            });
            this.$watch('year', () => {
                this.updateDaysInMonth();
                this.updateWireModel();
            });
            
            // Re-cache years if year range changes
            this.$watch('startYear', () => this._cacheYears());
            this.$watch('endYear', () => this._cacheYears());
        },
        
        _cacheYears() {
            this._cachedYears = [];
            for (let year = this.endYear; year >= this.startYear; year--) {
                this._cachedYears.push(year);
            }
        },
        
        _cacheMonths() {
            // Use custom month labels if provided, otherwise default English
            if (this.monthLabels && Array.isArray(this.monthLabels)) {
                this._cachedMonths = this.monthLabels;
            } else {
                this._cachedMonths = [
                    { value: 1, label: 'January' },
                    { value: 2, label: 'February' },
                    { value: 3, label: 'March' },
                    { value: 4, label: 'April' },
                    { value: 5, label: 'May' },
                    { value: 6, label: 'June' },
                    { value: 7, label: 'July' },
                    { value: 8, label: 'August' },
                    { value: 9, label: 'September' },
                    { value: 10, label: 'October' },
                    { value: 11, label: 'November' },
                    { value: 12, label: 'December' }
                ];
            }
        },
        
        updateWireModel() {
            if (this.$wire && this.statePath) {
                this.$wire.set(this.statePath, {
                    day: this.day,
                    month: this.month,
                    year: this.year
                });
            }
        },
        
        updateDaysInMonth() {
            if (!this.month || !this.year) return;
            
            const daysInMonth = new Date(this.year, this.month, 0).getDate();
            
            // If current day is invalid for new month/year, reset it
            if (this.day > daysInMonth) {
                this.day = null;
            }
        },
        
        getDaysInMonth() {
            if (!this.month || !this.year) return 31;
            return new Date(this.year, this.month, 0).getDate();
        },
        
        isValidDate() {
            if (!this.day || !this.month || !this.year) return false;
            
            try {
                // Add parseInt for type safety
                const yearInt = parseInt(this.year, 10);
                const monthInt = parseInt(this.month, 10);
                const dayInt = parseInt(this.day, 10);
                
                // Check for NaN values
                if (isNaN(yearInt) || isNaN(monthInt) || isNaN(dayInt)) return false;
                
                const date = new Date(yearInt, monthInt - 1, dayInt);
                return date.getFullYear() === yearInt &&
                       date.getMonth() === monthInt - 1 &&
                       date.getDate() === dayInt;
            } catch (e) {
                return false;
            }
        },
        
        getYears() {
            return this._cachedYears || [];
        },
        
        getMonths() {
            return this._cachedMonths || [];
        },
        
        getDays() {
            const days = [];
            const maxDays = this.getDaysInMonth();
            for (let day = 1; day <= maxDays; day++) {
                days.push(day);
            }
            return days;
        },
        
        // Keyboard navigation helpers
        handleKeyDown(event, type) {
            const { key } = event;
            
            if (key === 'ArrowDown' || key === 'ArrowUp') {
                event.preventDefault();
                this.navigateValue(type, key === 'ArrowDown' ? 1 : -1);
            }
        },
        
        navigateValue(type, direction) {
            switch (type) {
                case 'day':
                    this.navigateDay(direction);
                    break;
                case 'month':
                    this.navigateMonth(direction);
                    break;
                case 'year':
                    this.navigateYear(direction);
                    break;
            }
        },
        
        navigateDay(direction) {
            const maxDays = this.getDaysInMonth();
            const currentDay = this.day || 0;
            let newDay = currentDay + direction;
            
            if (newDay < 1) newDay = maxDays;
            if (newDay > maxDays) newDay = 1;
            
            this.day = newDay;
        },
        
        navigateMonth(direction) {
            const currentMonth = this.month || 0;
            let newMonth = currentMonth + direction;
            
            if (newMonth < 1) newMonth = 12;
            if (newMonth > 12) newMonth = 1;
            
            this.month = newMonth;
        },
        
        navigateYear(direction) {
            const currentYear = this.year || this.endYear;
            let newYear = currentYear + direction;
            
            if (newYear < this.startYear) newYear = this.endYear;
            if (newYear > this.endYear) newYear = this.startYear;
            
            this.year = newYear;
        }
    }));
});