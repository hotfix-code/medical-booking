@props([
    'body',
])

<div class="card custom-card dashboard-week-card dashboard-illustration-card">
    <div class="card-body">
        <img
            src="{{ asset('assets/images/dashboard-all-in-order.jpg') }}"
            alt=""
            class="dashboard-illustration-image"
        >
        <div class="dashboard-illustration-copy">
            <h3 class="dashboard-illustration-title">{{ __('dashboard.illustration.title') }}</h3>
            <p class="dashboard-illustration-body">{{ $body }}</p>
            <p class="dashboard-illustration-quote">
                “{{ __('dashboard.illustration.quote') }}”
                <i class="bx bxs-heart"></i>
            </p>
        </div>
    </div>
</div>
