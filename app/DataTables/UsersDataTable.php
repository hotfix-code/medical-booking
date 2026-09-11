<?php

namespace App\DataTables;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    use TranslatesDataTable;

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
            ->addColumn('role_name', fn (User $user) => Role::label($user->roles[0]->name ?? null))
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
            ->addColumn('action', fn (User $user) => view('components.datatable.actions', [
                'id' => $user->id,
                'permission' => 'user',
                'buttons' => ['edit', 'delete'],
            ]));
    }

    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()
            ->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['doctor', 'patient']);
            });
    }

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
            ->language($this->languageOptions());
    }

    public function getColumns(): array
    {
        return [
            Column::make('full_name')->title(__('users.columns.full_name')),
            Column::make('email')->title(__('users.columns.email')),
            Column::make('role_name')->title(__('users.columns.role_name')),
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
        return 'Users_' . date('YmdHis');
    }
}
