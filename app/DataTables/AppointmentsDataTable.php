<?php

namespace App\DataTables;

use App\Enums\Role;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AppointmentsDataTable extends DataTable
{
    use TranslatesDataTable;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return new EloquentDataTable($query)
            ->setRowId('id')
            ->addColumn('patient', fn (Appointment $appointment) => $appointment->patient->user->full_name ?? __('common.states.na'))
            ->filterColumn('patient', function ($query, $keyword) {
                $query->whereHas('patient.user', function ($q) use ($keyword) {
                    $q->whereRaw("CONCAT(firstname, ' ', lastname) like ?", ["%{$keyword}%"]);
                });
            })
            ->addColumn('doctor', fn (Appointment $appointment) => $appointment->doctor->user->full_name ?? __('common.states.na'))
            ->filterColumn('doctor', function ($query, $keyword) {
                $query->whereHas('doctor.user', function ($q) use ($keyword) {
                    $q->whereRaw("CONCAT(firstname, ' ', lastname) like ?", ["%{$keyword}%"]);
                });
            })
            ->addColumn('consulting_room', fn (Appointment $appointment) => $appointment->consultingRoom->name ?? __('common.states.na'))
            ->filterColumn('consulting_room', function ($query, $keyword) {
                $query->whereHas('consultingRoom', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('appointment_date', function (Appointment $appointment) {
                return $appointment->appointment_date;
            })
            ->editColumn('appointment_time', function (Appointment $appointment) {
                $hour = Carbon::createFromFormat('H:i:s', $appointment->appointment_time);

                return $hour->format('h:ia');
            })
            ->editColumn('status', function (Appointment $appointment) {
                $statusClass = match ($appointment->status->value) {
                    'pending' => 'badge bg-secondary',
                    'confirmed' => 'badge bg-success',
                    'cancelled' => 'badge bg-danger',
                    'completed' => 'badge bg-info',
                    'no_show' => 'badge bg-warning',
                    default => 'badge bg-light',
                };

                return '<span class="'.$statusClass.'">'.__('enums.appointment_status.'.$appointment->status->value).'</span>';
            })
            ->editColumn('is_active', function (Appointment $appointment) {
                return $appointment->is_active
                    ? '<span class="badge bg-success">'.__('common.states.active').'</span>'
                    : '<span class="badge" style="background-color: #95a5a6">'.__('common.states.inactive').'</span>';
            })
            ->editColumn('created_at', function (Appointment $appointment) {
                return $appointment->created_at->format('Y-m-d H:ia');
            })
            ->editColumn('updated_at', function (Appointment $appointment) {
                return $appointment->updated_at->format('Y-m-d H:ia');
            })
            ->addColumn('action', function (Appointment $appointment) {
                return view('components.datatable.actions', [
                    'id' => $appointment->id,
                    'permission' => 'appointment',
                    'buttons' => ['edit', 'delete'],
                ]);
            })
            ->rawColumns(['status', 'is_active', 'action']);
    }

    public function query(Appointment $model): QueryBuilder
    {
        $user = auth()->user();

        $query = match ($user->role) {
            Role::Doctor->value => $model->forDoctor($user->doctor->id),
            Role::Patient->value => $model->forPatient($user->patient->id),
            default => $model->newQuery(),
        };

        return $query->with(['patient.user', 'doctor.user', 'consultingRoom']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('appointments-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(4)
            ->selectStyleSingle()
            ->responsive()
            ->autoWidth(false)
            ->language($this->languageOptions());
    }

    public function getColumns(): array
    {
        return [
            Column::make('patient')->title(__('appointments.columns.patient')),
            Column::make('doctor')->title(__('appointments.columns.doctor')),
            Column::make('consulting_room')->title(__('appointments.columns.consulting_room')),
            Column::make('appointment_date')->title(__('appointments.columns.appointment_date')),
            Column::make('appointment_time')->title(__('appointments.columns.appointment_time')),
            Column::make('status')->title(__('appointments.columns.status')),
            Column::make('is_active')->title(__('appointments.columns.is_active')),
            Column::make('created_at')->title(__('datatables.columns.created_at')),
            Column::make('updated_at')->title(__('datatables.columns.updated_at')),
            Column::computed('action')
                ->title(__('datatables.columns.action'))
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Appointments_' . date('YmdHis');
    }
}
