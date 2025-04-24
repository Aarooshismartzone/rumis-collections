<!DOCTYPE html>
<html lang="en">

<head>
    @include('backend.layouts.partials.header')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/backend/add-forms.css') }}">
    <title>@yield('title')</title>
</head>

<body style="background-color: rgb(237, 237, 237)">
    <div class="d-flex align-items-start">
        @include('backend.layouts.partials.sidebar')

        <div class="content-area">
            <!-- Topbar -->
            <nav class="topbar">
                <h1>@yield('title')</h1>
                <div class="user-info">
                    <span>{{ Auth::user()->fname }}</span>
                    <a href="{{ route('admin.logout') }}" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
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
