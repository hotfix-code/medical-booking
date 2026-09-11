<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ __('common.app_name') }}</title>
    <meta name="Description" content="">
    <meta name="Author" content="">
    <meta name="keywords" content="">
    <link rel="icon" href="{{ asset('assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">
    <link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" >
    <link href="{{ asset('assets/css/styles.min.css') }}" rel="stylesheet" >
</head>
<body class="bg-white">
<div class="row authentication coming-soon mx-0 justify-content-center">
    <div class="col-xxl-8 col-xl-8 col-lg-8 col-12">
        <div class="authentication-cover text-fixed-white">
            <div class="aunthentication-cover-content text-center py-5 px-sm-5 px-0">
                <div class="row justify-content-center align-items-center h-100">
                    <div class="col-xxl-6 col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                        <h1 class="display-1 text-fixed-white">4<span class="text-center custom-emoji"><svg xmlns="http://www.w3.org/2000/svg" height="140" width="140" data-name="Layer 1" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="#a2a1ff"></circle><path fill="#6563ff" d="M15.999,17a.99764.99764,0,0,1-.59912-.2002l-.7334-.5498-.73291.5498a.99755.99755,0,0,1-1.20019,0L12,16.25l-.7334.5498a.9999.9999,0,0,1-1.20019-1.5996l1.33349-1a.99757.99757,0,0,1,1.2002,0l.7334.5498.73291-.5498a.99755.99755,0,0,1,1.20019,0l1.3335,1A1.00013,1.00013,0,0,1,15.999,17Z"></path><path fill="#6563ff" d="M13.33252 17a.9976.9976 0 0 1-.59912-.2002L12 16.25l-.7334.5498a.99755.99755 0 0 1-1.20019 0L9.3335 16.25l-.7334.5498a.9999.9999 0 0 1-1.2002-1.5996l1.3335-1a.99755.99755 0 0 1 1.20019 0l.73291.5498.7334-.5498a.99757.99757 0 0 1 1.2002 0l1.33349 1A1.00013 1.00013 0 0 1 13.33252 17zM8.37109 12.5a1 1 0 0 1-.707-1.707L8.457 10l-.793-.793A.99989.99989 0 0 1 9.07812 7.793l1.5 1.5a.99962.99962 0 0 1 0 1.41406l-1.5 1.5A.99676.99676 0 0 1 8.37109 12.5zM15.87109 12.5a.99678.99678 0 0 1-.707-.293l-1.5-1.5a.99964.99964 0 0 1 0-1.41406l1.5-1.5A.99989.99989 0 0 1 16.57812 9.207l-.793.793.793.793a1 1 0 0 1-.707 1.707z"></path></svg></span>4
                        </h1>
                        <div class="m-4">
                            <p class="fs-16">{{ __('common.errors.not_found') }}</p>
                        </div>
                        <div class="text-center">
                            <a class="btn btn-secondary d-inline-flex gap-1" href="{{ route('dashboard') }}"> <i class="ri-arrow-left-line my-auto "></i> {{ __('common.actions.back_to_home') }} </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
