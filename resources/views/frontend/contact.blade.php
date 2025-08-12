<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layouts.partials.header')
    <link rel="stylesheet" href="{{ asset('css/frontend/navbar-black.css') }}">
    <link rel="stylesheet" href="{{ asset('css/frontend/contact.css') }}">
    <title>Contact - Rumi's Collections</title>
</head>

<body>

    {{-- Navbar --}}
    @include('frontend.layouts.partials.navbar-black')

    {{-- Contact Section --}}
    <section class="py-5">
        <div class="container">

            <div class="text-center mb-5">
                <h1 class="fw-bold fade-in">Contact Us</h1>
                <p class="text-muted fade-in fade-in-delay">We’re here to help you every step of the way</p>
            </div>

            {{-- Contact Information --}}
            <div class="row mb-5">
                <div class="col-md-6 fade-in">
                    <div class="card shadow-sm border-0 rounded p-4 h-100">
                        <h4 class="fw-semibold mb-3">Email Us</h4>
                        <p class="mb-2"><strong>For feedback/help:</strong><br>
                            <a href="mailto:support@rumiscollections.com">support@rumiscollections.com</a>
                        </p>
                        <p class="mb-0"><strong>For complaints regarding an account or a product:</strong><br>
                            <a href="mailto:complaint@rumiscollections.com">complaint@rumiscollections.com</a><br>
                            <small class="text-muted">
                                Please mention your Order ID if you are making a complaint.<br>
                                At the moment, we are in the process of setting up our support team, so we are accepting
                                complaints via email itself.<br>
                                Be rest assured, your complaint shall be heard.
                            </small>
                        </p>
                    </div>
                </div>

                {{-- Contact Form --}}
                <div class="col-md-6 fade-in fade-in-delay">
                    <div class="card shadow-sm border-0 rounded p-4 h-100">
                        <h4 class="fw-semibold mb-3">Send Us a Message</h4>
                        <form>
                            <div class="mb-3">
                                <label for="name" class="form-label">Your Name *</label>
                                <input type="text" class="form-control" id="name" placeholder="Enter your name"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="customer_id" class="form-label">Customer ID (Optional)</label>
                                <input type="text" class="form-control" id="customer_id"
                                    placeholder="Enter your customer ID">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Your Email *</label>
                                <input type="email" class="form-control" id="email" placeholder="Enter your email"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Message *</label>
                                <textarea class="form-control" id="content" rows="5" placeholder="Type your message here..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-dark px-4">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- Footer --}}
    @include('frontend.layouts.partials.footer')

    {{-- Fade-in on Scroll Script --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const elements = document.querySelectorAll(".fade-in, .fade-in-delay");

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationPlayState = 'running';
                    }
                });
            }, {
                threshold: 0.1
            });

            elements.forEach(el => {
                el.style.animationPlayState = 'paused';
                observer.observe(el);
            });
        });
    </script>

</body>

</html>
