@php
    // $logo=asset(Storage::url('uploads/logo/'));
    $logo = \App\Models\Utility::get_file('uploads/logo');
    $company_favicon = Utility::companyData($invoice->created_by, 'company_favicon');
    $setting = DB::table('settings')->where('created_by', $user->creatorId())->pluck('value', 'name')->toArray();
    $settings_data = \App\Models\Utility::settingsById($invoice->created_by);
    $color = !empty($setting['color']) ? $setting['color'] : 'theme-3';

    if (isset($setting['color_flag']) && $setting['color_flag'] == 'true') {
        $themeColor = 'custom-color';
    } else {
        $themeColor = $color;
    }
    $company_setting = \App\Models\Utility::settingsById($invoice->created_by);
    $getseo = App\Models\Utility::getSeoSetting();
    $metatitle = isset($getseo['meta_title']) ? $getseo['meta_title'] : '';
    $metsdesc = isset($getseo['meta_desc']) ? $getseo['meta_desc'] : '';
    $meta_image = \App\Models\Utility::get_file('uploads/meta/');
    $meta_logo = isset($getseo['meta_image']) ? $getseo['meta_image'] : '';
    $get_cookie = \App\Models\Utility::getCookieSetting();

@endphp
<!DOCTYPE html>

<html lang="en" dir="{{ $settings_data['SITE_RTL'] == 'on' ? 'rtl' : '' }}">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>
        {{ Utility::companyData($invoice->created_by, 'title_text') ? Utility::companyData($invoice->created_by, 'title_text') : config('app.name', 'ERPGO') }}
        - {{ __('MoInvoice') }}</title>

    <meta name="title" content="{{ $metatitle }}">
    <meta name="description" content="{{ $metsdesc }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:title" content="{{ $metatitle }}">
    <meta property="og:description" content="{{ $metsdesc }}">
    <meta property="og:image" content="{{ $meta_image . $meta_logo }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:title" content="{{ $metatitle }}">
    <meta property="twitter:description" content="{{ $metsdesc }}">
    <meta property="twitter:image" content="{{ $meta_image . $meta_logo }}">

    <link rel="icon"
        href="{{ $logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}"
        type="image" sizes="16x16">

    <link rel="stylesheet" href="{{ asset('assets/css/plugins/main.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/animate.min.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">

    <!-- vendor css -->
 

    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" id="main-style-link">

    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.min.css') }}">

    <style>
        :root {
            --color-customColor: <?=$color ?>;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/custom-color.css') }}">
    @stack('css-page')

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        #card-element {
           border: 1px solid #a3afbb !important;
           border-radius: 10px !important;
           padding: 10px !important;
         }
    </style>
</head>

<body class="{{ $themeColor }}">
    <header class="header header-transparent" id="header-main">

    </header>

    <div class="main-content container">
        <div class="row justify-content-between align-items-center mb-3">
            <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="invoice">
                            <div class="invoice-print">
                                <div class="row invoice-title mt-2">
                                    <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                                        <h2 style="text-align:center">{{ __('Invoice') }}</h2>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12" style="text-align:center">
                                    @if (!empty($seller['name']))
                                        <div class="col">
                                            <strong class="font-style">
                                                {{ !empty($seller['businessAddr']) ? $seller['businessAddr'] : '' }}<br>
                                                <strong class="font-style">  {{ __('Tel : ') }}  </strong>{{ !empty($seller['businessPhoneNo']) ? $seller['businessPhoneNo'] : '' }}<br>
                                            </strong>
                                        </div>
                                    @endif
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12" style="text-align:center">
                                        <div class="invoice-number" ">
                                        <strong class="font-style">    Vat :   </strong>{{ !empty($seller['tan']) ? $seller['tan'] : '' }} 
                                        <strong class="font-style"> BRN :   </strong> {{ !empty($seller['brn']) ? $seller['brn'] : '' }} </div>
                                        <div class="float-end mt-3">
                                            @if($settings['invoice_qr_display'] == 'on')
                                            <img src="data:image/png;base64, {{ $invoice->qrCode }}" style="float:right;height:50%;width:50%;" alt="barcode"   />
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-end">
                                        <h3 class="invoice-number" style="text-align:center">
                                       {{ $invoice->created_at }} </h3>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                                        <h4 style="text-align:center">{{ __('Vat Invoice') }}</h4>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                                        <h4 style="text-align:center">{{ __('Cash Sales') }}</h4>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                                        <div class="invoice-number" style="float:left">
                                        <strong class="font-style">    Invoice No. :   </strong>  {{ $invoice->invoice_id }}  </div>
                                        <div class="invoice-number" style="float:right">
                                        <strong class="font-style">   Cashier Id:   </strong> {{ !empty($seller->cashierID ) ? $seller->cashierID  : '' }} </br>  
                                        <strong class="font-style">   Counter No:   </strong> {{ !empty($seller->ebsCounterNo ) ? $seller->ebsCounterNo  : '' }} </br> </div>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                </div>
                                

                     
                                <div class="row mt-4">
                                    <div class="col-md-12">
                               
                                        <div class="table-responsive mt-2">
                                            <table class="table mb-0 table-striped">
                                                <tr>
                                                    <th class="text-dark">{{ __('Product') }}</th>
                                                    <th class="text-dark">{{ __('Quantity') }}</th>
                                                    <th class="text-dark">{{ __('Rate') }}</th>
                                                    <th class="text-dark">{{ __('Discount') }}</th>
                                                    <th class="text-dark">{{ __('Tax') }}</th>
                                                    <th class="text-end text-dark" width="12%">
                                                        {{ __('Price') }}<br>
                                                        <small
                                                            class="text-danger font-weight-bold">{{ __('after tax & discount') }}</small>
                                                    </th>
                                                </tr>
                                                @php
                                                    $totalQuantity = 0;
                                                    $totalRate = 0;
                                                    $totalTaxPrice = 0;
                                                    $totalDiscount = 0;
                                                    $taxesData = [];
                                                @endphp
                                                @foreach ($iteams as $key => $iteam)

                                                    <tr>
                                                        @php
                                                            $productName = $iteam->product;
                                                            $totalRate += $iteam->price;
                                                            $totalQuantity += $iteam->quantity;
                                                            $totalDiscount += $iteam->discount;
                                                        @endphp
                                                        <td>{{ !empty($productName) ? $productName->name : '' }}</td>
                                                        @php
                                                            $unitName = App\Models\ProductServiceUnit::find(
                                                                $iteam->unit,
                                                            );
                                                        @endphp
                                                        <td>{{ $iteam->quantity }}
                                                            {{ $unitName != null ? '(' . $unitName->name . ')' : '' }}
                                                        </td>
                                                        <td>{{ \App\Models\Utility::priceFormat($settings, $iteam->price) }}
                                                        </td>
                                                        <td>{{ \App\Models\Utility::priceFormat($settings, $iteam->discount) }}
                                                        </td>
                                                        <td>
                                                            @if (!empty($iteam->tax))
                                                                <table>
                                                                    @php
                                                                        $itemTaxes = [];
                                                                        $getTaxData = Utility::getTaxData();

                                                                        if (!empty($iteam->tax)) {
                                                                            foreach (
                                                                                explode(',', $iteam->tax)
                                                                                as $tax
                                                                            ) {
                                                                                $taxPrice = \Utility::taxRate(
                                                                                    $getTaxData[$tax]['rate'],
                                                                                    $iteam->price,
                                                                                    $iteam->quantity,
                                                                                );
                                                                                $totalTaxPrice += $taxPrice;
                                                                                $itemTax['name'] =
                                                                                    $getTaxData[$tax]['name'];
                                                                                $itemTax['rate'] =
                                                                                    $getTaxData[$tax]['rate'] . '%';
                                                                                $itemTax[
                                                                                    'price'
                                                                                ] = \App\Models\Utility::priceFormat(
                                                                                    $settings,
                                                                                    $taxPrice,
                                                                                );

                                                                                $itemTaxes[] = $itemTax;
                                                                                if (
                                                                                    array_key_exists(
                                                                                        $getTaxData[$tax]['name'],
                                                                                        $taxesData,
                                                                                    )
                                                                                ) {
                                                                                    $taxesData[
                                                                                        $getTaxData[$tax]['name']
                                                                                    ] =
                                                                                        $taxesData[
                                                                                            $getTaxData[$tax]['name']
                                                                                        ] + $taxPrice;
                                                                                } else {
                                                                                    $taxesData[
                                                                                        $getTaxData[$tax]['name']
                                                                                    ] = $taxPrice;
                                                                                }
                                                                            }
                                                                            $iteam->itemTax = $itemTaxes;
                                                                        } else {
                                                                            $iteam->itemTax = [];
                                                                        }
                                                                    @endphp
                                                                    @foreach ($iteam->itemTax as $tax)
                                                                        <tr>
                                                                            <!--<td>{{ $tax['name'] . ' (' . $tax['rate'] . ')' }}-->
                                                                            <!--</td>-->
                                                                            <td>{{ $tax['price'] }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </table>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>

                                                        <!--<td>{{ !empty($iteam->description) ? $iteam->description : '-' }}-->
                                                        <!--</td>-->
                                                        <td class="text-end">
                                                            {{ Utility::priceFormat($settings, $iteam->price * $iteam->quantity - $iteam->discount + $totalTaxPrice) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tfoot>
                                                    <tr>
                                                        <td><b>{{ __('Total') }}</b></td>
                                                        <td><b>{{ $totalQuantity }}</b></td>
                                                        <td>{{ Utility::priceFormat($settings, $totalRate) }}</td>
                                                        <td><b>{{ Utility::priceFormat($settings, $totalDiscount) }}</b>
                                                        </td>
                                                        <td><b>{{ Utility::priceFormat($settings, $totalTaxPrice) }}</b>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4"></td>
                                                        <td class="text-end"><b>{{ __('Sub Total') }}</b></td>
                                                        <td class="text-end">
                                                            {{ Utility::priceFormat($settings, $invoice->getSubTotal()) }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="4"></td>
                                                        <td class="text-end"><b>{{ __('Discount') }}</b></td>
                                                        <td class="text-end">
                                                            {{ Utility::priceFormat($settings, $invoice->getTotalDiscount()) }}
                                                        </td>
                                                    </tr>

                                                    @if (!empty($taxesData))
                                                        @foreach ($taxesData as $taxName => $taxPrice)
                                                            <tr>
                                                                <td colspan="4"></td>
                                                                <td class="text-end"><b>{{ $taxName }}</b></td>
                                                                <td class="text-end">
                                                                    {{ Utility::priceFormat($settings, $taxPrice) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                    <tr>
                                                        <td colspan="4"></td>
                                                        <td class="blue-text text-end"><b>{{ __('Total') }}</b></td>
                                                        <td class="blue-text text-end">
                                                            {{ Utility::priceFormat($settings, $invoice->getTotal()) }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4"></td>
                                                        <td class="text-end"><b>{{ __('Paid') }}</b></td>
                                                        <td class="text-end">
                                                            {{ Utility::priceFormat($settings, $invoice->getTotal() - $invoice->getDue() - $invoice->invoiceTotalCreditNote()) }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4"></td>
                                                        <td class="text-end"><b>{{ __('Credit Note') }}</b></td>
                                                        <td class="text-end">
                                                            {{ Utility::priceFormat($settings, $invoice->invoiceTotalCreditNote()) }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4"></td>
                                                        <td class="text-end"><b>{{ __('Due') }}</b></td>
                                                        <td class="text-end">
                                                            {{ Utility::priceFormat($settings, $invoice->getDue()) }}
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                        <div class="float-end mt-3">
                                            @if($settings['invoice_qr_display'] == 'on')
                                            <img src="data:image/png;base64, {{ $invoice->qrCode }}" style="float:right;height:50%;width:50%;" alt="barcode"   />
                                            @endif
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          
        </div>

     
    </div>
 
    <footer id="footer-main">
        <div class="footer-dark">
            <div class="container">
                <div class="row align-items-center justify-content-md-between py-4 mt-4 delimiter-top">
                    <div class="col-md-6">
                        <div class="copyright text-sm font-weight-bold text-center text-md-left">
                            {{ !empty($companySettings['footer_text']) ? $companySettings['footer_text']->value : '' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/dash.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/bootstrap-switch-button.min.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/simple-datatables.js') }}"></script>

    <!-- Apex Chart -->
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/main.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>


    <script src="{{ asset('js/jscolor.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>

    @if ($message = Session::get('success'))
        <script>
            show_toastr('success', '{!! $message !!}');
        </script>
    @endif
    @if ($message = Session::get('error'))
        <script>
            show_toastr('error', '{!! $message !!}');
        </script>
    @endif


    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"
        integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous">
    </script>

    <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
 

    
   
     <script type="text/javascript">
    window.print();
</script>
    @if ($get_cookie['enable_cookie'] == 'on')
        @include('layouts.cookie_consent')
    @endif

</body>

</html>
