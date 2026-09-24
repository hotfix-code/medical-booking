import {
    CategoryScale,
    Chart,
    Filler,
    LinearScale,
    LineController,
    LineElement,
    PointElement,
    Tooltip,
} from 'chart.js';

Chart.register(
    LineController,
    LineElement,
    PointElement,
    LinearScale,
    CategoryScale,
    Filler,
    Tooltip,
);

function hexToRgba(hex: string, alpha: number): string {
    const raw = hex.replace('#', '');
    const normalized = raw.length === 3
        ? raw.split('').map((char) => char + char).join('')
        : raw;
    const value = Number.parseInt(normalized, 16);

    if (Number.isNaN(value)) {
        return `rgba(59, 130, 246, ${alpha})`;
    }

    const r = (value >> 16) & 255;
    const g = (value >> 8) & 255;
    const b = value & 255;

    return `rgba(${r}, ${g}, ${b}, ${alpha})`;
}

function parseSeries(raw: string | undefined): number[] {
    if (!raw) {
        return [];
    }

    try {
        const parsed: unknown = JSON.parse(raw);

        if (!Array.isArray(parsed)) {
            return [];
        }

        return parsed.filter((point): point is number => typeof point === 'number');
    } catch {
        return [];
    }
}

function createSparkline(
    canvas: HTMLCanvasElement,
    series: number[],
    color: string,
): void {
    new Chart(canvas, {
        type: 'line',
        data: {
            labels: series.map((_, index) => String(index + 1)),
            datasets: [
                {
                    data: series,
                    borderColor: color,
                    backgroundColor: hexToRgba(color, 0.16),
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 3,
                    pointHitRadius: 8,
                },
            ],
        },
        options: {
            animation: {
                duration: 700,
                easing: 'easeOutQuart',
            },
            animations: {
                colors: false,
            },
            responsive: false,
            maintainAspectRatio: false,
            devicePixelRatio: window.devicePixelRatio || 1,
            events: ['mousemove', 'mouseout'],
            layout: {
                autoPadding: false,
                padding: 0,
            },
            plugins: {
                legend: {display: false},
                tooltip: {
                    enabled: true,
                    displayColors: false,
                    callbacks: {
                        title: () => '',
                        label: (context) => String(context.parsed.y ?? ''),
                    },
                },
            },
            scales: {
                x: {display: false},
                y: {display: false},
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        },
    });
}

export function renderSparklines(
    canvases: NodeListOf<HTMLCanvasElement> | HTMLCanvasElement[],
): void {
    canvases.forEach((canvas) => {
        const series = parseSeries(canvas.dataset.series);
        const color = canvas.dataset.color ?? '#3b82f6';

        if (series.length === 0) {
            return;
        }

        createSparkline(canvas, series, color);
    });
}
