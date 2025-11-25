<?php

namespace App\DataTables;

use App\Enums\Role;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PatientsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Patient> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return new EloquentDataTable($query)
            ->setRowId('id')

            ->addColumn('full_name', fn(Patient $patient) => $patient->user->full_name)
            ->filterColumn('full_name', function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->whereRaw("CONCAT(firstname, ' ', lastname) like ?", ["%{$keyword}%"]);
                });
            })

            ->addColumn('document_type', fn(Patient $patient) => $patient->documentType->code)
            ->filterColumn('document_type', function ($query, $keyword) {
                $query->whereHas('documentType', function ($q) use ($keyword) {
                    $q->where('code', 'like', "%{$keyword}%");
                });
            })

            ->editColumn('created_at', fn(Patient $patient) => $patient->created_at->format('Y-m-d H:ia'))
            ->editColumn('phone', fn(Patient $patient) => $patient->phone ?? 'N/A')
            ->addColumn('action', function (Patient $patient) {
                return view('components.datatable.actions', [
                    'id' => $patient->id,
                    'permission' => 'patient',
                    'buttons' => ['edit', 'delete'],
                ]);
            });
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Patient>
     */
    public function query(Patient $model): QueryBuilder
    {
        $user = auth()->user();

        $query = $user->role == Role::Doctor->value
            ? $model->forDoctor($user->doctor->id)
            : $model->newQuery();

        return $query->with(['user', 'documentType']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
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
            Column::make('full_name')->title('Patient Name'),
            Column::make('document_type')->title('Doc. Type'),
            Column::make('document_number')->title('Doc. Number'),
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
        return 'Patients_' . date('YmdHis');
    }
}
