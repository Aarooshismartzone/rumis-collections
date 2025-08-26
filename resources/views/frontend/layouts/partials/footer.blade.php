<footer class="bg-black text-white pt-4 pb-3">
    <div class="container">
        <div class="row">

            <!-- Logo and Brand Info -->
            <div class="col-md-4 mb-3">
                <img src="{{ asset('images/logo/logo_white.png') }}" alt="Logo" style="height: 40px;">
                <p class="mt-2">Where Life and Style come together.</p>
            </div>

            <!-- Quick Navigation -->
            <div class="col-md-4 mb-3">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="/" class="text-white text-decoration-none">Home</a></li>
                    <li><a href="{{ route('frontend.shop') }}" class="text-white text-decoration-none">Shop</a></li>
                    <li><a href="{{ route('frontend.about') }}" class="text-white text-decoration-none">About</a></li>
                    <li><a href="{{ route('frontend.contact') }}" class="text-white text-decoration-none">Contact</a>
                    </li>
                </ul>
            </div>

            <!-- Important Policies -->
            <div class="col-md-4 mb-3">
                <h5>Important Links</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('privacy.policy') }}" class="text-white text-decoration-none">Privacy
                            Policy</a></li>
                    <li><a href="{{ route('cancellation.policy') }}"
                            class="text-white text-decoration-none">Cancellation Policy</a></li>
                    <li><a href="{{ route('refund.policy') }}" class="text-white text-decoration-none">Refund Policy</a>
                    </li>
                    <li><a href="{{ route('terms.conditions') }}" class="text-white text-decoration-none">Terms And
                            Conditions</a></li>
                    <li><a href="{{ route('shipping.rules') }}" class="text-white text-decoration-none">Shipping</a></li>

                </ul>
            </div>

        </div>
    </div>
    <div class="text-center mt-3 border-top pt-3" style="font-size: 14px;">
        &copy; {{ date('Y') }} Rumi's Collections. All rights reserved.
    </div>
</footer>
