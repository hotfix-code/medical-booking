import { startDashboardClock } from '@/utils/dashboard-clock';

function afterPaint(): Promise<void> {
    return new Promise((resolve) => {
        requestAnimationFrame(() => {
            requestAnimationFrame(() => resolve());
        });
    });
}

function bootClock(): void {
    const root = document.querySelector<HTMLElement>('[data-dashboard-clock]');

    if (root) {
        startDashboardClock(root);
    }
}

async function bootCharts(): Promise<void> {
    await afterPaint();

    const {bindPeriodSelects} = await import('@/utils/period-select');
    bindPeriodSelects();

    const canvases = document.querySelectorAll<HTMLCanvasElement>('canvas.chart-dropshadow');

    if (canvases.length > 0) {
        const {renderSparklines} = await import('@/utils/sparkline');
        renderSparklines(canvases);
    }

    const weekCanvas = document.querySelector<HTMLCanvasElement>('canvas[data-chart="week"]');
    const weekPayload = document.getElementById('dashboard-week-chart');

    if (weekCanvas && weekPayload) {
        const {bindWeekChart} = await import('@/utils/week-bar');
        bindWeekChart(weekCanvas, weekPayload.textContent);
    }

    const statusCanvas = document.querySelector<HTMLCanvasElement>('canvas[data-chart="status"]');
    const statusPayload = document.getElementById('dashboard-status-chart');

    if (statusCanvas && statusPayload) {
        const {bindStatusChart} = await import('@/utils/status-doughnut');
        bindStatusChart(statusCanvas, statusPayload.textContent);
    }

    const specialtiesPayload = document.getElementById('dashboard-specialties');

    if (specialtiesPayload) {
        const {bindSpecialties} = await import('@/utils/specialties');
        bindSpecialties(specialtiesPayload.textContent);
    }
}

function boot(): void {
    bootClock();
    void bootCharts();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}
