<?php

namespace App\DataTables;

use App\Enums\Role;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PatientsDataTable extends DataTable
{
    use TranslatesDataTable;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return new EloquentDataTable($query)
            ->setRowId('id')
            ->addColumn('full_name', fn (Patient $patient) => $patient->user->full_name)
            ->filterColumn('full_name', function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->whereRaw("CONCAT(firstname, ' ', lastname) like ?", ["%{$keyword}%"]);
                });
            })
            ->addColumn('document_type', fn (Patient $patient) => $patient->documentType->code)
            ->filterColumn('document_type', function ($query, $keyword) {
                $query->whereHas('documentType', function ($q) use ($keyword) {
                    $q->where('code', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('created_at', fn (Patient $patient) => $patient->created_at->format('Y-m-d H:ia'))
            ->editColumn('phone', fn (Patient $patient) => $patient->phone ?? __('common.states.na'))
            ->addColumn('action', function (Patient $patient) {
                return view('components.datatable.actions', [
                    'id' => $patient->id,
                    'permission' => 'patient',
                    'buttons' => ['edit', 'delete'],
                ]);
            });
    }

    public function query(Patient $model): QueryBuilder
    {
        $user = auth()->user();

        $query = $user->role == Role::Doctor->value
            ? $model->forDoctor($user->doctor->id)
            : $model->newQuery();

        return $query->with(['user', 'documentType']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('patients-table')
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
            Column::make('full_name')->title(__('patients.columns.full_name')),
            Column::make('document_type')->title(__('patients.columns.document_type')),
            Column::make('document_number')->title(__('patients.columns.document_number')),
            Column::make('phone')->title(__('patients.columns.phone')),
            Column::make('created_at')->title(__('datatables.columns.created_at')),
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
        return 'Patients_' . date('YmdHis');
    }
}
