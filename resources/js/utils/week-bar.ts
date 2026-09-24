import {
    BarController,
    BarElement,
    CategoryScale,
    Chart,
    LinearScale,
    Tooltip,
} from 'chart.js';

Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip);

type WeekChartPayload = {
    labels: string[];
    values: number[];
    unit: string;
    color?: string;
};

function parsePayload(raw: string | null): WeekChartPayload | null {
    if (!raw) {
        return null;
    }

    try {
        const parsed: unknown = JSON.parse(raw);

        if (
            typeof parsed !== 'object'
            || parsed === null
            || !Array.isArray((parsed as WeekChartPayload).labels)
            || !Array.isArray((parsed as WeekChartPayload).values)
        ) {
            return null;
        }

        return parsed as WeekChartPayload;
    } catch {
        return null;
    }
}

type WeekChartBundle = {
    period?: string;
    subtitles?: Record<string, string>;
    periods?: Record<string, WeekChartPayload>;
};

function applySubtitle(selector: string, subtitles: Record<string, string> | undefined, key: string): void {
    const el = document.querySelector(selector);
    const text = subtitles?.[key];

    if (el && text) {
        el.textContent = text;
    }
}

function parseBundle(raw: string | null): WeekChartBundle | null {
    if (!raw) {
        return null;
    }

    try {
        const parsed: unknown = JSON.parse(raw);

        if (typeof parsed !== 'object' || parsed === null) {
            return null;
        }

        return parsed as WeekChartBundle;
    } catch {
        return null;
    }
}

function drawWeekBar(canvas: HTMLCanvasElement, payload: WeekChartPayload): void {
    Chart.getChart(canvas)?.destroy();

    const max = Math.max(...payload.values, 0);
    const highlight = '#2563eb';
    const rest = '#93c5fd';
    const backgrounds = payload.values.map((value) => (value === max && max > 0 ? highlight : rest));

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: payload.labels,
            datasets: [
                {
                    data: payload.values,
                    backgroundColor: backgrounds,
                    borderRadius: 0,
                    maxBarThickness: 36,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 700,
                easing: 'easeOutQuart',
            },
            animations: {
                colors: false,
                x: {duration: 0},
                base: {duration: 0},
                y: {
                    duration: 700,
                    easing: 'easeOutQuart',
                    from(ctx) {
                        if (ctx.type !== 'data') {
                            return;
                        }

                        return ctx.chart.scales.y.getPixelForValue(0);
                    },
                },
            },
            plugins: {
                legend: {display: false},
                tooltip: {
                    displayColors: false,
                    callbacks: {
                        title: () => '',
                        label: (context) => `${context.label} · ${context.parsed.y ?? 0} ${payload.unit}`,
                    },
                },
            },
            scales: {
                x: {
                    grid: {
                        display: true,
                        color: 'rgba(148, 163, 184, 0.25)',
                        drawTicks: false,
                    },
                    ticks: {color: '#9ca3af', font: {size: 11}},
                    border: {display: false},
                },
                y: {
                    beginAtZero: true,
                    ticks: {color: '#9ca3af', precision: 0},
                    grid: {
                        display: true,
                        color: 'rgba(148, 163, 184, 0.25)',
                        drawTicks: false,
                    },
                    border: {display: false},
                },
            },
        },
    });
}

export function bindWeekChart(canvas: HTMLCanvasElement, raw: string | null): void {
    const bundle = parseBundle(raw);
    const select = document.querySelector<HTMLSelectElement>('[data-week-period]');
    const periodKey = select?.value || bundle?.period || 'week';
    const initial = bundle?.periods?.[periodKey] ?? parsePayload(raw);

    if (!initial || initial.labels.length === 0) {
        return;
    }

    drawWeekBar(canvas, initial);
    applySubtitle('[data-week-subtitle]', bundle?.subtitles, periodKey);

    select?.addEventListener('change', () => {
        const next = bundle?.periods?.[select.value];

        if (!next || next.labels.length === 0) {
            return;
        }

        applySubtitle('[data-week-subtitle]', bundle?.subtitles, select.value);
        drawWeekBar(canvas, next);
    });
}
