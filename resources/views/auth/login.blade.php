<x-auth-layout>
    <div class="autentication-bg">
        <div class="container-lg">
            <div class="row justify-content-center authentication authentication-basic align-items-center h-100">
                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-6 col-sm-8 col-12">
                    <div class="my-4 d-flex justify-content-center">
                        <a href="javascript:void(0);">
                            <img src="{{ asset('assets/images/brand-logos/logo.png') }}" alt="logo">
                        </a>
                    </div>
                    <form
                        method="POST"
                        action="{{ route('login') }}"
                        class="card custom-card"
                    >
                        @csrf
                        <div class="card-body p-5">
                            <p class="h5 fw-semibold mb-2 text-center">{{ __('auth.login.title') }}</p>
                            <p class="mb-4 text-muted op-7 fw-normal text-center">{{ __('auth.login.welcome') }}</p>

                            @if($errors->isNotEmpty())
                                <div class="alert alert-danger" role="alert">
                                    <strong>{{ __('auth.login.oops') }}</strong>
                                    {{ __('auth.login.invalid_credentials') }}
                                </div>
                            @endif

                            <div class="row gy-3">
                                <div class="col-xl-12">
                                    <label for="signin-username" class="form-label text-default">{{ __('auth.login.user_name') }}</label>
                                    <input type="email"
                                           class="form-control form-control-lg"
                                           id="signin-username"
                                           placeholder="{{ __('auth.login.user_name') }}"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required
                                           autofocus
                                    >
                                </div>
                                <div class="col-xl-12 mb-2">
                                    <label for="signin-password" class="form-label text-default d-block">
                                        {{ __('auth.login.password') }}
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control form-control-lg"
                                               id="signin-password"
                                               placeholder="{{ __('auth.login.password') }}"
                                               name="password"
                                               required
                                        >
                                        <button class="btn btn-light" type="button" onclick="createpassword('signin-password',this)" id="button-addon2"><i class="ri-eye-off-line align-middle"></i></button>
                                    </div>
                                    <div class="mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                            <label class="form-check-label text-muted fw-normal" for="defaultCheck1">
                                                {{ __('auth.login.remember') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 d-grid mt-2">
                                    <button class="btn btn-lg btn-primary" type="submit">{{ __('auth.login.submit') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-auth-layout>
