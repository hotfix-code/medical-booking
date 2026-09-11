<?php

namespace App\DataTables;

trait TranslatesDataTable
{
    protected function languageOptions(): array
    {
        return [
            'emptyTable' => __('datatables.empty_table'),
            'info' => __('datatables.info'),
            'infoEmpty' => __('datatables.info_empty'),
            'infoFiltered' => __('datatables.info_filtered'),
            'lengthMenu' => __('datatables.length_menu'),
            'loadingRecords' => __('datatables.loading'),
            'processing' => __('datatables.processing'),
            'search' => __('datatables.search'),
            'zeroRecords' => __('datatables.zero_records'),
            'paginate' => [
                'first' => __('datatables.paginate.first'),
                'last' => __('datatables.paginate.last'),
                'next' => __('datatables.paginate.next'),
                'previous' => __('datatables.paginate.previous'),
            ],
        ];
    }
}
