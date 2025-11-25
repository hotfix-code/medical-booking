<?php

namespace App\DataTables;

use App\Models\ConsultingRoom;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ConsultingRoomsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<ConsultingRoom> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return new EloquentDataTable($query)
            ->setRowId('id')
            ->editColumn('location', function (ConsultingRoom $consultingRoom) {
                return $consultingRoom->location ?? 'N/A';
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

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<ConsultingRoom>
     */
    public function query(ConsultingRoom $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
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
            Column::make('location'),
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
        return 'ConsultingRooms_' . date('YmdHis');
    }
}
