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

<body style="background-color: rgb(214, 214, 214)">
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
                {{-- Delivery Address --}}
                <div class="col-md-6">
                    <h5>Delivery Address</h5>
                    @if ($customer_id)
                        <button type="button" class="btn btn-link p-0 mb-2"
                            data-bs-toggle="modal"
                            data-bs-target="#savedAddressesModal"
                            data-target-form="delivery">
                            Choose from Saved Addresses
                        </button>
                    @endif

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

                {{-- Billing Address --}}
                <div class="col-md-6">
                    <h5 class="d-flex justify-content-between align-items-center">
                        Billing Address
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sameAsDelivery"
                                name="billing_same_as_delivery">
                            <label class="form-check-label" for="sameAsDelivery">Same as delivery address</label>
                        </div>
                    </h5>
                    @if ($customer_id)
                        <button type="button" class="btn btn-link p-0 mb-2"
                            data-bs-toggle="modal"
                            data-bs-target="#savedAddressesModal"
                            data-target-form="billing">
                            Choose from Saved Addresses
                        </button>
                    @endif

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

    {{-- Saved Addresses Modal --}}
    @if ($customer_id)
        <div class="modal fade" id="savedAddressesModal" tabindex="-1" aria-labelledby="savedAddressesModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="savedAddressesModalLabel">Saved Addresses</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($addresses->isEmpty())
                            <p>No saved addresses found.</p>
                        @else
                            <div class="row g-3">
                                @foreach ($addresses as $address)
                                    <div class="col-md-6 col-sm-12">
                                        <div class="border rounded p-3 h-100">
                                            <p class="mb-1"><strong>{{ $address->address_line_1 }}</strong></p>
                                            <p class="mb-1">{{ $address->address_line_2 }}</p>
                                            <p class="mb-1">{{ $address->city }}, {{ $address->state }}</p>
                                            <p class="mb-1">{{ $address->country }} - {{ $address->pin_code }}</p>
                                            @if ($address->is_primary_address)
                                                <span class="badge bg-primary">Primary</span>
                                            @endif
                                            <div class="mt-2">
                                                {{-- Use single quotes around JSON - @json safely encodes --}}
                                                <button type="button" class="btn btn-sm btn-success select-address"
                                                    data-address='@json($address)'>
                                                    Use This Address
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @include('frontend.layouts.partials.footer')

    <script>
        // ---------- helper: pincode lookup ----------
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
                                const city = office.Name + ', ' + office.Block + ', ' + office.District;
                                uniqueCities.add(city);
                            });

                            uniqueCities.forEach(city => {
                                const opt = document.createElement('option');
                                opt.value = city;
                                opt.textContent = city;
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

        // ---------- main DOM ready ----------
        document.addEventListener('DOMContentLoaded', function () {
            // set up pincode listeners
            const deliveryP = document.getElementById('delivery_pincode');
            const billingP = document.getElementById('billing_pincode');
            if (deliveryP) deliveryP.addEventListener('blur', function () {
                handlePincodeLookup('delivery_pincode', 'delivery_city', 'delivery_state');
            });
            if (billingP) billingP.addEventListener('blur', function () {
                handlePincodeLookup('billing_pincode', 'billing_city', 'billing_state');
            });

            // track which form (delivery / billing) opened the modal
            let currentTargetForm = 'delivery';
            document.querySelectorAll('[data-target-form]').forEach(btn => {
                btn.addEventListener('click', function () {
                    const t = this.getAttribute('data-target-form');
                    if (t === 'delivery' || t === 'billing') currentTargetForm = t;
                });
            });

            // helper to create/replace city option safely
            function setCitySelect(prefix, cityValue) {
                const sel = document.querySelector(`[name="${prefix}[city]"]`);
                if (!sel) return;
                sel.innerHTML = ''; // clear
                const opt = document.createElement('option');
                opt.value = cityValue ?? '';
                opt.textContent = cityValue ?? '';
                opt.selected = true;
                sel.appendChild(opt);
            }

            // fill address form safely
            function fillAddressForm(prefix, address) {
                if (!address || typeof address !== 'object') return;

                const setIf = (selectorName, val) => {
                    const el = document.querySelector(`[name="${prefix}[${selectorName}]"]`);
                    if (!el) return;
                    el.value = val ?? '';
                };

                setIf('address_line_1', address.address_line_1);
                setIf('address_line_2', address.address_line_2);
                setCitySelect(prefix, address.city);
                setIf('state', address.state);
                setIf('country', address.country);
                setIf('pin_code', address.pin_code);
                setIf('fname', address.fname ?? '');
                setIf('lname', address.lname ?? '');
                setIf('company_name', address.company_name ?? '');
                setIf('pnum', address.pnum ?? '');
            }

            // bind "Use This Address" buttons
            document.querySelectorAll('.select-address').forEach(button => {
                button.addEventListener('click', function () {
                    const raw = this.getAttribute('data-address');

                    let addressObj = null;
                    try {
                        addressObj = JSON.parse(raw);
                    } catch (err) {
                        console.error('Address JSON parse error:', err, 'raw:', raw);
                        alert('Failed to parse saved address. See console for details.');
                        return;
                    }

                    // fill the targeted form
                    fillAddressForm(currentTargetForm, addressObj);

                    // If they selected delivery and "same as delivery" is checked, copy to billing too
                    const sameCB = document.getElementById('sameAsDelivery');
                    if (currentTargetForm === 'delivery' && sameCB && sameCB.checked) {
                        fillAddressForm('billing', addressObj);
                        // Also copy city select options/selection safely
                        const dCity = document.querySelector('[name="delivery[city]"]');
                        const bCity = document.querySelector('[name="billing[city]"]');
                        if (dCity && bCity) {
                            bCity.innerHTML = dCity.innerHTML;
                            bCity.value = dCity.value;
                        }
                    }

                    // close modal (safely get instance or create)
                    const modalEl = document.getElementById('savedAddressesModal');
                    if (modalEl) {
                        let modalInstance = bootstrap.Modal.getInstance(modalEl);
                        if (!modalInstance) {
                            modalInstance = new bootstrap.Modal(modalEl);
                        }
                        modalInstance.hide();
                    }
                });
            });

            // same-as-delivery checkbox (vanilla JS)
            const sameCheckbox = document.getElementById('sameAsDelivery');
            if (sameCheckbox) {
                sameCheckbox.addEventListener('change', function () {
                    if (this.checked) {
                        // copy all delivery values into billing and lock billing inputs
                        const billingFields = document.querySelectorAll('.billing');
                        billingFields.forEach(function (el) {
                            const name = el.getAttribute('name'); // e.g. billing[address_line_1]
                            if (!name) return;
                            const correspondingName = name.replace('billing', 'delivery');
                            const deliveryEl = document.querySelector('[name="' + correspondingName + '"]');
                            if (deliveryEl) {
                                el.value = deliveryEl.value;
                                el.classList.add('readonly');
                                el.setAttribute('readonly', true);
                            }
                        });

                        // copy city options & value
                        const dCity = document.getElementById('delivery_city');
                        const bCity = document.getElementById('billing_city');
                        if (dCity && bCity) {
                            bCity.innerHTML = dCity.innerHTML;
                            bCity.value = dCity.value;
                        }
                    } else {
                        // unlock and clear billing fields
                        const billingFields = document.querySelectorAll('.billing');
                        billingFields.forEach(function (el) {
                            el.value = '';
                            el.classList.remove('readonly');
                            el.removeAttribute('readonly');
                        });

                        const bCity = document.getElementById('billing_city');
                        if (bCity) bCity.innerHTML = '<option value="">-- Select City --</option>';
                    }
                });
            }
        });
    </script>

</body>

</html>
