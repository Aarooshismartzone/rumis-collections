<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layouts.partials.header')
    <link rel="stylesheet" href="{{ asset('css/frontend/navbar-black.css') }}">
    <link rel="stylesheet" href="{{ asset('css/frontend/about.css') }}">
    <title>About Rumi's Collections</title>
</head>

<body>

    {{-- Navbar --}}
    @include('frontend.layouts.partials.navbar-black')

    {{-- About Section --}}
    <section>
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="fw-bold fade-in">About Rumi's Collections</h1>
                <p class="text-muted fade-in fade-in-delay">Where life and style come together</p>
            </div>

            <div class="row align-items-center mb-5">
                <div class="col-md-6 fade-in">
                    <img src="{{ asset('images/about-main.jpg') }}" alt="Rumi's Collections Overview"
                        class="img-fluid rounded shadow-sm" style="max-height: 400px; object-fit: cover;">
                </div>
                <div class="col-md-6 fade-in fade-in-delay">
                    <h3 class="fw-semibold">Our Story</h3>
                    <p class="text-muted">
                        Rumi's Collections is an Indian startup built on the belief that fashion is not just
                        about clothing - it's about expressing who we are. We specialize in <strong>printed
                            garments</strong> and a variety of unique materials that bring together the richness
                        of Indian creativity with a global sense of style.
                    </p>
                    <p class="text-muted">
                        Our philosophy is simple: <em>Style with elegance</em>. Whether you are at home,
                        in the workplace, or strolling down the street, we believe elegance should follow you
                        everywhere. Our products are designed for those who wish to carry their personality with
                        confidence and grace.
                    </p>
                </div>
            </div>

            <div class="row align-items-center flex-md-row-reverse mb-5">
                <div class="col-md-6 fade-in">
                    <img src="{{ asset('images/about-design.jpg') }}" alt="Design and Craftsmanship"
                        class="img-fluid rounded shadow-sm" style="max-height: 400px; object-fit: cover;">
                </div>
                <div class="col-md-6 fade-in fade-in-delay">
                    <h3 class="fw-semibold">Our Craft</h3>
                    <p class="text-muted">
                        Every piece at Rumi's Collections is thoughtfully created, from selecting premium fabrics
                        to designing timeless prints. Our team ensures that every stitch reflects our dedication
                        to quality, comfort, and style.
                    </p>
                    <p class="text-muted">
                        We work with skilled artisans and designers who understand that style is personal
                        and elegance is universal. This is why our collections resonate with people from
                        all walks of life.
                    </p>
                </div>
            </div>

            <div class="text-center fade-in">
                <h4 class="fw-semibold">Our Promise</h4>
                <p class="text-muted mx-auto" style="max-width: 700px;">
                    To bring you fashion that speaks to your individuality while ensuring comfort and
                    durability. Rumi's Collections is more than a brand—it's a reflection of who you are,
                    and a reminder that <strong>life and style truly come together</strong>.
                </p>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    @include('frontend.layouts.partials.footer')

    {{-- Simple Fade-In on Scroll Script --}}
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
