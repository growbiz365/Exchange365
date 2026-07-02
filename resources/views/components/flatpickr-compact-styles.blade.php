<style>
    .flatpickr-calendar {
        width: 252px !important;
        font-size: 0.75rem;
        border-radius: 0.375rem;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.12);
        line-height: 1.2;
    }
    .flatpickr-calendar.open {
        z-index: 10000;
    }
    .flatpickr-months {
        padding: 6px 8px 0;
    }
    .flatpickr-months .flatpickr-month {
        height: 28px;
    }
    .flatpickr-current-month {
        font-size: 0.8125rem;
        padding-top: 0;
        height: 28px;
        line-height: 28px;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months,
    .flatpickr-current-month input.cur-year {
        font-size: 0.8125rem;
        font-weight: 600;
    }
    .flatpickr-weekdays {
        height: 22px;
    }
    span.flatpickr-weekday {
        font-size: 0.6875rem;
        line-height: 22px;
    }
    .flatpickr-days,
    .flatpickr-calendar .flatpickr-days,
    .flatpickr-calendar .dayContainer,
    .dayContainer {
        width: 236px !important;
        min-width: 236px !important;
        max-width: 236px !important;
    }
    .flatpickr-day {
        flex-basis: 14.2857143% !important;
        max-width: 32px !important;
        height: 28px;
        line-height: 28px;
        border-radius: 9999px;
        font-size: 0.75rem;
    }
    .flatpickr-day.today { border-color: #4f46e5; }
    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange {
        background: #4f46e5;
        border-color: #4f46e5;
        color: #fff;
    }
    .flatpickr-innerContainer {
        padding: 0 8px 8px;
    }
    .flatpickr-months .flatpickr-prev-month,
    .flatpickr-months .flatpickr-next-month {
        padding: 4px 8px;
        top: 4px;
    }
    .flatpickr-months .flatpickr-prev-month svg,
    .flatpickr-months .flatpickr-next-month svg {
        width: 12px;
        height: 12px;
    }
</style>
