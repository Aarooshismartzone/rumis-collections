<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.customer.layouts.partials.customer-header')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/backend/add-forms.css') }}">
    <title>@yield('title')</title>
</head>

<body style="background-color: rgb(237, 237, 237); font-family: poppins, sans-serif;">
    <div class="d-flex">
        @include('frontend.customer.layouts.partials.sidebar')

        <div class="content-area" style="margin-left: 60px; width: 100%;">
            <!-- Topbar -->
            <nav class="topbar">
                <h1>@yield('title')</h1>
                <div class="user-info">
                    <div>{{ session('customer_name') }}</div>
                    <a href="{{ route('customer.logout') }}" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </nav>

            <div class="tab-content" id="v-pills-tabContent">
                @yield('content')
            </div>
        </div>
    </div>

</body>

</html>
