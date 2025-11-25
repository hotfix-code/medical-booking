<?php

namespace App\DataTables;

use App\Models\Specialty;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SpecialtiesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Specialty> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return new EloquentDataTable($query)
            ->setRowId('id')
            ->editColumn('description', function (Specialty $specialty) {
                return $specialty->description ? Str::limit($specialty->description, 50) : 'N/A';
            })
            ->editColumn('created_at', function (Specialty $specialty) {
                return $specialty->created_at->format('Y-m-d H:ia');
            })
            ->editColumn('updated_at', function (Specialty $specialty) {
                return $specialty->updated_at->format('Y-m-d H:ia');
            })
            ->addColumn('action', function (Specialty $specialty) {
                return view('components.datatable.actions', [
                    'id' => $specialty->id,
                    'permission' => 'specialty',
                    'buttons' => ['view', 'edit', 'delete'],
                ]);
            });
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Specialty>
     */
    public function query(Specialty $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('specialties-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(2)
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
            Column::make('name'),
            Column::make('description'),
            Column::make('created_at')->title('Created'),
            Column::make('updated_at')->title('Updated'),
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
        return 'Specialties_' . date('YmdHis');
    }
}
