<?php

namespace App\DataTables;

use App\Models\DocumentType;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DocumentTypesDataTable extends DataTable
{
    use TranslatesDataTable;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return new EloquentDataTable($query)
            ->setRowId('id')
            ->editColumn('created_at', function (DocumentType $documentType) {
                return $documentType->created_at->format('Y-m-d H:ia');
            })
            ->editColumn('updated_at', function (DocumentType $documentType) {
                return $documentType->updated_at->format('Y-m-d H:ia');
            })
            ->addColumn('action', function (DocumentType $documentType) {
                return view('components.datatable.actions', [
                    'id' => $documentType->id,
                    'permission' => 'document_type',
                    'buttons' => ['edit', 'delete'],
                ]);
            });
    }

    public function query(DocumentType $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('document-types-table')
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
            Column::make('name')->title(__('document_types.columns.name')),
            Column::make('code')->title(__('document_types.columns.code')),
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
        return 'DocumentTypes_' . date('YmdHis');
    }
}
