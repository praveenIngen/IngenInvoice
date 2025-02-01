@php
    use App\Models\Utility;
    $setting = \App\Models\Utility::settings();
    $logo = \App\Models\Utility::get_file('uploads/logo');

    $company_logo = $setting['company_logo_dark'] ?? '';
    $company_logos = $setting['company_logo_light'] ?? '';
    $company_small_logo = $setting['company_small_logo'] ?? '';

    $emailTemplate = \App\Models\EmailTemplate::emailTemplateData();
    $lang = Auth::user()->lang;

    $userPlan = \App\Models\Plan::getPlan(\Auth::user()->show_dashboard());
@endphp

@if (isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on')
    <nav class="dash-sidebar light-sidebar transprent-bg">
    @else
        <nav class="dash-sidebar light-sidebar ">
@endif
<div class="navbar-wrapper">
    <div class="m-header main-logo">
        <a href="#" class="b-brand">
            {{--                <img src="{{ asset(Storage::url('uploads/logo/'.$logo)) }}" alt="{{ env('APP_NAME') }}" class="logo logo-lg" /> --}}

            @if ($setting['cust_darklayout'] && $setting['cust_darklayout'] == 'on')
                <img src="{{ $logo . '/' . (isset($company_logos) && !empty($company_logos) ? $company_logos : 'logo-dark.png') }}"
                    alt="{{ config('app.name', 'ERPGo-SaaS') }}" class="logo logo-lg">
            @else
                <img src="{{ $logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-light.png') }}"
                    alt="{{ config('app.name', 'ERPGo-SaaS') }}" class="logo logo-lg">
            @endif

        </a>
    </div>
    <div class="navbar-content">
        @if (\Auth::user()->type != 'client')
            <ul class="dash-navbar">
             
                <!--------------------- End Dashboard ----------------------------------->
                @if (Gate::check('show hrm dashboard') ||
                        Gate::check('show project dashboard') ||
                        Gate::check('show account dashboard') ||
                        Gate::check('show crm dashboard') ||
                        Gate::check('show pos dashboard'))
                    <li
                        class="dash-item dash-hasmenu
                                {{ Request::segment(1) == null ||
                                Request::segment(1) == 'account-dashboard' ||
                                Request::segment(1) == 'income report' ||
                                Request::segment(1) == 'report' ||
                                Request::segment(1) == 'reports-monthly-cashflow' ||
                                Request::segment(1) == 'reports-quarterly-cashflow' ||
                                Request::segment(1) == 'reports-payroll' ||
                                Request::segment(1) == 'reports-leave' ||
                                Request::segment(1) == 'reports-monthly-attendance' ||
                                Request::segment(1) == 'reports-lead' ||
                                Request::segment(1) == 'reports-deal' ||
                                Request::segment(1) == 'pos-dashboard' ||
                                Request::segment(1) == 'reports-warehouse' ||
                                Request::segment(1) == 'reports-daily-purchase' ||
                                Request::segment(1) == 'reports-monthly-purchase' ||
                                Request::segment(1) == 'reports-daily-pos' ||
                                Request::segment(1) == 'reports-monthly-pos' ||
                                Request::segment(1) == 'reports-pos-vs-purchase'
                                    ? 'active dash-trigger'
                                    : '' }}">
                        <a href="#!" class="dash-link ">
                            <span class="dash-micon">
                                <i class="ti ti-home"></i>
                            </span>
                            <span class="dash-mtext">{{ __('Dashboard') }}</span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                            @if ($userPlan->account == 1 && Gate::check('show account dashboard'))
                         
                                        @can('show account dashboard')
                                            <li
                                                class="dash-item {{ Request::segment(1) == null || Request::segment(1) == 'account-dashboard' ? ' active' : '' }}">
                                                <a class="dash-link"
                                                    href="{{ route('dashboard') }}">{{ __(' Overview') }}</a>
                                            </li>
                                        @endcan
                            
                                @endif  
                    </li>
                @endif

                <!--------------------- Start HRM ----------------------------------->
</ul>
              
            @endif

            <!--------------------- End HRM ----------------------------------->

            <!--------------------- Start Account ----------------------------------->

            @if (!empty($userPlan) &&  $userPlan->account == 1)
                @if (Gate::check('manage customer') ||
                        Gate::check('manage vender') ||
                        Gate::check('manage customer') ||
                        Gate::check('manage vender') ||
                        Gate::check('manage proposal') ||
                        Gate::check('manage bank account') ||
                        Gate::check('manage bank transfer') ||
                        Gate::check('manage invoice') ||
                        Gate::check('manage revenue') ||
                        Gate::check('manage credit note') ||
                        Gate::check('manage bill') ||
                        Gate::check('manage payment') ||
                        Gate::check('manage debit note') ||
                        Gate::check('manage chart of account') ||
                        Gate::check('manage journal entry') ||
                        Gate::check('balance sheet report') ||
                        Gate::check('ledger report') ||
                        Gate::check('trial balance report'))
                    <li
                        class="dash-item dash-hasmenu
                                     {{ Request::route()->getName() == 'print-setting' ||
                                     Request::segment(1) == 'customer' ||
                                     Request::segment(1) == 'vender' ||
                                     Request::segment(1) == 'proposal' ||
                                     Request::segment(1) == 'bank-account' ||
                                     Request::segment(1) == 'bank-transfer' ||
                                     Request::segment(1) == 'invoice' ||
                                     Request::segment(1) == 'revenue' ||
                                     Request::segment(1) == 'credit-note' ||
                               
                                     Request::segment(1) == 'payment-method' ||
                                     Request::segment(1) == 'custom-field' ||
                                   
                                     (Request::segment(1) == 'transaction' &&
                                         Request::segment(2) != 'ledger' &&
                                         Request::segment(2) != 'balance-sheet' &&
                                         Request::segment(2) != 'trial-balance') ||
                                     Request::segment(1) == 'goal' ||
                                     Request::segment(1) == 'budget' ||
                                
                                     Request::segment(1) == 'journal-entry' ||
                                     Request::segment(2) == 'ledger' ||
                                     Request::segment(2) == 'balance-sheet' ||
                                     Request::segment(2) == 'trial-balance' ||
                                     Request::segment(2) == 'profit-loss' ||
                                     Request::segment(1) == 'bill' ||
                                     Request::segment(1) == 'expense' ||
                                     Request::segment(1) == 'payment' ||
                                     Request::segment(1) == 'debit-note'
                                         ? ' active dash-trigger'
                                         : '' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-box"></i></span><span
                                class="dash-mtext">{{ __('Sales') }}
                            </span><span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="dash-submenu">

                      
                        @if (Gate::check('manage vender'))
                                            <li
                                                class="dash-item {{ Request::segment(1) == 'vender' ? 'active' : '' }}">
                                                <a class="dash-link"
                                                    href="{{ route('vender.index') }}">{{ __('Suppiler') }}</a>
                                            </li>
                                        @endif
                             
                          
                                        @if (Gate::check('manage customer'))
                                            <li
                                                class="dash-item {{ Request::segment(1) == 'customer' ? 'active' : '' }}">
                                                <a class="dash-link"
                                                    href="{{ route('customer.index') }}">{{ __('Customer') }}</a>
                                            </li>
                                        @endif
                                        @if ( Gate::check('manage invoice'))
                                        <li
                                            class="dash-item {{ Request::route()->getName() == 'invoice.index' || Request::route()->getName() == 'invoice.create' || Request::route()->getName() == 'invoice.edit' || Request::route()->getName() == 'invoice.show' ? ' active' : '' }}">
                                            <a class="dash-link"
                                                href="{{ route('invoice.index') }}">{{ __('Invoice') }}</a>
                                        </li>
                                       @endif
                                       @if (Gate::check('manage credit note'))
                                        <li
                                            class="dash-item {{ Request::route()->getName() == 'credit.note' ? ' active' : '' }}">
                                            <a class="dash-link"
                                                href="{{ route('credit.note') }}">{{ __('Credit Note') }}</a>
                                        </li>
                                        @endif
                        </ul>

                @endif
            @endif

            <!--------------------- End Account ----------------------------------->

            <!--------------------- Start CRM ----------------------------------->
  <!--------------------- Start POs System ----------------------------------->
  @if (!empty($userPlan) &&  $userPlan->pos == 1)
            @if (Gate::check('manage warehouse') ||
                    Gate::check('manage purchase') ||
                    Gate::check('manage pos') ||
                    Gate::check('manage print settings'))
                <li
                    class="dash-item dash-hasmenu {{ Request::segment(1) == 'warehouse' || Request::segment(1) == 'purchase'|| Request::segment(1) == 'quotation' || Request::route()->getName() == 'pos.barcode' || Request::route()->getName() == 'pos.print' || Request::route()->getName() == 'pos.show' ? ' active dash-trigger' : '' }}">
                    <a href="#!" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-layers-difference"></i></span><span
                            class="dash-mtext">{{ __('POS System') }}</span><span class="dash-arrow"><i
                                data-feather="chevron-right"></i></span></a>
                    <ul
                        class="dash-submenu {{ Request::segment(1) == 'warehouse' ||
                        Request::segment(1) == 'purchase' ||
                        Request::route()->getName() == 'pos.barcode' ||
                        Request::route()->getName() == 'pos.print' ||
                        Request::route()->getName() == 'pos.show'
                            ? 'show'
                            : '' }}">
                        @can('manage warehouse')
                            <li
                                class="dash-item {{ Request::route()->getName() == 'warehouse.index' || Request::route()->getName() == 'warehouse.show' ? ' active' : '' }}">
                                <a class="dash-link" href="{{ route('warehouse.index') }}">{{ __('Warehouse') }}</a>
                            </li>
                        @endcan
                     
                    @can('manage purchase')
                            <li
                                class="dash-item {{ Request::route()->getName() == 'purchase.index' || Request::route()->getName() == 'purchase.create' || Request::route()->getName() == 'purchase.edit' || Request::route()->getName() == 'purchase.show' ? ' active' : '' }}">
                                <a class="dash-link" href="{{ route('purchase.index') }}">{{ __('Purchase') }}</a>
                            </li>
                        @endcan
                        @can('manage pos')
                            <li class="dash-item {{ Request::route()->getName() == 'pos.index' ? ' active' : '' }}">
                                <a class="dash-link" href="{{ route('pos.index') }}">{{ __(' Add POS') }}</a>
                            </li>
                            <li
                                class="dash-item {{ Request::route()->getName() == 'pos.report' || Request::route()->getName() == 'pos.show' ? ' active' : '' }}">
                                <a class="dash-link" href="{{ route('pos.report') }}">{{ __('POS') }}</a>
                            </li>
                        @endcan
                   
                     

                    </ul>
                </li>
            @endif
        @endif
     

        <!--------------------- End CRM ----------------------------------->

        <!--------------------- Start Project ----------------------------------->

    
        <!--------------------- End Project ----------------------------------->



        <!--------------------- Start User Managaement System ----------------------------------->

        @if (
            \Auth::user()->type != 'super admin' &&
                (Gate::check('manage user') || Gate::check('manage role') || Gate::check('manage client')))
            <li
                class="dash-item dash-hasmenu {{ Request::segment(1) == 'users' ||
                Request::segment(1) == 'roles' ||
                Request::segment(1) == 'clients' ||
                Request::segment(1) == 'userlogs'
                    ? ' active dash-trigger'
                    : '' }}">

                <a href="#!" class="dash-link "><span class="dash-micon"><i
                            class="ti ti-users"></i></span><span
                        class="dash-mtext">{{ __('User Management') }}</span><span class="dash-arrow"><i
                            data-feather="chevron-right"></i></span></a>
                <ul class="dash-submenu">
                    @can('manage user')
                        <li
                            class="dash-item {{ Request::route()->getName() == 'users.index' || Request::route()->getName() == 'users.create' || Request::route()->getName() == 'users.edit' || Request::route()->getName() == 'user.userlog' ? ' active' : '' }}">
                            <a class="dash-link" href="{{ route('users.index') }}">{{ __('User') }}</a>
                        </li>
                    @endcan
                    @can('manage role')

                        <li
                            class="dash-item {{ Request::route()->getName() == 'roles.index' || Request::route()->getName() == 'roles.create' || Request::route()->getName() == 'roles.edit' ? ' active' : '' }} ">
                            <a class="dash-link" href="{{ route('roles.index') }}">{{ __('Role') }}</a>
                        </li>
                    @endcan
              
                </ul>
            </li>
        @endif

        <!--------------------- End User Managaement System----------------------------------->


        <!--------------------- Start Products System ----------------------------------->

        @if (Gate::check('manage product & service') || Gate::check('manage product & service'))
            <li class="dash-item dash-hasmenu {{ Request::segment(1) == 'productservice' || Request::segment(1) == 'taxes' || Request::segment(1) == 'product-category' || Request::segment(1) == 'product-unit' || Request::segment(1) == 'chart-of-account-type' ? 'active dash-trigger' : '' }}">
                <a href="#!" class="dash-link ">
                    <span class="dash-micon"><i class="ti ti-shopping-cart"></i></span><span
                        class="dash-mtext">{{ __('Setup') }}</span><span class="dash-arrow">
                        <i data-feather="chevron-right"></i></span>
                </a>
                <ul class="dash-submenu">
                    @if (Gate::check('manage product & service'))
                        <li class="dash-item {{ Request::segment(1) == 'productservice' ? 'active' : '' }}">
                            <a href="{{ route('productservice.index') }}"
                                class="dash-link">{{ __('Product & Services Setup') }}
                            </a>
                        </li>
                    @endif
                    @if (Gate::check('manage product & service'))
                        <li class="dash-item {{ Request::segment(1) == 'productstock' ? 'active' : '' }}">
                            <a href="{{ route('productstock.index') }}"
                                class="dash-link">{{ __('Product Stock') }}
                            </a>
                        </li>
                    @endif
                    @if (Gate::check('manage constant tax') ||
                                    Gate::check('manage constant category') ||
                                    Gate::check('manage constant unit'))
                                <li
                                    class="dash-item {{ Request::segment(1) == 'taxes' || Request::segment(1) == 'product-category' || Request::segment(1) == 'product-unit' ? 'active dash-trigger' : ''}}">
                                    <a class="dash-link"
                                        href="{{ route('taxes.index') }}">{{ __('Accounting Setup') }}</a>
                                </li>
                            @endif
                            @if (Gate::check('manage chart of account'))
                                <li
                                    class="dash-item dash-hasmenu {{ Request::segment(1) == 'chart-of-account' ||
                                    Request::segment(1) == 'journal-entry' ||
                                    Request::segment(2) == 'profit-loss' ||
                                    Request::segment(2) == 'ledger' ||
                                    Request::segment(2) == 'balance-sheet' ||
                                    Request::segment(2) == 'trial-balance'
                                        ? 'active dash-trigger'
                                        : '' }}">
                                        <a class="dash-link"
                                                href="{{ route('chart-of-account.index') }}">{{ __('Chart of Accounts') }}</a>
                                        </li>
                         @endif
                         @if (Gate::check('manage chart of account'))
                                <li
                                    class="dash-item dash-hasmenu {{ Request::segment(1) == 'chart-of-account-type' ||
                                    Request::segment(1) == 'journal-entry' ||
                                    Request::segment(2) == 'profit-loss' ||
                                    Request::segment(2) == 'ledger' ||
                                    Request::segment(2) == 'balance-sheet' ||
                                    Request::segment(2) == 'trial-balance'
                                        ? 'active dash-trigger'
                                        : '' }}">
                                        <a class="dash-link"
                                                href="{{ route('chart-of-account-type.index') }}">{{ __('Chart of Accounts Type') }}</a>
                                        </li>
                         @endif

                </ul>
            </li>
        @endif

        <!--------------------- End Products System ----------------------------------->


        <!--------------------- Start POs System ----------------------------------->
   
    


        <!--------------------- Start System Setup ----------------------------------->




        <!--------------------- End System Setup ----------------------------------->
     

        @if (\Auth::user()->type == 'super admin')
            <ul class="dash-navbar">
                @if (Gate::check('manage super admin dashboard'))
                    <li class="dash-item dash-hasmenu {{ Request::segment(1) == 'dashboard' ? ' active' : '' }}">
                        <a href="{{ route('client.dashboard.view') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-home"></i></span><span
                                class="dash-mtext">{{ __('Dashboard') }}</span>
                        </a>
                    </li>
                @endif


                @can('manage user')
                    <li
                        class="dash-item dash-hasmenu {{ Request::route()->getName() == 'users.index' || Request::route()->getName() == 'users.create' || Request::route()->getName() == 'users.edit' ? ' active' : '' }}">
                        <a href="{{ route('users.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-users"></i></span><span
                                class="dash-mtext">{{ __('Companies') }}</span>
                        </a>
                    </li>
                @endcan

                @if (Gate::check('manage plan'))
                    <li class="dash-item dash-hasmenu  {{ Request::segment(1) == 'plans' ? 'active' : '' }}">
                        <a href="{{ route('plans.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-trophy"></i></span><span
                                class="dash-mtext">{{ __('Plan') }}</span>
                        </a>
                    </li>
                @endif
          
             
    

             

            
             
                <li
                    class="dash-item dash-hasmenu {{ Request::segment(1) == 'email_template' || Request::route()->getName() == 'manage.email.language' ? ' active dash-trigger' : 'collapsed' }}">
                    <a href="{{ route('manage.email.language', [$emailTemplate->id, \Auth::user()->lang]) }}"
                        class="dash-link">
                        <span class="dash-micon"><i class="ti ti-template"></i></span>
                        <span class="dash-mtext">{{ __('Email Template') }}</span>
                    </a>
                </li>

          


            </ul>
        @endif
   
        @if (\Auth::user()->type != 'super admin')
            @if (Gate::check('manage company plan') || Gate::check('manage order') || Gate::check('manage company settings'))
                <li
                    class="dash-item dash-hasmenu {{ Request::segment(1) == 'settings' ||
                    Request::segment(1) == 'plans' ||
                    Request::segment(1) == 'stripe' ||
                    Request::segment(1) == 'order'
                        ? ' active dash-trigger'
                        : '' }}">
                    <a href="#!" class="dash-link">
                        <span class="dash-micon"><i class="ti ti-settings"></i></span><span
                            class="dash-mtext">{{ __('Settings') }}</span>
                        <span class="dash-arrow">
                            <i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="dash-submenu">
                        @if (Gate::check('manage company settings'))
                            <li
                                class="dash-item dash-hasmenu {{ Request::segment(1) == 'settings' ? ' active' : '' }}">
                                <a href="{{ route('settings') }}"
                                    class="dash-link">{{ __('System Settings') }}</a>
                            </li>
                        @endif
                        @if (Gate::check('manage company plan'))
                            <li
                                class="dash-item{{ Request::route()->getName() == 'plans.index' || Request::route()->getName() == 'stripe' ? ' active' : '' }}">
                                <a href="{{ route('plans.index') }}"
                                    class="dash-link">{{ __('Setup Subscription Plan') }}</a>
                            </li>
                        @endif
                        <li
                        class="dash-item{{ Request::route()->getName() == 'referral-program.company' ? ' active' : '' }}">
                        <a href="{{ route('referral-program.company') }}"
                            class="dash-link">{{ __('Referral Program') }}</a>
                        </li>

                        @if (Gate::check('manage order') && Auth::user()->type == 'company')
                            <li class="dash-item {{ Request::segment(1) == 'order' ? 'active' : '' }}">
                                <a href="{{ route('order.index') }}" class="dash-link">{{ __('Order') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endif

     
    </div>
</div>
</nav>
