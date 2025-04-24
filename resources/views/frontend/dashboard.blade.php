<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
</head>
<body>
    <div class="container mt-5">
        <h3>Welcome, {{ session('customer_name') }}</h3>
        <a href="{{ route('customer.logout') }}" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>
