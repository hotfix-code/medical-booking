<?php

namespace Database\Data;

class Permissions
{
    public static function list(): array
    {
        return [
            // User Management - Patients
            ['name' => 'patient.create'],
            ['name' => 'patient.view'],
            ['name' => 'patient.edit'],
            ['name' => 'patient.delete'],

            // User Management - Doctors
            ['name' => 'doctor.create'],
            ['name' => 'doctor.view'],
            ['name' => 'doctor.edit'],
            ['name' => 'doctor.delete'],

            // Scheduling - Doctor Schedules
            ['name' => 'schedule.create'],
            ['name' => 'schedule.view'],
            ['name' => 'schedule.edit'],
            ['name' => 'schedule.delete'],

            // Scheduling - Consulting Rooms
            ['name' => 'consulting_room.create'],
            ['name' => 'consulting_room.view'],
            ['name' => 'consulting_room.edit'],
            ['name' => 'consulting_room.delete'],

            // Appointments
            ['name' => 'appointment.create'],
            ['name' => 'appointment.view'],
            ['name' => 'appointment.edit'],
            ['name' => 'appointment.delete'],

            // Clinical - Specialties
            ['name' => 'specialty.create'],
            ['name' => 'specialty.view'],
            ['name' => 'specialty.edit'],
            ['name' => 'specialty.delete'],

            // System - Roles & Permissions
            ['name' => 'role.create'],
            ['name' => 'role.view'],
            ['name' => 'role.edit'],
            ['name' => 'role.delete'],

            // System - Only read
            ['name' => 'permission.view'],
            ['name' => 'permission.edit'],

            // System - Admin Users
            ['name' => 'user.create'],
            ['name' => 'user.view'],
            ['name' => 'user.edit'],
            ['name' => 'user.delete'],

            //️ Configuration - Document Types
            ['name' => 'document_type.create'],
            ['name' => 'document_type.view'],
            ['name' => 'document_type.edit'],
            ['name' => 'document_type.delete'],
        ];
    }

    public static function assignListToDoctor(): array
    {
        return [
            // User Management - Patients
            ['name' => 'patient.view'],

            // Scheduling - Consulting Rooms
            ['name' => 'consulting_room.view'],

            // Scheduling - Doctor Schedules
            ['name' => 'schedule.view'],

            // Appointments
            ['name' => 'appointment.view'],
            ['name' => 'appointment.edit'],
        ];
    }

    public static function assignListToPatient(): array
    {
        return [
            // User Management - Doctors
            ['name' => 'doctor.view'],

            // Clinical - Specialties
            ['name' => 'specialty.view'],

            // Appointments
            ['name' => 'appointment.view'],
        ];
    }
}
