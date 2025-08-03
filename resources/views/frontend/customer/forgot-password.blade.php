<!DOCTYPE html>
<html>

<head>
    <title>Customer Forgot Password</title>
</head>

<body>
    <h2>Forgot Password</h2>

    @if (session('status'))
        <p style="color: green">{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('customer.password.email') }}">
        @csrf
        <label>Email Address:</label><br>
        <input type="email" name="email" required><br><br>

        @error('email')
            <p style="color: red">{{ $message }}</p>
        @enderror

        <button type="submit">Send Password Reset Link</button>
    </form>
</body>

</html>
