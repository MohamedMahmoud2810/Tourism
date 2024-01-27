<!DOCTYPE html>
<html lang="ar">
{{-- {{ LaravelLocalization::getCurrentLocaleDirection() }}" lang="{{ app()->getLocale() }}" --}}
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Control Tour</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@500&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    {{--<!-- Bootstrap 3.3.7 -->--}}
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/skin-blue.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/styleTour.css') }}">

    @if (app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/font-awesome-rtl.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/AdminLTE-rtl.min.css') }}">
        <link href="https://fonts.googleapis.com/css?family=Cairo:400,700" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/bootstrap-rtl.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/rtl.css') }}">

        <style>
            body, h1, h2, h3, h4, h5, h6 {
                font-family: 'Cairo', sans-serif !important;
            }
        </style>
    @else
        <link rel="stylesheet"
              href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/AdminLTE.min.css') }}">
    @endif

    <style>
        .collapse.in{

            visibility: visible!important;
        }
        .mega ul>li>a{
            padding: 8px 30px 8px 5px;
            display: block!important;

        }
        body {
            font-family: 'Noto Kufi Arabic', sans-serif !important;
        }

        .bg-light {
            background-color: #f3f3f3 !important;
        }

        .dirtable {
            direction: rtl !important;
        }

        .mr-2 {
            margin-right: 5px;
        }

        .loader {
            border: 5px solid #f3f3f3;
            border-radius: 50%;
            border-top: 5px solid #367FA9;
            width: 60px;
            height: 60px;
            -webkit-animation: spin 1s linear infinite; /* Safari */
            animation: spin 1s linear infinite;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    {{--<!-- jQuery 3 -->--}}
    {{--    <script src="{{ asset('dashboard_files/js/jquery.min.js') }}"></script>--}}
    {{--    <script src="{{ asset('dashboard_files/js/select2.min.js') }}"></script>--}}

    {{--noty--}}
    <link rel="stylesheet" href="{{ asset('dashboard_files/plugins/noty/noty.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_files/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <script src="{{ asset('dashboard_files/plugins/noty/noty.min.js') }}"></script>

    {{--morris--}}

    {{--<!-- iCheck -->--}}
    {{--    <link rel="stylesheet" href="{{ asset('dashboard_files/plugins/icheck/all.css') }}">--}}

    {{--html in  ie--}}
    {{-- <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script> --}}
    @livewireStyles
    @powerGridStyles
    @yield('style')
</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper" style="overflow-y: hidden;">

    <header class="main-header">

        {{--<!-- Logo -->--}}
        <a href="{{ asset('/') }}" class="logo">
            {{--<!-- mini logo for sidebar mini 50x50 pixels -->--}}
            <span class="logo-mini"><b><img src="{{ asset('dashboard_files/img/logo.png') }}"></b></span>
            <span class="logo-lg"><b>معاهد أبو قير العليا </b></span>
        </a>

        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <!-- Messages: style can be found in dropdown.less-->


                    {{--<!-- Notifications: style can be found in dropdown.less -->--}}


                    <li class="dropdown user user-menu">

                        <a href="#" class="drodpdown-toggle" data-toggle="dropdown">
                            <img src="{{ asset('dashboard_files/img/user2-160x160.jpg') }}" class="user-image"
                                 alt="User Image">
                            <span class="hidden-xl" style="font-size: 12px;">{{Auth::user()->name ?? 'Admin'}} </span>
                        </a>
                        <ul class="dropdown-menu" >

                            {{--<!-- User image -->--}}
                            <li class="user-header" style=" height: 100px !important;">
                                <img src="{{ asset('dashboard_files/img/user2-160x160.jpg') }}" class="img-circle"
                                     alt="User Image">
{{--                                <p>--}}
{{--                                    <small>{{Auth::user()->name ?? 'Admin'}}</small>--}}
{{--                                </p>--}}
                            </li>

                            {{--<!-- Menu Footer-->--}}
                            <li class="user-footer">


                                <a href="{{ route('logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">تسجيل خروج</a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>

                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

    </header>

    @include('layouts.dashboard._aside')

    @yield('content')

    @include('partials._session')
{{--    @include('partials._errors')--}}

    <footer class="main-footer"
            style="left: 0;bottom: 0;width: 100%;text-align: center; margin-left:0px!important;">
        <div class="pull-right hidden-xs">
            <b style="color: rgb(209, 186, 55)">Dahab Informatics</b>
        </div>
        <strong style="color:#144935">معاهد ابو قير العليا &copy; 2022/2023</strong>
        <!-- reserved. -->
    </footer>

</div><!-- end of wrapper -->

{{--<!-- Bootstrap 3.3.7 -->--}}
<script src="{{ asset('dashboard_files/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('dashboard_files/js/select2.full.min.js') }}"></script>

{{--icheck--}}
{{--<script src="{{ asset('dashboard_files/plugins/icheck/icheck.min.js') }}"></script>--}}

{{--<!-- FastClick -->--}}
<script src="{{ asset('dashboard_files/js/fastclick.js') }}"></script>

{{--<!-- AdminLTE App -->--}}
<script src="{{ asset('dashboard_files/js/adminlte.min.js') }}"></script>

{{--ckeditor standard--}}
{{--<script src="{{ asset('dashboard_files/plugins/ckeditor/ckeditor.js') }}"></script>--}}

{{--jquery number--}}
{{--<script src="{{ asset('dashboard_files/js/jquery.number.min.js') }}"></script>--}}

{{--print this--}}
<script src="{{ asset('dashboard_files/js/printThis.js') }}"></script>


<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()
    })
    $(document).ready(function () {
        // $('.sidebar-menu').tree();
        // //icheck
        // $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        //     checkboxClass: 'icheckbox_minimal-blue',
        //     radioClass: 'iradio_minimal-blue'
        // });
        //delete
        $('.delete').click(function (e) {
            var that = $(this)
            e.preventDefault();
            var n = new Noty({
                text: "@lang('site.confirm_delete')",
                type: "warning",
                killer: true,
                buttons: [
                    Noty.button("@lang('site.yes')", 'btn btn-success mr-2', function () {
                        that.closest('form').submit();
                    }),
                    Noty.button("@lang('site.no')", 'btn btn-primary mr-2', function () {
                        n.close();
                    })
                ]
            });
            n.show();
        });//end of delete
        // CKEDITOR.config.language =  "{{ app()->getLocale() }}";
    });//end of ready
</script>
@stack('scripts')
@livewireScripts
@powerGridScripts
<script src="//unpkg.com/alpinejs" defer></script>
<script>
    window.addEventListener('showAlert', event => {
        alert(event.detail.message);
    })
</script>
</body>
</html>
