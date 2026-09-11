<?php

namespace App\DataTables;

use App\Models\ConsultingRoom;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ConsultingRoomsDataTable extends DataTable
{
    use TranslatesDataTable;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return new EloquentDataTable($query)
            ->setRowId('id')
            ->editColumn('location', function (ConsultingRoom $consultingRoom) {
                return $consultingRoom->location ?? __('common.states.na');
            })
            ->editColumn('created_at', function (ConsultingRoom $consultingRoom) {
                return $consultingRoom->created_at->format('Y-m-d H:ia');
            })
            ->editColumn('updated_at', function (ConsultingRoom $consultingRoom) {
                return $consultingRoom->updated_at->format('Y-m-d H:ia');
            })
            ->addColumn('action', function (ConsultingRoom $consultingRoom) {
                return view('components.datatable.actions', [
                    'id' => $consultingRoom->id,
                    'permission' => 'consulting_room',
                    'buttons' => ['edit', 'delete'],
                ]);
            });
    }

    public function query(ConsultingRoom $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('consulting-rooms-table')
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
            Column::make('name')->title(__('consulting_rooms.columns.name')),
            Column::make('location')->title(__('consulting_rooms.columns.location')),
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
        return 'ConsultingRooms_' . date('YmdHis');
    }
}
