<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layouts.partials.header')
    <title>Customer Login / Register</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f4f4;
        }

        .auth-card {
            perspective: 1000px;
        }

        .auth-inner {
            position: relative;
            width: 100%;
            transform-style: preserve-3d;
            transition: transform 0.8s ease-in-out;
        }

        .auth-card.flipped .auth-inner {
            transform: rotateY(180deg);
        }

        .auth-front,
        .auth-back {
            position: absolute;
            width: 100%;
            backface-visibility: hidden;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .auth-back {
            transform: rotateY(180deg);
        }

        h3 {
            font-family: 'Cinzel', serif;
            color: #333;
        }

        .toggle-btn {
            background: none;
            border: none;
            color: #007bff;
            cursor: pointer;
            text-decoration: underline;
            margin-top: 15px;
            font-size: 0.9rem;
        }

        .btn-primary {
            background-color: #333;
            border-color: #333;
        }

        .btn-primary:hover {
            background-color: #555;
            border-color: #555;
        }

        .toggle-password {
            position: absolute;
            top: 43px;
            right: 15px;
            cursor: pointer;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 auth-card">
                <div class="auth-inner">
                    <!-- Login Form -->
                    <div class="auth-front">
                        <h3 class="text-center mb-4"><i class="fas fa-sign-in-alt me-2"></i>Customer Login</h3>
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        <form action="{{ route('customer.login.submit') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="login_password" class="form-control"
                                    required>
                                <i class="fas fa-eye toggle-password" toggle="#login_password"></i>
                                <a href="{{ route('customer.password.request') }}">Forgot Password</a>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        <div class="text-center">
                            <button class="toggle-btn" id="show-register">Don't have an account? Register</button>
                        </div>
                    </div>

                    <!-- Register Form -->
                    <div class="auth-back">
                        <h3 class="text-center mb-4"><i class="fas fa-user-plus me-2"></i>Create Account</h3>
                        @if (session('register_error'))
                            <div class="alert alert-danger">{{ session('register_error') }}</div>
                        @endif
                        <form action="{{ route('customer.register.submit') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">First Name</label>
                                <input type="text" name="fname" id="fname" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Last Name (optional)</label>
                                <input type="text" name="lname" id="lname" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="register_email" class="form-label">Email</label>
                                <input type="email" name="email" id="register_email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="register_email" class="form-label">Phone Number</label>
                                <input type="number" name="pnum" id="register_pnum" class="form-control" required>
                            </div>
                            <div class="mb-3 position-relative">
                                <label for="register_password" class="form-label">Password</label>
                                <input type="password" name="password" id="register_password" class="form-control"
                                    required>
                                <i class="fas fa-eye toggle-password" toggle="#register_password"></i>
                            </div>
                            <div class="mb-3 position-relative">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="confirm_password"
                                    class="form-control" required>
                                <i class="fas fa-eye toggle-password" toggle="#confirm_password"></i>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                        <div class="text-center">
                            <button class="toggle-btn" id="show-login">Already have an account? Login</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#show-register').on('click', function() {
                $('.auth-card').addClass('flipped');
            });
            $('#show-login').on('click', function() {
                $('.auth-card').removeClass('flipped');
            });

            $('.toggle-password').on('click', function() {
                const input = $($(this).attr('toggle'));
                const type = input.attr('type') === 'password' ? 'text' : 'password';
                input.attr('type', type);
                $(this).toggleClass('fa-eye fa-eye-slash');
            });
        });
    </script>

</body>

</html>
