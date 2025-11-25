import {createScheduleModal, updateScheduleModal} from "@/modals/scheduleModal.js";
import {SuccessModal} from "@/utils/SwalWrapper.js";
import http from "@/utils/http.js";
import flatpickr from "flatpickr";
import {lockTimeDropdownInputs} from "@/utils/flatpickr.js";
import {resetSelect2} from "@/utils/select2.js";

$(function () {
    "use strict";

    const calendarEl = document.getElementById('calendar2');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        views: {
            type: 'timeGridWeek',
            duration: { days: 7 },
        },
        headerToolbar: false,
        footerToolbar: false,
        dayHeaderFormat: { weekday: 'long'},
        allDaySlot: false,
        navLinks: false,
        businessHours: true,
        selectable: false,
        selectMirror: false,
        droppable: false,
        eventStartEditable: false,
        eventDurationEditable: false,
        eventClick: async function (arg) {
            try {
                const { status, value } = await updateScheduleModal(arg.event.id);
                const schedule = value.data;

                if (status === 'confirmed') {
                    SuccessModal('Schedule Updated', 'Schedule updated successfully')
                        .then(() => {
                            calendar.getEventSources().forEach(src => src.remove());
                            calendar.addEventSource({
                                id: `room-${schedule.consulting_room.id}`,
                                events: adaptScheduleData(schedule.schedulesByConsultingRoom)
                            });

                            consultingRoomSelect
                                .val(schedule.consulting_room.id)
                                .trigger('change');
                        });
                }

                if (status === 'denied') {
                    SuccessModal('Schedule Deleted', 'Schedule deleted successfully')
                        .then(() => {
                            calendar.getEventSources().forEach(src => src.remove());
                            calendar.addEventSource({
                                id: `room-${schedule.consulting_room.id}`,
                                events: adaptScheduleData(schedule.schedulesByConsultingRoom)
                            });
                        });
                }

            } catch (err) {
                // Interceptor shows the error modal
            }
        },
        editable: false,
        dayMaxEvents: true,
    });
    calendar.render();

    const consultingRoomSelect = $('#schedule-consulting-room-input')
        .select2({
            placeholder: 'Select a consulting room',
            allowClear: true,
            dir: 'ltr',
        })
        .on('select2:select', async function (e) {
            const roomId = e.params.data.id;
            await http.post(`/schedules/fetch-all/consulting-rooms/${e.params.data.id}`)
                .then(({ data: { data: schedules } }) => {
                    calendar.getEventSources().forEach(src => src.remove());
                    calendar.addEventSource({
                        id: `room-${roomId}`,
                        events: adaptScheduleData(schedules)
                    });
                })
        });

    const specialtyDoctorSelect = $('#schedule-specialty-select')
        .select2({
            placeholder: "Select a specialty",
            allowClear: true,
            dir: "ltr",
        });

    const scheduleDoctorSelect = $('#schedule-doctor-select')
        .select2({
            placeholder: "Select a doctor",
            allowClear: true,
            dir: "ltr",
        });

    const scheduleWeekdaySelect = $('#schedule-weekday-select').select2({
        placeholder: "Select a weekday",
        allowClear: true,
        dir: "ltr",
    });

    flatpickr("#schedule-start-time-input", {
        enableTime: true,
        noCalendar: true,
        altInput: true,
        altFormat: "h:i K",
        dateFormat: "H:i",
        minuteIncrement: 60,
        allowInput: false,
        onReady(_, __, instance) {
            lockTimeDropdownInputs(instance);
        },
        onOpen(_, __, instance) {
            lockTimeDropdownInputs(instance);
        },
    });

    flatpickr("#schedule-end-time-input", {
        enableTime: true,
        noCalendar: true,
        altInput: true,
        altFormat: "h:i K",
        dateFormat: "H:i",
        minuteIncrement: 60,
        allowInput: false,
        onReady(_, __, instance) {
            lockTimeDropdownInputs(instance);
        },
        onOpen(_, __, instance) {
            lockTimeDropdownInputs(instance);
        },
    });

    $('#add-new-schedule').on('click', async function () {
        try {
            const { status, value } = await createScheduleModal();
            if (status === 'dismissed') return;
            const schedule = value.data;
            const roomId = schedule.consulting_room.id;
            SuccessModal('Schedule Created', 'Schedule created successfully')
                .then(() => {
                    resetSelect2(specialtyDoctorSelect, scheduleDoctorSelect, scheduleWeekdaySelect);
                    calendar.getEventSources().forEach(src => src.remove());
                    calendar.addEventSource({
                        id: `room-${roomId}`,
                        events: adaptScheduleData(schedule.schedulesByConsultingRoom)
                    });
                });
        } catch (err) {
            // Interceptor shows the error modal
        }
    });

    function adaptScheduleData(apiData = []) {
        return apiData.map(s => ({
            id: s.id,
            groupId: s.groupId,              // opcional
            title: s.title,
            daysOfWeek: s.daysOfWeek,         // [1] ⇒ lunes
            startTime: s.startTime.slice(0, 5), // "12:00"
            endTime: s.endTime.slice(0, 5),   // "16:00"
            color: colorFromUuid(s.groupIdByDoctor),
            textColor: s.textColor ?? undefined,
            description: s.description ?? ''
        }));
    }

    function hueFromUuid(uuid) {
        const sum = uuid
            .replace('sch_', '')
            .split('')
            .reduce((acc, ch) => acc + ch.charCodeAt(0), 0);
        return sum % 360;
    }

    function colorFromUuid(uuid, saturation = 70, lightness = 60) {
        const hue = hueFromUuid(uuid);
        return `hsl(${hue}, ${saturation}%, ${lightness}%)`;
    }
});
