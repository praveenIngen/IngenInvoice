@extends('layouts.admin')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Settings') }}</li>
@endsection

@php
    $lang = \App\Models\Utility::getValByName('default_language');
    // $logo=asset(Storage::url('uploads/logo/'));
    $logo = \App\Models\Utility::get_file('uploads/logo');

    $logo_light = \App\Models\Utility::getValByName('logo_light');
    $logo_dark = \App\Models\Utility::getValByName('logo_dark');
    $company_favicon = \App\Models\Utility::getValByName('company_favicon');
    $setting = \App\Models\Utility::colorset();
    $color = !empty($setting['color']) ? $setting['color'] : 'theme-3';
    $flag = !empty($setting['color_flag']) ? $setting['color_flag'] : '';
    $SITE_RTL = isset($setting['SITE_RTL']) ? $setting['SITE_RTL'] : 'off';
    $meta_image = \App\Models\Utility::get_file('uploads/meta/');
    $google_recaptcha_version = ['v2-checkbox' => __('v2'), 'v3' => __('v3')];
@endphp

{{-- Storage setting --}}
@php
    $file_type = config('files_types');
    $setting = App\Models\Utility::settings();

    $local_storage_validation = $setting['local_storage_validation'];
    $local_storage_validations = explode(',', $local_storage_validation);

    $s3_storage_validation = $setting['s3_storage_validation'];
    $s3_storage_validations = explode(',', $s3_storage_validation);

    $wasabi_storage_validation = $setting['wasabi_storage_validation'];
    $wasabi_storage_validations = explode(',', $wasabi_storage_validation);

@endphp
<style>


</style>

<script>
    // document.addEventListener("DOMContentLoaded", function() {
    //     const body = document.querySelector("body");
    //     const input = document.getElementById("colorPicker");
    //     const colorCode = document.getElementById("colorCode");
    //     const button = document.getElementById("changeColorButton");

    //     setColor();
    //     input.addEventListener("input", setColor);

    //     function setColor() {
    //         // body.style.backgroundColor = input.value;
    //         colorCode.innerHTML = input.value;
    //     }
    // });
</script>
{{-- end Storage setting --}}
@push('css-page')
    @if ($color == 'theme-3')
        <style>
            .btn-check:checked+.btn-outline-primary,
            .btn-check:active+.btn-outline-primary,
            .btn-outline-primary:active,
            .btn-outline-primary.active,
            .btn-outline-primary.dropdown-toggle.show {
                color: #ffffff;
                background-color: #6fd943 !important;
                border-color: #6fd943 !important;
            }

            .btn-outline-primary:hover {
                color: #ffffff;
                background-color: #6fd943 !important;
                border-color: #6fd943 !important;
            }

            .btn[class*="btn-outline-"]:hover {

                border-color: #6fd943 !important;
            }
        </style>
    @endif
    @if ($color == 'theme-2')
        <style>
            .btn-check:checked+.btn-outline-primary,
            .btn-check:active+.btn-outline-primary,
            .btn-outline-primary:active,
            .btn-outline-primary.active,
            .btn-outline-primary.dropdown-toggle.show {
                color: #ffffff;
                background: linear-gradient(141.55deg, rgba(240, 244, 243, 0) 3.46%, #4ebbd3 99.86%)#1f3996 !important;
                border-color: #4ebbd3 !important;
            }

            .btn-outline-primary:hover {
                color: #ffffff;
                background: linear-gradient(141.55deg, rgba(240, 244, 243, 0) 3.46%, #4ebbd3 99.86%)#1f3996 !important;
                border-color: #4ebbd3 !important;
            }

            .btn.btn-outline-primary {
                color: #1F3996;
                border-color: #4ebbd3 !important;
            }
        </style>
    @endif
    @if ($color == 'theme-4')
        <style>
            .btn-check:checked+.btn-outline-primary,
            .btn-check:active+.btn-outline-primary,
            .btn-outline-primary:active,
            .btn-outline-primary.active,
            .btn-outline-primary.dropdown-toggle.show {
                color: #ffffff;
                background-color: #584ed2 !important;
                border-color: #584ed2 !important;

            }

            .btn-outline-primary:hover {
                color: #ffffff;
                background-color: #584ed2 !important;
                border-color: #584ed2 !important;
            }

            .btn.btn-outline-primary {
                color: #584ed2;
                border-color: #584ed2 !important;
            }
        </style>
    @endif
    @if ($color == 'theme-1')
        <style>
            .btn-check:checked+.btn-outline-primary,
            .btn-check:active+.btn-outline-primary,
            .btn-outline-primary:active,
            .btn-outline-primary.active,
            .btn-outline-primary.dropdown-toggle.show {
                color: #ffffff;
                background: linear-gradient(141.55deg, rgba(81, 69, 157, 0) 3.46%, rgba(255, 58, 110, 0.6) 99.86%), #51459d !important;
                border-color: #51459d !important;
            }


            body.theme-1 .btn-outline-primary:hover {
                color: #ffffff;
                background: linear-gradient(141.55deg, rgba(81, 69, 157, 0) 3.46%, rgba(255, 58, 110, 0.6) 99.86%), #51459d !important;
                border-color: #51459d !important;
            }
        </style>
    @endif
@endpush


@push('script-page')
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300,
        })

        $('.colorPicker').on('click', function(e) {
            $('body').removeClass('custom-color');
            if (/^theme-\d+$/) {
                $('body').removeClassRegex(/^theme-\d+$/);
            }
            $('body').addClass('custom-color');
            $('.themes-color-change').removeClass('active_color');
            $(this).addClass('active_color');
            const input = document.getElementById("color-picker");
            setColor();
            input.addEventListener("input", setColor);

            function setColor() {
                $(':root').css('--color-customColor', input.value);
            }

            $(`input[name='color_flag`).val('true');
        });

        $('.themes-color-change').on('click', function() {

            $(`input[name='color_flag`).val('false');

            var color_val = $(this).data('value');
            $('body').removeClass('custom-color');
            if (/^theme-\d+$/) {
                $('body').removeClassRegex(/^theme-\d+$/);
            }
            $('body').addClass(color_val);
            $('.theme-color').prop('checked', false);
            $('.themes-color-change').removeClass('active_color');
            $('.colorPicker').removeClass('active_color');
            $(this).addClass('active_color');
            $(`input[value=${color_val}]`).prop('checked', true);
        });

        $.fn.removeClassRegex = function(regex) {
            return $(this).removeClass(function(index, classes) {
                return classes.split(/\s+/).filter(function(c) {
                    return regex.test(c);
                }).join(' ');
            });
        };

        // storage setting
        $(document).on('change', '[name=storage_setting]', function() {
            if ($(this).val() == 's3') {
                $('.s3-setting').removeClass('d-none');
                $('.wasabi-setting').addClass('d-none');
                $('.local-setting').addClass('d-none');
            } else if ($(this).val() == 'wasabi') {
                $('.s3-setting').addClass('d-none');
                $('.wasabi-setting').removeClass('d-none');
                $('.local-setting').addClass('d-none');
            } else {
                $('.s3-setting').addClass('d-none');
                $('.wasabi-setting').addClass('d-none');
                $('.local-setting').removeClass('d-none');
            }
        });
    </script>

    <script>
        document.getElementById('logo_dark').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image').src = src
        }
        document.getElementById('logo_light').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image1').src = src
        }
        document.getElementById('favicon').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image2').src = src
        }
    </script>

    <script type="text/javascript">
        $(document).on("click", '.send_email', function(e) {
            e.preventDefault();
            var title = $(this).attr('data-title');
            var size = 'md';
            var url = $(this).attr('data-url');

            if (typeof url != 'undefined') {
                $("#commonModal .modal-title").html(title);
                $("#commonModal .modal-dialog").addClass('modal-' + size);
                $("#commonModal").modal('show');


                $.post(url, {
                    _token: '{{ csrf_token() }}',
                    mail_driver: $("#mail_driver").val(),
                    mail_host: $("#mail_host").val(),
                    mail_port: $("#mail_port").val(),
                    mail_username: $("#mail_username").val(),
                    mail_password: $("#mail_password").val(),
                    mail_encryption: $("#mail_encryption").val(),
                    mail_from_address: $("#mail_from_address").val(),
                    mail_from_name: $("#mail_from_name").val(),

                }, function(data) {
                    $('#commonModal .body').html(data);
                });
            }
        });
        $(document).on('submit', '#test_email', function(e) {
            e.preventDefault();
            // $("#email_sending").show();
            var post = $(this).serialize();
            var url = $(this).attr('action');
            $.ajax({
                type: "post",
                url: url,
                data: post,
                cache: false,
                beforeSend: function() {
                    $('#test_email .btn-create').attr('disabled', 'disabled');
                },
                success: function(data) {
                    // console.log(data)
                    if (data.success) {
                        show_toastr('success', data.message, 'success');
                    } else {
                        show_toastr('error', data.message, 'error');
                    }
                    // $("#email_sending").hide();
                    $('#commonModal').modal('hide');


                },
                complete: function() {
                    $('#test_email .btn-create').removeAttr('disabled');
                },
            });
        });
    </script>

    {{--    for cookie setting --}}
    <script type="text/javascript">
        function enablecookie() {
            const element = $('#enable_cookie').is(':checked');
            $('.cookieDiv').addClass('disabledCookie');
            if (element == true) {
                $('.cookieDiv').removeClass('disabledCookie');
                $("#cookie_logging").attr('checked', true);
            } else {
                $('.cookieDiv').addClass('disabledCookie');
                $("#cookie_logging").attr('checked', false);
            }
        }
    </script>

    <script>
        if ($('#cust-darklayout').length > 0) {
            var custthemedark = document.querySelector("#cust-darklayout");
            custthemedark.addEventListener("click", function() {
                if (custthemedark.checked) {
                    $('#main-style-link').attr('href', '{{ config('app.url') }}' +
                        '/public/assets/css/style-dark.css');
                    document.body.style.background = 'linear-gradient(141.55deg, #22242C 3.46%, #22242C 99.86%)';

                    $('.dash-sidebar .main-logo a img').attr('src',
                        '{{ isset($logo_light) && !empty($logo_light) ? $logo . $logo_light : $logo . '/logo-light.png' }}'
                        );

                } else {
                    $('#main-style-link').attr('href', '{{ config('app.url') }}' + '/public/assets/css/style.css');
                    document.body.style.setProperty('background',
                        'linear-gradient(141.55deg, rgba(240, 244, 243, 0) 3.46%, #f0f4f3 99.86%)', 'important');

                    $('.dash-sidebar .main-logo a img').attr('src',
                        '{{ isset($logo_light) && !empty($logo_light) ? $logo . $logo_light : $logo . '/logo-dark.png' }}'
                        );

                }
            });
        }

        if ($('#cust-theme-bg').length > 0) {
            var custthemebg = document.querySelector("#cust-theme-bg");
            custthemebg.addEventListener("click", function() {
                if (custthemebg.checked) {
                    document.querySelector(".dash-sidebar").classList.add("transprent-bg");
                    document
                        .querySelector(".dash-header:not(.dash-mob-header)")
                        .classList.add("transprent-bg");
                } else {
                    document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
                    document
                        .querySelector(".dash-header:not(.dash-mob-header)")
                        .classList.remove("transprent-bg");
                }
            });
        }
    </script>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Settings') }}</li>
@endsection


@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#brand-settings"
                                class="list-group-item list-group-item-action border-0">{{ __('Brand Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                        
                        </div>
                    </div>
                </div>

                <div class="col-xl-9">
                    {{--  Start for all settings tab --}}

                    <!--Site Settings-->
                    <div id="brand-settings" class="card">
                        <div class="card-header">
                            <h5>{{ __('Brand Settings') }}</h5>
                        </div>
                        {{ Form::model($settings, ['url' => 'systems', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-md-6">
                                    <div class="card logo_card">
                                        <div class="card-header">
                                            <h5>{{ __('Logo dark') }}</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="setting-card">
                                                <div class="logo-content mt-4">
                                                    <img id="image"
                                                        src="{{ $logo . '/' . (isset($logo_dark) && !empty($logo_dark) ? $logo_dark : 'logo-dark.png') . '?timestamp=' . time() }}"
                                                        class="big-logo">
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="logo_dark">
                                                        <div class=" bg-primary company_logo_update"> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="logo_dark" id="logo_dark"
                                                            class="form-control file" data-filename="logo_dark">
                                                    </label>
                                                </div>
                                                @error('logo_dark')
                                                    <div class="row">
                                                        <span class="invalid-logo" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-md-6">
                                    <div class="card logo_card">
                                        <div class="card-header">
                                            <h5>{{ __('Logo Light') }}</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class=" setting-card">
                                                <div class="logo-content mt-4">
                                                    <img id="image1"
                                                        src="{{ $logo . '/' . (isset($logo_light) && !empty($logo_light) ? $logo_light : 'logo-light.png') . '?timestamp=' . time() }}"
                                                        class="big-logo img_setting">
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="logo_light">
                                                        <div class=" bg-primary dark_logo_update"> <i
                                                                class="ti ti-upload px-1">
                                                            </i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="logo_light" id="logo_light"
                                                            class="form-control file" data-filename="logo_light">
                                                    </label>
                                                </div>
                                                @error('logo_light')
                                                    <div class="row">
                                                        <span class="invalid-logo" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-md-6">
                                    <div class="card logo_card">
                                        <div class="card-header">
                                            <h5>{{ __('Favicon') }}</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class=" setting-card">
                                                <div class="logo-content mt-4">
                                                    <img id="image2"
                                                        src="{{ $logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') . '?timestamp=' . time() }}"
                                                        width="50px" class="img_setting">
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="favicon">
                                                        <div class="bg-primary company_favicon_update"> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" class="form-control file" id="favicon"
                                                            name="favicon" data-filename="favicon">
                                                    </label>
                                                </div>
                                                @error('favicon')
                                                    <div class="row">
                                                        <span class="invalid-logo" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('title_text', __('Title Text'), ['class' => 'form-label']) }}
                                            {{ Form::text('title_text', null, ['class' => 'form-control', 'placeholder' => __('Title Text')]) }}
                                            @error('title_text')
                                                <span class="invalid-title_text" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('footer_text', __('Footer Text'), ['class' => 'form-label']) }}
                                            {{ Form::text('footer_text', Utility::getValByName('footer_text'), ['class' => 'form-control', 'placeholder' => __('Enter Footer Text')]) }}
                                            @error('footer_text')
                                                <span class="invalid-footer_text" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('default_language', __('Default Language'), ['class' => 'form-label']) }}
                                            <div class="changeLanguage">
                                                <select name="default_language" id="default_language"
                                                    class="form-control select">
                                                    @foreach (\App\Models\Utility::languages() as $code => $language)
                                                        <option @if ($lang == $code) selected @endif
                                                            value="{{ $code }}">
                                                            {{ ucFirst($language) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('default_language')
                                                <span class="invalid-default_language" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <div class="custom-control custom-switch">
                                            <label class="mb-1 mt-3" for="SITE_RTL">{{ __('Enable RTL') }}</label>
                                            <div class="">
                                                <input type="checkbox" name="SITE_RTL" id="SITE_RTL"
                                                    data-toggle="switchbutton" data-onstyle="primary"
                                                    {{ $settings['SITE_RTL'] == 'on' ? 'checked="checked"' : '' }}>
                                                <label class="custom-control-label" for="SITE_RTL"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="mb-1 mt-3"
                                                for="display_landing_page">{{ __('Enable Landing Page') }}</label>
                                            <div class="">
                                                <input type="checkbox" name="display_landing_page"
                                                    class="form-check-input" id="display_landing_page"
                                                    data-toggle="switchbutton"
                                                    {{ Utility::getValByName('display_landing_page') == 'on' ? 'checked' : '' }}
                                                    data-onstyle="primary">
                                                <label class="form-check-label" for="display_landing_page"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="mb-1 mt-3"
                                                for="signup_button">{{ __('Enable Sign-Up Page') }}</label>
                                            <div class="">
                                                <input type="checkbox" name="enable_signup" id="enable_signup"
                                                    data-toggle="switchbutton"
                                                    {{ $settings['enable_signup'] == 'on' ? 'checked="checked"' : '' }}
                                                    data-onstyle="primary">
                                                <label class="form-check-label" for="enable_signup"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="form-group">
                                            <label class="mb-1 mt-3"
                                                for="email_verification">{{ __('Email Verification') }}</label>
                                            <div class="">
                                                <input type="checkbox" name="email_verification" id="email_verification"
                                                    data-toggle="switchbutton"
                                                    {{ $settings['email_verification'] == 'on' ? 'checked="checked"' : '' }}
                                                    data-onstyle="primary">
                                                <label class="form-check-label" for="email_verification"></label>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <h4 class="small-title">{{ __('Theme Customizer') }}</h4>
                                <div class="setting-card setting-logo-box p-3">
                                    <div class="row">
                                        <div class="col-lg-4 col-xl-4 col-md-4">
                                            <h6 class="mt-2">
                                                <i data-feather="credit-card"
                                                    class="me-2"></i>{{ __('Primary color settings') }}
                                            </h6>

                                            <hr class="my-2" />
                                            <div class="color-wrp">
                                                <div class="theme-color themes-color">
                                                    <a href="#!"
                                                        class="themes-color-change {{ $color == 'theme-1' ? 'active_color' : '' }}"
                                                        data-value="theme-1"></a>
                                                    <input type="radio" class="theme_color d-none" name="color"
                                                        value="theme-1"{{ $color == 'theme-1' ? 'checked' : '' }}>
                                                    <a href="#!"
                                                        class="themes-color-change {{ $color == 'theme-2' ? 'active_color' : '' }}"
                                                        data-value="theme-2"></a>
                                                    <input type="radio" class="theme_color d-none" name="color"
                                                        value="theme-2"{{ $color == 'theme-2' ? 'checked' : '' }}>
                                                    <a href="#!"
                                                        class="themes-color-change {{ $color == 'theme-3' ? 'active_color' : '' }}"
                                                        data-value="theme-3"></a>
                                                    <input type="radio" class="theme_color d-none" name="color"
                                                        value="theme-3"{{ $color == 'theme-3' ? 'checked' : '' }}>
                                                    <a href="#!"
                                                        class="themes-color-change {{ $color == 'theme-4' ? 'active_color' : '' }}"
                                                        data-value="theme-4"></a>
                                                    <input type="radio" class="theme_color d-none" name="color"
                                                        value="theme-4"{{ $color == 'theme-4' ? 'checked' : '' }}>
                                                    <a href="#!"
                                                        class="themes-color-change {{ $color == 'theme-5' ? 'active_color' : '' }}"
                                                        data-value="theme-5"></a>
                                                    <input type="radio" class="theme_color d-none" name="color"
                                                        value="theme-5"{{ $color == 'theme-5' ? 'checked' : '' }}>
                                                    <br>
                                                    <a href="#!"
                                                        class="themes-color-change {{ $color == 'theme-6' ? 'active_color' : '' }}"
                                                        data-value="theme-6"></a>
                                                    <input type="radio" class="theme_color d-none" name="color"
                                                        value="theme-6"{{ $color == 'theme-6' ? 'checked' : '' }}>
                                                    <a href="#!"
                                                        class="themes-color-change {{ $color == 'theme-7' ? 'active_color' : '' }}"
                                                        data-value="theme-7"></a>
                                                    <input type="radio" class="theme_color d-none" name="color"
                                                        value="theme-7"{{ $color == 'theme-7' ? 'checked' : '' }}>
                                                    <a href="#!"
                                                        class="themes-color-change {{ $color == 'theme-8' ? 'active_color' : '' }}"
                                                        data-value="theme-8"></a>
                                                    <input type="radio" class="theme_color d-none" name="color"
                                                        value="theme-8"{{ $color == 'theme-8' ? 'checked' : '' }}>
                                                    <a href="#!"
                                                        class="themes-color-change {{ $color == 'theme-9' ? 'active_color' : '' }}"
                                                        data-value="theme-9"></a>
                                                    <input type="radio" class="theme_color d-none" name="color"
                                                        value="theme-9"{{ $color == 'theme-9' ? 'checked' : '' }}>
                                                    <a href="#!"
                                                        class="themes-color-change {{ $color == 'theme-10' ? 'active_color' : '' }}"
                                                        data-value="theme-10"></a>
                                                    <input type="radio" class="theme_color d-none" name="color"
                                                        value="theme-10"{{ $color == 'theme-10' ? 'checked' : '' }}>
                                                </div>
                                                <div class="color-picker-wrp">
                                                    <input type="color" value="{{ $color ? $color : '' }}"
                                                        class="colorPicker {{ isset($flag) && $flag == 'true' ? 'active_color' : '' }}"
                                                        name="custom_color" id="color-picker">
                                                    <input type='hidden' name="color_flag"
                                                        value={{ isset($flag) && $flag == 'true' ? 'true' : 'false' }}>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-xl-4 col-md-4">
                                            <h6 class="mt-2">
                                                <i data-feather="layout" class="me-2"></i>{{ __('Sidebar settings') }}
                                            </h6>
                                            <hr class="my-2" />
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="cust-theme-bg"
                                                    name="cust_theme_bg"
                                                    {{ !empty($settings['cust_theme_bg']) && $settings['cust_theme_bg'] == 'on' ? 'checked' : '' }} />
                                                <label class="form-check-label f-w-600 pl-1"
                                                    for="cust-theme-bg">{{ __('Transparent layout') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-xl-4 col-md-4">
                                            <h6 class="mt-2">
                                                <i data-feather="sun" class="me-2"></i>{{ __('Layout settings') }}
                                            </h6>
                                            <hr class="my-2" />
                                            <div class="form-check form-switch mt-2">
                                                <input type="checkbox" class="form-check-input" id="cust-darklayout"
                                                    name="cust_darklayout"{{ !empty($settings['cust_darklayout']) && $settings['cust_darklayout'] == 'on' ? 'checked' : '' }} />
                                                <label class="form-check-label f-w-600 pl-1"
                                                    for="cust-darklayout">{{ __('Dark Layout') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <div class="form-group">
                                        <input class="btn btn-print-invoice btn-primary m-r-10" type="submit"
                                            value="{{ __('Save Changes') }}">
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
