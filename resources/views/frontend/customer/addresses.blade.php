@extends('frontend.customer.layouts.customer-master')

@section('title')
    Addresses
@endsection

@section('content')
    <h1>Addresses</h1>
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    <h4>Add an address</h4>
    <form action="{{ route('customer.addAddress') }}" method="POST" class="address-form">
        @csrf
        <input type="text" class="form-control mb-2" placeholder="Address Line 1" name="address_line_1" required>
        <input type="text" class="form-control mb-2" placeholder="Address Line 2" name="address_line_2">
        <input type="text" class="form-control mb-2" placeholder="Pin Code" name="pincode" id="pincode" maxlength="6" required>

        <select name="city" id="city" class="form-control form-select mb-2">
            <option value="">-- Select City --</option>
        </select>

        <input type="text" class="form-control mb-2" placeholder="State" name="state" id="state" readonly>
        <input type="text" class="form-control mb-2" placeholder="Country" name="country" value="India" readonly>

        <button type="submit" class="btn btn-primary">Add</button>
    </form>
    <div class="row">
        @foreach ($addresses as $address)
            <div class="col-lg-3 col-sm-6 col-12 p-2">
                <div class="card p-1 text-center">
                    <div class="card-title">
                        <h4>Address {{ $loop->iteration }}</h4>
                    </div>
                    <div class="card-body">
                        {{ $address->address_line_1 }}<br>
                        {{ $address->address_line_2 }}<br>
                        {{ $address->city }}, {{ $address->state }}<br>
                        {{ $address->country }}<br>
                        {{ $address->pincode }}
                        {{ $address->is_primary_address == 1 ? 'Primary' : '' }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <script>
        $(document).ready(function() {
            $("#pincode").on("keyup", function() {
                let pincode = $(this).val().trim();

                if (pincode.length === 6 && /^[0-9]+$/.test(pincode)) {
                    fetch(`https://api.postalpincode.in/pincode/${pincode}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data[0].Status === "Success" && data[0].PostOffice) {
                                let postOffices = data[0].PostOffice;

                                // Fill state (from first entry)
                                $("#state").val(postOffices[0].State);

                                // Populate city dropdown
                                let cityDropdown = $("#city");
                                cityDropdown.empty();
                                cityDropdown.append(`<option value="">-- Select City --</option>`);

                                postOffices.forEach(function(office) {
                                    let cityName =
                                        `${office.Name}, ${office.Block}, ${office.District}`;
                                    cityDropdown.append(
                                        `<option value="${cityName}">${cityName}</option>`);
                                });
                            } else {
                                $("#state").val("");
                                $("#city").html(`<option value="">-- Select City --</option>`);
                            }
                        })
                        .catch(error => {
                            console.error("Error fetching data:", error);
                        });
                }
            });
        });
    </script>
@endsection
