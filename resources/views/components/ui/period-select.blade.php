@props([
    'value' => 'month',
])

<div class="dashboard-period" data-period>
    <button type="button" class="dashboard-period-trigger" data-period-trigger aria-haspopup="listbox" aria-expanded="false">
        <span data-period-label>
            {{ $value === 'week' ? __('dashboard.charts.period_week') : __('dashboard.charts.period_month') }}
        </span>
        <i class="bx bx-chevron-down"></i>
    </button>
    <ul class="dashboard-period-menu" data-period-menu hidden role="listbox">
        <li>
            <button type="button" role="option" data-period-option data-value="month" @class(['is-selected' => $value === 'month'])>
                {{ __('dashboard.charts.period_month') }}
            </button>
        </li>
        <li>
            <button type="button" role="option" data-period-option data-value="week" @class(['is-selected' => $value === 'week'])>
                {{ __('dashboard.charts.period_week') }}
            </button>
        </li>
    </ul>
    <select class="visually-hidden" tabindex="-1" aria-hidden="true" {{ $attributes }}>
        <option value="month" @selected($value === 'month')>{{ __('dashboard.charts.period_month') }}</option>
        <option value="week" @selected($value === 'week')>{{ __('dashboard.charts.period_week') }}</option>
    </select>
</div>
