<?php

namespace App\DataTables;

use App\Models\Specialty;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SpecialtiesDataTable extends DataTable
{
    use TranslatesDataTable;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return new EloquentDataTable($query)
            ->setRowId('id')
            ->editColumn('description', function (Specialty $specialty) {
                return $specialty->description ? Str::limit($specialty->description, 50) : __('common.states.na');
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

    public function query(Specialty $model): QueryBuilder
    {
        return $model->newQuery();
    }

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
            ->language($this->languageOptions());
    }

    public function getColumns(): array
    {
        return [
            Column::make('name')->title(__('specialties.columns.name')),
            Column::make('description')->title(__('specialties.columns.description')),
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
        return 'Specialties_' . date('YmdHis');
    }
}
