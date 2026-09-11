import {LoadingModal, ShowModal} from "@/utils/SwalWrapper.ts";
import {AppointmentService} from "@/services/appointment.service.ts";
import {Header} from "@/components/modal";
import {SpecialtyService} from "@/services/specialty.service.ts";
import {DoctorService} from "@/services/doctor.service.ts";
import flatpickr from "flatpickr";
import {clearSelect2} from "@/utils/select2.ts";
import {resetFlatpickr} from "@/utils/flatpickr.ts";
import { DateTime } from "luxon";
import { t } from '@/utils/i18n';

type Schedule = {
    id: number;
    weekday: number;
    start_time: string;
    end_time: string;
};

export const createAppointmentModal = async () => {
    const tpl = document.getElementById('appointment-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header(t('appointments.modals.create')),
        html: formHtml,
        width: '800px',
        didOpen: async () => {
            const dp = $('.swal2-container');
            const swal = document.querySelector('.swal2-container') as HTMLInputElement;
            const appointmentDate = document.getElementById('appointment-date-input') as HTMLInputElement;
            const consultingRoomInput = document.getElementById('appointment-consulting-room-input') as HTMLInputElement;
            const consultingRoomInputHidden = document.getElementById('appointment-consulting-room-input-hidden') as HTMLInputElement;
            const scheduleInputHidden = document.getElementById('appointment-schedule-input-hidden') as HTMLInputElement;
            const isActiveInput = document.getElementById('appointment-is-active-input') as HTMLInputElement;

            const appointmentTimeSelect = $('#appointment-time-select')
                .select2({
                    placeholder: t('appointments.placeholders.select_time'),
                    allowClear: true,
                    dir: 'ltr',
                    dropdownParent: dp,
                })
                .on('select2:select', function (e) {
                    const option = e.params.data.element;
                    consultingRoomInputHidden.value = `${option.dataset.consultingRoomId}`;
                    scheduleInputHidden.value = `${option.dataset.scheduleId}`;
                    consultingRoomInput.value = option.dataset.consultinRoomLocation === 'null'
                        ? `${option.dataset.consultingRoomName}`
                        : `${option.dataset.consultingRoomName} - ${option.dataset.consultinRoomLocation}`;
                })
                .on('select2:unselecting', function () {
                    consultingRoomInput.value = '';
                });

            const patientSelect = document.getElementById('appointment-patient-select') as HTMLSelectElement;
            $(patientSelect)
                .select2({
                    placeholder: t('appointments.placeholders.select_patient'),
                    allowClear: true,
                    dir: 'ltr',
                    dropdownParent: dp,
                });

            const scheduleInput = document.getElementById('appointment-schedule-input') as HTMLInputElement;
            let tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            let doctorScheduleCalendar = flatpickr(scheduleInput, {
                minDate: tomorrow,
                disable: [() => true],
                appendTo: swal
            });

            const doctorSelect = $('#appointment-doctor-select')
                .select2({
                    placeholder: t('appointments.placeholders.select_doctor'),
                    allowClear: true,
                    dir: 'ltr',
                    dropdownParent: dp,
                })
                .on('select2:select', async function (e: any) {
                    consultingRoomInput.value = '';
                    appointmentDate.value = '';
                    resetFlatpickr(doctorScheduleCalendar)
                    const doctorId = e.params.data.id;
                    const specialtyId: string = $(specialtySelect).val() as string;
                    const { data: { data: doctor } } = await DoctorService.fetchBySpecialty(doctorId, specialtyId);
                    const enableDays = new Set(doctor.schedules.map((schedule: Schedule) => schedule.weekday % 7));

                    const onValueUpdate = async (selectedDates: Date[], dateStr: string, _instance: any) => {
                        clearSelect2(appointmentTimeSelect);

                        const selected = selectedDates[0];
                        if (!selected) return;
                        appointmentDate.value = dateStr;

                        const { data: { data: doctor } } = await DoctorService.available(doctorId, { date: dateStr });

                        const doctorSchedules = doctor.schedules;
                        doctorSchedules.forEach((schedule: any) => {
                            schedule.time_slots.forEach(({ is_available: is_available, start: start, end: end } : { is_available: boolean, start: string, end: string}) => {
                                console.log(is_available)
                                if (is_available) {
                                    const startFormatted = DateTime.fromFormat(start, "HH:mm:ss").toFormat("hh:mma");
                                    const endFormatted = DateTime.fromFormat(end, "HH:mm:ss").toFormat("hh:mma");
                                    const option = new Option(
                                        `${startFormatted} - ${endFormatted}`,
                                        start,
                                        false,
                                        false
                                    );
                                    option.dataset.scheduleId = schedule.id;
                                    option.dataset.consultingRoomId = schedule.consulting_room.id;
                                    option.dataset.consultingRoomName = schedule.consulting_room.name;
                                    option.dataset.consultinRoomLocation = schedule.consulting_room.location;
                                    appointmentTimeSelect.append(option);
                                }
                            });
                        });

                        appointmentTimeSelect.prop('disabled', false);
                    };

                    const onDayCreate = (_dObj: any, _dStr: string, _fp: any, dayElem: any) => {
                        if (enableDays.has(dayElem.dateObj.getDay())) {
                            dayElem.classList.add('bg-primary-transparent');
                        }
                    }

                    doctorScheduleCalendar.set('disable', [(date: any) => !enableDays.has(date.getDay())]);
                    doctorScheduleCalendar.set('onValueUpdate', onValueUpdate);
                    doctorScheduleCalendar.set('onDayCreate', onDayCreate);
                })
                .on('select2:unselecting', async function () {
                    consultingRoomInput.value = '';
                    appointmentDate.value = '';
                    resetFlatpickr(doctorScheduleCalendar);
                });

            const specialtySelect = document.getElementById('appointment-specialty-select') as HTMLSelectElement;
            $(specialtySelect)
                .select2({
                    placeholder: t('appointments.placeholders.select_doctor'),
                    allowClear: true,
                    dir: 'ltr',
                    dropdownParent: dp,
                })
                .on('select2:select', async function (e: any) {
                    consultingRoomInput.value = '';
                    appointmentDate.value = '';
                    clearSelect2(doctorSelect);
                    resetFlatpickr(doctorScheduleCalendar);

                    doctorSelect.prop('disabled', true);
                    const { data: { data: specialties } } = await SpecialtyService.fetchWithDoctors(e.params.data.id);
                    specialties.doctors.forEach((doctor: any) => {
                        doctorSelect.append(new Option(`${doctor.user.firstname} ${doctor.user.lastname}`, doctor.id, false, false));
                    });
                    doctorSelect.prop('disabled', false);
                })
                .on('select2:unselecting', async function () {
                    consultingRoomInput.value = '';
                    appointmentDate.value = '';
                    clearSelect2(doctorSelect);
                    resetFlatpickr(doctorScheduleCalendar);
                });

            const statusSelect = document.getElementById('appointment-status-select') as HTMLSelectElement;
            $(statusSelect)
                .select2({
                    placeholder: t('appointments.placeholders.select_status'),
                    allowClear: true,
                    dir: 'ltr',
                    dropdownParent: dp,
                })
                .on('select2:select', function (e) {
                    ['pending', 'confirmed'].includes(e.params.data.id)
                        ? isActiveInput.value = t('common.states.yes')
                        : isActiveInput.value = t('common.states.no');
                })
                .on('select2:unselecting', function () {
                    isActiveInput.value = t('common.states.na');
                });

        },
        preConfirm: async () => {
            const { data } = await AppointmentService.create({
                patient_id: (document.getElementById('appointment-patient-select') as HTMLSelectElement).value,
                doctor_id: (document.getElementById('appointment-doctor-select') as HTMLSelectElement).value,
                consulting_room_id: (document.getElementById('appointment-consulting-room-input-hidden') as HTMLSelectElement).value,
                specialty_id: (document.getElementById('appointment-specialty-select') as HTMLSelectElement).value,
                schedule_id: (document.getElementById('appointment-schedule-input-hidden') as HTMLSelectElement).value,
                appointment_date: (document.getElementById('appointment-date-input') as HTMLInputElement).value,
                appointment_time: (document.getElementById('appointment-time-select') as HTMLSelectElement).value,
                status: (document.getElementById('appointment-status-select') as HTMLSelectElement).value,
                notes: (document.getElementById('appointment-notes-input') as HTMLTextAreaElement).value.trim(),
            });
            return data;
        },
    });
}

export const updateAppointmentModal = async (id: string) => {
    await LoadingModal(t('messages.loading_resource', { resource: t('appointments.resource') }));
    const { data } = await AppointmentService.fetch(id);
    const appointment = data.data;

    const tpl = document.getElementById('appointment-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header(t('appointments.modals.edit')),
        html: formHtml,
        width: '800px',
        didOpen: async () => {
            const dp = $('.swal2-container');
            const patientSelect = document.getElementById('appointment-patient-select') as HTMLSelectElement;
            const specialtySelect = document.getElementById('appointment-specialty-select') as HTMLSelectElement;
            const doctorSelect = document.getElementById('appointment-doctor-select') as HTMLSelectElement;
            const consultingRoomInput = document.getElementById('appointment-consulting-room-input') as HTMLInputElement;
            const scheduleInput = document.getElementById('appointment-schedule-input') as HTMLInputElement;
            const dateInput = document.getElementById('appointment-date-input') as HTMLInputElement;
            const timeSelect = document.getElementById('appointment-time-select') as HTMLSelectElement;
            const statusSelect = document.getElementById('appointment-status-select') as HTMLSelectElement;
            const isActiveInput = document.getElementById('appointment-is-active-input') as HTMLInputElement;
            const notesInput = document.getElementById('appointment-notes-input') as HTMLTextAreaElement;

            if (patientSelect) {
                patientSelect.value = appointment.patient_id
                patientSelect.disabled = true;
            }

            if (specialtySelect) {
                specialtySelect.value = appointment.specialty_id;
                specialtySelect.disabled = true;
            }

            if (doctorSelect) {
                const tempOption = document.createElement('option');
                tempOption.textContent = `${appointment.doctor.user.full_name}`;
                tempOption.selected = true;
                doctorSelect.insertBefore(tempOption, doctorSelect.firstChild);
                doctorSelect.disabled = true;
            }

            if (consultingRoomInput) {
                consultingRoomInput.value = appointment.consulting_room.location === null
                    ? `${appointment.consulting_room.name}`
                    : `${appointment.consulting_room.name} - ${appointment.consulting_room.location}`;
                consultingRoomInput.disabled = true;
            }

            if (scheduleInput) {
                scheduleInput.value = appointment.appointment_date;
                scheduleInput.disabled = true;
            }

            if (dateInput) dateInput.value = appointment.appointment_date;

            if (timeSelect) {
                const tempOption = document.createElement('option');
                const time = DateTime.fromFormat(appointment.appointment_time, "HH:mm:ss").toFormat("hh:mm a");
                tempOption.textContent = `${time}`;
                tempOption.selected = true;
                timeSelect.insertBefore(tempOption, timeSelect.firstChild);
                timeSelect.disabled = true;
            }

            if (statusSelect) {
                $(statusSelect)
                    .select2({
                        placeholder: t('appointments.placeholders.select_status'),
                        allowClear: true,
                        dir: 'ltr',
                        dropdownParent: dp,
                    })
                    .on('select2:select', function (e) {
                        ['pending', 'confirmed'].includes(e.params.data.id)
                            ? isActiveInput.value = t('common.states.yes')
                            : isActiveInput.value = t('common.states.no');
                    })
                    .on('select2:unselecting', function () {
                        isActiveInput.value = t('common.states.na');
                    });
                statusSelect.value = appointment.status;
            }

            if (isActiveInput) isActiveInput.value = appointment.is_active ? t('common.states.yes') : t('common.states.no');
            if (notesInput) notesInput.value = appointment.notes || '';

        },
        preConfirm: async () => {
            const { data } = await AppointmentService.update(id, {
                status: (document.getElementById('appointment-status-select') as HTMLSelectElement).value,
                notes: (document.getElementById('appointment-notes-input') as HTMLTextAreaElement).value.trim(),
            });
            return data;
        },
        confirmButtonText: t('common.actions.save_changes'),
    });
};

export const deleteAppointmentModal = async (id: string) => {
    return ShowModal({
        title: Header(t('appointments.modals.delete'), 'danger'),
        html: `<span style="font-size: 0.85rem;">${t('messages.confirm_delete', { resource: t('appointments.resource') })}</span>`,
        confirmButtonText: t('common.actions.delete'),
        preConfirm: async () => {
            const { data } = await AppointmentService.delete(id);
            return data;
        },
    });
}
