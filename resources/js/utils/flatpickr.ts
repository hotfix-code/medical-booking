export function resetFlatpickr(calendar: any, disable: boolean = true) {
    calendar.clear();
    calendar.set('onDayCreate', null as any);
    calendar.set('disable', [() => disable]);
}

export function lockTimeDropdownInputs(instance: any) {
    const container = instance.calendarContainer;
    if (!container) return;
    const timeInputs = container.querySelectorAll(".flatpickr-time input, .flatpickr-time .numInput");
    timeInputs.forEach((input: HTMLInputElement) => {
        input.setAttribute("readonly", "readonly");
        input.setAttribute("inputmode", "none");
        input.addEventListener("keydown", (e) => e.preventDefault());
        input.addEventListener("keypress", (e) => e.preventDefault());
        input.addEventListener("paste", (e) => e.preventDefault());
        input.addEventListener("drop", (e) => e.preventDefault());
        input.addEventListener("wheel", (e) => e.preventDefault(), { passive: false });
    });
}
