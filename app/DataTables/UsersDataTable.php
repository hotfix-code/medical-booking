<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<User> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return new EloquentDataTable($query)
            ->setRowId('id')

            ->addColumn('full_name', function (User $user) {
                return $user->full_name;
            })
            ->filterColumn('full_name', function ($query, $keyword) {
                $query->whereRaw("CONCAT(firstname, ' ', lastname) like ?", ["%{$keyword}%"]);
            })

            ->addColumn('role_name', fn(User $user) => ucwords($user->roles[0]->name ?? 'N/A', ' -'))
            ->filterColumn('role_name', function ($query, $keyword) {
                $query->whereHas('roles', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })

            ->editColumn('created_at', function (User $user) {
                return $user->created_at->format('Y-m-d H:ia');
            })
            ->editColumn('updated_at', function (User $user) {
                return $user->updated_at->format('Y-m-d H:ia');
            })
            ->addColumn('action', fn(User $user) => view('components.datatable.actions', [
                'id' => $user->id,
                'permission' => 'user',
                'buttons' => ['edit', 'delete'],
            ]));
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()
            ->whereDoesntHave('roles', function ($q)
            {
                $q->whereIn('name', ['doctor', 'patient']);
            });
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('users-table')
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
            Column::make('full_name')->title('Name'),
            Column::make('email')->title('Email'),
            Column::make('role_name')->title('Role'),
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
        return 'Users_' . date('YmdHis');
    }
}
