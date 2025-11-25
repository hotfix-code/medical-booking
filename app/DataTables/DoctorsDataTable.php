<?php

namespace App\DataTables;

use App\Enums\Role;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DoctorsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Doctor> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return new EloquentDataTable($query)
            ->setRowId('id')

            ->addColumn('full_name', fn(Doctor $doctor) => $doctor->user->full_name)
            ->filterColumn('full_name', function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->whereRaw("CONCAT(firstname, ' ', lastname) like ?", ["%{$keyword}%"]);
                });
            })

            ->addColumn('document_type', fn(Doctor $doctor) => $doctor->documentType->code)
            ->filterColumn('document_type', function ($query, $keyword) {
                $query->whereHas('documentType', function ($q) use ($keyword) {
                    $q->where('code', 'like', "%{$keyword}%");
                });
            })

            ->editColumn('phone', function(Doctor $doctor) {
                $user = auth()->user();
                return $user->role == Role::Patient->value
                    ? '******'
                    : $doctor->phone ?? 'N/A';
            })

            ->editColumn('created_at', fn(Doctor $doctor) => $doctor->created_at->format('Y-m-d H:ia'))
            ->addColumn(
                'action',
                fn(Doctor $doctor) => view('components.datatable.actions', [
                    'id' => $doctor->id,
                    'permission' => 'doctor',
                    'buttons' => ['edit', 'delete'],
                ])
            );
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Doctor>
     */
    public function query(Doctor $model): QueryBuilder
    {
        return $model->newQuery()->with(['user', 'documentType']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
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
//            ->buttons([
//                Button::make('excel'),
//                Button::make('csv'),
//                Button::make('pdf'),
//                Button::make('print'),
//                Button::make('reset'),
//                Button::make('reload')
//            ])
        ;
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('full_name')->title('Doctor Name'),
            Column::make('document_type')->title('Doc Type'),
            Column::make('document_number')->title('Doc Number'),
            Column::make('license_number')->title('License'),
            Column::make('phone')->title('Phone'),
            Column::make('created_at')->title('Created'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Doctors_' . date('YmdHis');
    }
}
