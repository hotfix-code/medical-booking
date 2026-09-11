<?php

namespace App\DataTables;

use App\Enums\Role;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DoctorsDataTable extends DataTable
{
    use TranslatesDataTable;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return new EloquentDataTable($query)
            ->setRowId('id')
            ->addColumn('full_name', fn (Doctor $doctor) => $doctor->user->full_name)
            ->filterColumn('full_name', function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->whereRaw("CONCAT(firstname, ' ', lastname) like ?", ["%{$keyword}%"]);
                });
            })
            ->addColumn('document_type', fn (Doctor $doctor) => $doctor->documentType->code)
            ->filterColumn('document_type', function ($query, $keyword) {
                $query->whereHas('documentType', function ($q) use ($keyword) {
                    $q->where('code', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('phone', function (Doctor $doctor) {
                $user = auth()->user();

                return $user->role == Role::Patient->value
                    ? '******'
                    : $doctor->phone ?? __('common.states.na');
            })
            ->editColumn('created_at', fn (Doctor $doctor) => $doctor->created_at->format('Y-m-d H:ia'))
            ->addColumn(
                'action',
                fn (Doctor $doctor) => view('components.datatable.actions', [
                    'id' => $doctor->id,
                    'permission' => 'doctor',
                    'buttons' => ['edit', 'delete'],
                ])
            );
    }

    public function query(Doctor $model): QueryBuilder
    {
        return $model->newQuery()->with(['user', 'documentType']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('doctors-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(5)
            ->selectStyleSingle()
            ->responsive()
            ->autoWidth(false)
            ->language($this->languageOptions());
    }

    public function getColumns(): array
    {
        return [
            Column::make('full_name')->title(__('doctors.columns.full_name')),
            Column::make('document_type')->title(__('doctors.columns.document_type')),
            Column::make('document_number')->title(__('doctors.columns.document_number')),
            Column::make('license_number')->title(__('doctors.columns.license_number')),
            Column::make('phone')->title(__('doctors.columns.phone')),
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
        return 'Doctors_' . date('YmdHis');
    }
}
