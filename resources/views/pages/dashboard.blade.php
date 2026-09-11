<x-app-layout>
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1 class="page-title my-auto">{{ __('dashboard.title') }}</h1>
                <div>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0)">{{ __('common.home') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('dashboard.title') }}</li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- ROW-1 -->
            <div class="row">
                @foreach($metrics as $metric)
                    <x-ui.stat-card
                        :title="$metric['title']"
                        :value="$metric['value']"
                        :subtitle="$metric['subtitle']"
                        :change="$metric['change']"
                        :change-icon="$metric['changeIcon']"
                        :change-class="$metric['changeClass']"
                        :chart-id="$metric['chartId']"
                    />
                @endforeach
            </div>
            <!-- ROW-1 END -->

        </div>
    </div>
    <!-- End::app-content -->
</x-app-layout>
