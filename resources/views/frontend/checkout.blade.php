<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layouts.partials.header')
    <link rel="stylesheet" href="{{ asset('css/frontend/navbar-black.css') }}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .form-label {
            font-weight: 500;
        }

        .readonly {
            background-color: #f8f9fa;
        }
    </style>
    <title>Checkout</title>
</head>

<body>
    @include('frontend.layouts.partials.navbar-black')

    <div class="container my-5">
        <h3 class="mb-4">Checkout</h3>
        <p>Total Price: ₹{{ number_format($totalPrice, 2) }}</p>
        <p>Delivery Charge: ₹{{ number_format($deliveryCharge, 2) }}</p>
        @if ($generics['is_gst'])
            <p>GST: ₹{{ number_format($gstAmount, 2) }}</p>
        @endif
        <h4 class="mt-3" style="color: green">Grand Total: ₹{{ number_format($grandTotal, 2) }}</h4>

        <form action="{{ route('order.store') }}" method="POST" id="checkoutForm">
            @csrf

            @php
                $customer = session()->has('customer_id') ? \App\Models\Customer::find(session('customer_id')) : null;
            @endphp

            <div class="row mt-5">
                <div class="col-md-6">
                    <h5>Delivery Address</h5>
                    <div class="mb-2">
                        <label class="form-label">First Name</label>
                        <input name="delivery[fname]" type="text" class="form-control"
                            value="{{ $customer->fname ?? '' }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Last Name</label>
                        <input name="delivery[lname]" type="text" class="form-control"
                            value="{{ $customer->lname ?? '' }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Company Name</label>
                        <input name="delivery[company_name]" type="text" class="form-control"
                            value="{{ $customer->company_name ?? '' }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Address Line 1</label>
                        <input name="delivery[address_line_1]" type="text" class="form-control"
                            value="{{ $customer->address_line_1 ?? '' }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Address Line 2</label>
                        <input name="delivery[address_line_2]" type="text" class="form-control"
                            value="{{ $customer->address_line_2 ?? '' }}">
                    </div>
                    {{-- API INPUTS --}}
                    <div class="mb-2">
                        <label class="form-label">Pin Code</label>
                        <input id="delivery_pincode" name="delivery[pin_code]" type="text" class="form-control"
                            value="{{ $customer->pin_code ?? '' }}" required pattern="^[0-9]{6}$" maxlength="6"
                            title="Enter a valid 6-digit PIN code.">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">City</label>
                        <select id="delivery_city" name="delivery[city]" class="form-control" required>
                            <option value="">-- Select City --</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">State</label>
                        <input id="delivery_state" name="delivery[state]" type="text" class="form-control readonly"
                            value="{{ $customer->state ?? '' }}" readonly required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Country</label>
                        <input name="delivery[country]" type="text" class="form-control readonly" value="India"
                            readonly required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Contact Number</label>
                        <input name="delivery[pnum]" type="text" class="form-control"
                            value="{{ $customer->pnum ?? '' }}" required pattern="^\+?[0-9]{1,12}$" maxlength="13"
                            title="Only digits and optional '+' at the beginning. Max 13 characters.">
                    </div>
                </div>

                <div class="col-md-6">
                    <h5 class="d-flex justify-content-between align-items-center">
                        Billing Address
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sameAsDelivery"
                                name="billing_same_as_delivery">
                            <label class="form-check-label" for="sameAsDelivery">Same as delivery address</label>
                        </div>
                    </h5>
                    <div class="mb-2">
                        <label class="form-label">First Name</label>
                        <input name="billing[fname]" type="text" class="form-control billing" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Last Name</label>
                        <input name="billing[lname]" type="text" class="form-control billing">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Company Name</label>
                        <input name="billing[company_name]" type="text" class="form-control billing">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Address Line 1</label>
                        <input name="billing[address_line_1]" type="text" class="form-control billing" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Address Line 2</label>
                        <input name="billing[address_line_2]" type="text" class="form-control billing">
                    </div>
                    {{-- API INPUTS --}}
                    <div class="mb-2">
                        <label class="form-label">Pin Code</label>
                        <input id="billing_pincode" name="billing[pin_code]" type="text"
                            class="form-control billing" required pattern="^[0-9]{6}$" maxlength="6"
                            title="Enter a valid 6-digit PIN code.">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">City</label>
                        <select id="billing_city" name="billing[city]" class="form-control billing" required>
                            <option value="">-- Select City --</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">State</label>
                        <input id="billing_state" name="billing[state]" type="text"
                            class="form-control billing readonly" readonly required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Country</label>
                        <input name="billing[country]" type="text" class="form-control billing readonly"
                            value="India" readonly required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Contact Number</label>
                        <input name="billing[pnum]" type="text" class="form-control billing" required
                            pattern="^\+?[0-9]{1,12}$" maxlength="13"
                            title="Only digits and optional '+' at the beginning. Max 13 characters.">
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button class="btn btn-success px-5 py-2">Place Order</button>
            </div>
        </form>
    </div>
    @include('frontend.layouts.partials.footer')

    <script>
        function handlePincodeLookup(pincodeInputId, citySelectId, stateInputId) {
            const pincode = document.getElementById(pincodeInputId).value;

            if (pincode.length === 6 && /^[0-9]+$/.test(pincode)) {
                fetch(`https://api.postalpincode.in/pincode/${pincode}`)
                    .then(response => response.json())
                    .then(data => {
                        const result = data[0];
                        const citySelect = document.getElementById(citySelectId);
                        const stateInput = document.getElementById(stateInputId);

                        citySelect.innerHTML = `<option value="">-- Select City --</option>`; // reset

                        if (result.Status === "Success" && result.PostOffice.length > 0) {
                            const state = result.PostOffice[0].State;
                            const uniqueCities = new Set();

                            result.PostOffice.forEach(office => {
                                const city = office.Name + ', ' + office.District;
                                uniqueCities.add(city);
                            });

                            uniqueCities.forEach(city => {
                                const opt = document.createElement('option');
                                opt.value = city;
                                opt.text = city;
                                citySelect.appendChild(opt);
                            });

                            stateInput.value = state;
                        } else {
                            alert('Invalid PIN code or no data found.');
                            stateInput.value = '';
                        }
                    })
                    .catch(error => {
                        console.error('API Error:', error);
                        alert('Failed to fetch location details.');
                    });
            }
        }

        document.getElementById('delivery_pincode').addEventListener('blur', function() {
            handlePincodeLookup('delivery_pincode', 'delivery_city', 'delivery_state');
        });

        document.getElementById('billing_pincode').addEventListener('blur', function() {
            handlePincodeLookup('billing_pincode', 'billing_city', 'billing_state');
        });

        // When checkbox "same as delivery" is selected, copy delivery city/state
        $('#sameAsDelivery').on('change', function() {
            if ($(this).is(':checked')) {
                $('.billing').each(function() {
                    let name = $(this).attr('name');
                    let corresponding = name.replace('billing', 'delivery');
                    let value = $('[name="' + corresponding + '"]').val();
                    $(this).val(value).addClass('readonly').attr('readonly', true);
                });

                $('#billing_city').html($('#delivery_city').html());
                $('#billing_city').val($('#delivery_city').val());
            } else {
                $('.billing').val('').removeClass('readonly').attr('readonly', false);
                $('#billing_city').html('<option value="">-- Select City --</option>');
            }
        });
    </script>

</body>

</html>
