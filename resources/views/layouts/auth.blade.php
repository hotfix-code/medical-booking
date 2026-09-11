<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="">
    <meta name="Author" content="">
    <meta name="keywords" content="">
    <title>{{ __('common.app_name') }}</title>

    <link rel="icon" href="{{ asset('assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">
    <link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" >
    <link href="{{ asset('assets/css/styles.min.css') }}" rel="stylesheet" >
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" >
</head>

<body>

{{ $slot }}

<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/show-password.js') }}"></script>
</body>
</html>
