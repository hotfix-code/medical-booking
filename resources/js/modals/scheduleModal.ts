import {LoadingModal, ShowModal} from "@/utils/SwalWrapper.ts";
import {ScheduleService} from "@/services/schedule.service.ts";
import {Header} from "@/components/modal";
import flatpickr from "flatpickr";
import {lockTimeDropdownInputs} from "@/utils/flatpickr.ts";

export const createScheduleModal = async () => {
    return ShowModal({
        title: Header('Create new schedule?'),
        text: 'This action confirm the schedule creation.',
        didOpen: async () => {
            (document.getElementById('schedule-consulting-room-input') as HTMLSelectElement).focus();
        },
        preConfirm: async () => {
            const { data } = await ScheduleService.create({
                consulting_room_id: (document.getElementById('schedule-consulting-room-input') as HTMLSelectElement).value,
                doctor_id: (document.getElementById('schedule-doctor-select') as HTMLSelectElement).value,
                specialty_id: (document.getElementById('schedule-specialty-select') as HTMLSelectElement).value,
                weekday: parseInt((document.getElementById('schedule-weekday-select') as HTMLSelectElement).value),
                start_time: (document.getElementById('schedule-start-time-input') as HTMLInputElement).value,
                end_time: (document.getElementById('schedule-end-time-input') as HTMLInputElement).value,
            });
            return data;
        },
    });
}

export const updateScheduleModal = async (id: string) => {
    await LoadingModal('Loading Schedule data...');
    const { data } = await ScheduleService.fetch(id);
    const schedule = data.data;

    const tpl = document.getElementById('schedule-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header('Edit schedule?'),
        html: formHtml,
        width: 600,
        showDenyButton: true,
        confirmButtonText: 'Save Changes',
        denyButtonText: 'Delete Schedule',
        didOpen: async () => {
            const $dp = $('.swal2-container');

            const consultingRoomSelect = document.getElementById('schedule-consulting-room-input-edit') as HTMLSelectElement;
            $(consultingRoomSelect)
                .select2({
                    placeholder: 'Select a consulting room',
                    allowClear: true,
                    dir: 'ltr',
                    dropdownParent: $dp,
                })
                .val(String(schedule.consulting_room_id))
                .trigger('change');

            const specialtySelect = document.getElementById('schedule-specialty-select-edit') as HTMLSelectElement;
            $(specialtySelect)
                .select2({
                    placeholder: 'Select a specialty',
                    allowClear: true,
                    dir: 'ltr',
                    dropdownParent: $dp,
                })
                .val(String(schedule.specialty_id))
                .trigger('change');

            const doctorSelect = document.getElementById('schedule-doctor-input-edit') as HTMLSelectElement;
            $(doctorSelect)
                .select2({
                    placeholder: 'Select a doctor',
                    allowClear: true,
                    dir: 'ltr',
                    dropdownParent: $dp,
                })
                .val(String(schedule.doctor_id))
                .trigger('change');

            const weekdaySelect = document.getElementById('schedule-weekday-input-edit') as HTMLSelectElement;
            $(weekdaySelect)
                .select2({
                    placeholder: 'Select a weekday',
                    allowClear: true,
                    dir: 'ltr',
                    dropdownParent: $dp,
                })
                .val(String(schedule.weekday))
                .trigger('change');

            const $swal = document.querySelector('.swal2-container') as HTMLInputElement;

            flatpickr("#schedule-start-time-input-edit", {
                enableTime: true,
                noCalendar: true,
                altInput: true,
                altFormat: "h:i K",
                dateFormat: "H:i",
                minuteIncrement: 60,
                appendTo: $swal,
                defaultDate: schedule.start_time,
                allowInput: false,
                onReady(_, __, instance) {
                    lockTimeDropdownInputs(instance);
                },
                onOpen(_, __, instance) {
                    lockTimeDropdownInputs(instance);
                },
            });

            flatpickr("#schedule-end-time-input-edit", {
                enableTime: true,
                noCalendar: true,
                altInput: true,
                altFormat: "h:i K",
                dateFormat: "H:i",
                minuteIncrement: 60,
                appendTo: $swal,
                defaultDate: schedule.end_time,
                allowInput: false,
                onReady(_, __, instance) {
                    lockTimeDropdownInputs(instance);
                },
                onOpen(_, __, instance) {
                    lockTimeDropdownInputs(instance);
                },
            });
        },
        preConfirm: async () => {
            const { data } = await ScheduleService.update(id, {
                consulting_room_id: (document.getElementById('schedule-consulting-room-input-edit') as HTMLSelectElement).value,
                specialty_id: (document.getElementById('schedule-specialty-select-edit') as HTMLSelectElement).value,
                doctor_id: (document.getElementById('schedule-doctor-input-edit') as HTMLSelectElement).value,
                weekday: parseInt((document.getElementById('schedule-weekday-input-edit') as HTMLSelectElement).value),
                start_time: (document.getElementById('schedule-start-time-input-edit') as HTMLInputElement).value,
                end_time: (document.getElementById('schedule-end-time-input-edit') as HTMLInputElement).value,
            });
            return data;
        },
        preDeny: async () => {
            const { value } = await deleteScheduleModal(id);
            return value;
        },
    });
};

export const deleteScheduleModal = async (id: string) => {
    return ShowModal({
        title: Header('Delete schedule?', 'danger'),
        html: `<span style="font-size: 0.85rem;">Are you sure you want to delete the schedule?</span>`,
        confirmButtonText: 'Delete',
        preConfirm: async () => {
            const { data } = await ScheduleService.delete(id);
            return data;
        },
    });
}
