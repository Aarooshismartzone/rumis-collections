<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layouts.partials.header')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/frontend/navbar-white.css') }}">
    <title>{{ $generics['title'] ?? 'Site Title' }}</title>
</head>

<body style="font-family: 'Poppins', serif">
    <main>
        @include('frontend.layouts.partials.navbar')
        @include('frontend.layouts.partials.accordian')

        {{-- Hero section --}}
        <div style="position: relative">
            <div class="container">
                <div class="section-title pt-4">Featured Products</div>
                <div class="row mt-2">
                    @foreach ($products->where('is_featured', true)->sortByDesc('created_at')->take(4) as $product)
                        @include('frontend.layouts.partials.list')
                    @endforeach
                </div>
                @foreach ($categories as $category)
                    <div class="section-title pt-4">{{ $category->category_name }}</div>
                    <div class="row mt-2">
                        @foreach ($category->products->where('is_featured', false)->shuffle()->take(4) as $product)
                            @include('frontend.layouts.partials.list')
                        @endforeach
                        <div class="text-center">
                            <div style="position: relative" class="mt-3">
                                <a href="{{ route('frontend.shop', ['category_slug' => $category->category_slug]) }}"
                                    class="smr-btn">See More</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

    @if (!$customer)
        <!-- Login Modal -->
        <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-4 rounded-4 shadow-lg">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="loginModalLabel">
                            <i class="fas fa-sign-in-alt me-2"></i> Login / Register
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <!-- Info line -->
                        <p class="text-muted small mb-3">
                            Logging in helps you get additional benefits such as adding addresses,
                            checking your previous orders, and accessing your cart/wishlist from any device at any time.
                        </p>

                        <!-- Buttons -->
                        <div class="d-grid gap-2 mb-3">
                            <a href="{{ route('customer.login') }}" class="btn btn-dark">
                                <i class="fas fa-user me-2"></i> Login / Sign Up
                            </a>
                            <a href="{{ route('customer.google.login') }}" class="btn btn-danger">
                                <i class="fab fa-google me-2"></i> Continue with Google
                            </a>
                            <button type="button" class="btn btn-secondary" id="guestBtn" data-bs-dismiss="modal">
                                <i class="fas fa-user-secret me-2"></i> Continue as Guest
                            </button>
                        </div>

                        <small class="text-muted d-block text-center">
                            By continuing, you agree to our Terms & Privacy Policy.
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Function to set cookie
                function setCookie(name, value, days) {
                    var d = new Date();
                    d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
                    var expires = "expires=" + d.toUTCString();
                    document.cookie = name + "=" + value + ";" + expires + ";path=/";
                }

                // Function to get cookie
                function getCookie(name) {
                    var cname = name + "=";
                    var decodedCookie = decodeURIComponent(document.cookie);
                    var ca = decodedCookie.split(';');
                    for (var i = 0; i < ca.length; i++) {
                        var c = ca[i].trim();
                        if (c.indexOf(cname) == 0) {
                            return c.substring(cname.length, c.length);
                        }
                    }
                    return "";
                }

                // Show modal only if not shown before
                if (!getCookie("loginModalShown")) {
                    setTimeout(function() {
                        var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                        loginModal.show();

                        // Set cookie for 1 day
                        setCookie("loginModalShown", "true", 1);
                    }, 3000); // show after 3s
                }

                // If guest continues, set cookie immediately
                document.getElementById("guestBtn")?.addEventListener("click", function() {
                    setCookie("loginModalShown", "true", 1); // cookie valid 1 day
                });
            });
        </script>
    @endif

    @include('frontend.layouts.partials.footer')
</body>

</html>
<!-- DEVELOPED BY AAROOSHI.COM -->
