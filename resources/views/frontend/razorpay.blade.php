<!DOCTYPE html>
<html>

<head>
    <title>Processing Payment...</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body onload="initiatePayment()">
    <h3 style="text-align: center; margin-top: 50px;">Redirecting to Payment...</h3>

    <script>
        function initiatePayment() {
            var options = {
                "key": "{{ env('RAZORPAY_KEY') }}",
                "amount": "{{ $summary['grand_total'] * 100 }}", // in paise
                "currency": "INR",
                "name": "{{ env('APP_NAME') }}",
                "description": "Order Payment",
                "image": "{{ asset('images/logo/logo_white.png') }}", // Optional: add logo URL
                "handler": function(response) {
                    // Submit the payment ID to backend
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('payment.proceed') }}";

                    const _token = document.createElement('input');
                    _token.type = 'hidden';
                    _token.name = '_token';
                    _token.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.appendChild(_token);

                    const payment_id = document.createElement('input');
                    payment_id.type = 'hidden';
                    payment_id.name = 'razorpay_payment_id';
                    payment_id.value = response.razorpay_payment_id;
                    form.appendChild(payment_id);

                    document.body.appendChild(form);
                    form.submit();
                },
                "prefill": {
                    "name": "{{ $summary['billing']['fname'] }} {{ $summary['billing']['lname'] }}",
                    @if (!empty($customer?->email))
                        "email": "{{ $customer->email }}",
                    @endif
                    "contact": "{{ $summary['billing']['pnum'] }}"
                },
                "theme": {
                    "color": "#0d6efd"
                }
            };

            const rzp = new Razorpay(options);
            rzp.open();
        }
    </script>
</body>

</html>
