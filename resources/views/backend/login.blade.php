<!DOCTYPE html>
<html lang="en">

<head>
    @include('backend.layouts.partials.header')
    <title>Admin Login</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f8f9fa;
            font-family: 'Poppins', serif;
            background-color: rgb(174, 174, 174)
        }

        .form-control {
            font-size: 14px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>

<body>
    <div class="login-card">
        @if (session()->has('status'))
            <div class="alert alert-success">
                {{ session()->get('status') }}
            </div>
        @endif
        <h3 class="text-center mb-4">Admin Login</h3>
        <form action="{{ route('admin.login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email"
                    required>
            </div>

            <div class="mb-3 position-relative">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Enter your password" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="fa fa-eye" id="passwordIcon"></i>
                    </button>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#togglePassword').on('click', function() {
                const passwordInput = $('#password');
                const passwordIcon = $('#passwordIcon');

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    passwordIcon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    passwordIcon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
</body>

</html>
