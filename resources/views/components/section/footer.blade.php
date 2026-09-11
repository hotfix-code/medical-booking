<footer class="footer mt-auto py-3 text-center">
    <div class="container">
        <span>
            {{ __('common.footer.copyright', ['year' => date('Y')]) }}
            <a href="javascript:void(0);" class="text-primary">{{ __('common.app_name') }}</a>.
            {{ __('common.footer.designed_with') }} <i class="ri ri-heart-3-fill text-danger"></i> {{ __('common.footer.by') }}
            <a href="javascript:void(0);">
                <span class="text-primary">{{ __('common.footer.author') }}</span>
            </a>
            {{ __('common.footer.all_rights_reserved') }}
        </span>
    </div>
</footer>
