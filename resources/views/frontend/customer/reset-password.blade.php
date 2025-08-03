<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
</head>

<body>
    <h2>Reset Password</h2>

    <form method="POST" action="{{ route('customer.password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <label>New Password:</label><br>
        <input type="password" name="password" required><br>

        <label>Confirm Password:</label><br>
        <input type="password" name="password_confirmation" required><br><br>

        @error('password')
            <p style="color: red">{{ $message }}</p>
        @enderror

        <button type="submit">Reset Password</button>
    </form>
</body>

</html>
