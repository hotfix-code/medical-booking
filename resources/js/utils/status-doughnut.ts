import {
    ArcElement,
    Chart,
    DoughnutController,
    Tooltip,
} from 'chart.js';

Chart.register(DoughnutController, ArcElement, Tooltip);

type StatusLegendItem = {
    label: string;
    value: number;
    percent: number;
    color: string;
};

type StatusChartPayload = {
    labels: string[];
    values: number[];
    colors: string[];
    total: number;
    unit: string;
    legend?: StatusLegendItem[];
};

type StatusChartBundle = {
    period?: string;
    subtitles?: Record<string, string>;
    periods?: Record<string, StatusChartPayload>;
};

function applySubtitle(key: string, subtitles: Record<string, string> | undefined): void {
    const el = document.querySelector('[data-status-subtitle]');
    const text = subtitles?.[key];

    if (el && text) {
        el.textContent = text;
    }
}

function isStatusPayload(value: unknown): value is StatusChartPayload {
    if (typeof value !== 'object' || value === null) {
        return false;
    }

    const payload = value as StatusChartPayload;

    return Array.isArray(payload.labels) && Array.isArray(payload.values);
}

function parseBundle(raw: string | null): StatusChartBundle | null {
    if (!raw) {
        return null;
    }

    try {
        const parsed: unknown = JSON.parse(raw);

        if (typeof parsed !== 'object' || parsed === null) {
            return null;
        }

        return parsed as StatusChartBundle;
    } catch {
        return null;
    }
}

function paintCenter(payload: StatusChartPayload): void {
    const total = document.querySelector('[data-status-total]');
    const unit = document.querySelector('[data-status-unit]');

    if (total) {
        total.textContent = String(payload.total);
    }

    if (unit) {
        unit.textContent = payload.unit;
    }
}

function paintLegend(list: HTMLElement, legend: StatusLegendItem[]): void {
    list.replaceChildren();

    legend.forEach((item) => {
        const li = document.createElement('li');
        li.innerHTML = `<span class="dashboard-status-legend-label"><span class="dashboard-status-dot" style="background: ${item.color}"></span>${item.label}</span><span class="dashboard-status-legend-value">${item.value} <span class="text-muted">(${item.percent}%)</span></span>`;
        list.append(li);
    });
}

function sizeCanvas(canvas: HTMLCanvasElement): void {
    const parent = canvas.parentElement;

    if (!parent) {
        return;
    }

    const {width, height} = parent.getBoundingClientRect();
    const side = Math.round(Math.min(width, height));

    if (side < 1) {
        return;
    }

    canvas.width = side;
    canvas.height = side;
    canvas.style.width = `${side}px`;
    canvas.style.height = `${side}px`;
}

function drawDoughnut(canvas: HTMLCanvasElement, payload: StatusChartPayload): Chart {
    Chart.getChart(canvas)?.destroy();
    sizeCanvas(canvas);

    return new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: payload.labels,
            datasets: [
                {
                    data: payload.values,
                    backgroundColor: payload.colors,
                    borderWidth: 0,
                    hoverOffset: 4,
                },
            ],
        },
        options: {
            animation: {
                duration: 1200,
                easing: 'easeInOutCubic',
                animateRotate: true,
                animateScale: false,
            },
            responsive: false,
            maintainAspectRatio: false,
            cutout: '66%',
            plugins: {
                legend: {display: false},
                tooltip: {
                    displayColors: false,
                    callbacks: {
                        title: () => '',
                        label: (context) => {
                            const value = context.parsed ?? 0;
                            const percent = payload.total > 0
                                ? Math.round((value / payload.total) * 100)
                                : 0;

                            return `${context.label} · ${value} (${percent}%)`;
                        },
                    },
                },
            },
        },
    });
}

export function bindStatusChart(canvas: HTMLCanvasElement, raw: string | null): void {
    const bundle = parseBundle(raw);
    const select = document.querySelector<HTMLSelectElement>('[data-status-period]');
    const legend = document.querySelector<HTMLElement>('[data-status-legend]');

    const periodKey = select?.value || bundle?.period || 'month';
    const initial = bundle?.periods?.[periodKey];

    if (!initial || !isStatusPayload(initial)) {
        return;
    }

    drawDoughnut(canvas, initial);
    paintCenter(initial);
    applySubtitle(periodKey, bundle?.subtitles);

    if (legend && initial.legend) {
        paintLegend(legend, initial.legend);
    }

    select?.addEventListener('change', () => {
        const next = bundle?.periods?.[select.value];

        if (!next || !isStatusPayload(next)) {
            return;
        }

        applySubtitle(select.value, bundle?.subtitles);
        drawDoughnut(canvas, next);
        paintCenter(next);

        if (legend && next.legend) {
            paintLegend(legend, next.legend);
        }
    });
}
