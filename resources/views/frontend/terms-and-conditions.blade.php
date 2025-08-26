<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layouts.partials.header')
    <title>Terms & Conditions | Rumi's Collections</title>
    <meta name="description" content="Terms & Conditions for Rumi's Collections (rumiscollections.com)">
    <!-- Bootstrap is assumed to be already connected on your site -->
    <style>
        html {
            scroll-behavior: smooth;
        }

        .toc a.active {
            font-weight: 600;
        }

        .section-divider {
            border-top: 1px solid rgba(0, 0, 0, .075);
        }
    </style>
</head>

<body>
    {{-- Navbar --}}
    @include('frontend.layouts.partials.navbar-black')
    <header class="py-4 border-bottom bg-white">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="/" class="text-decoration-none">
                <span class="fs-4 fw-bold text-dark">Rumi's Collections</span>
            </a>
            <span class="text-body-secondary">rumiscollections.com</span>
        </div>
    </header>

    <main class="container my-5">
        <div class="row g-4">
            <!-- Table of Contents -->
            <aside class="col-lg-4 col-xl-3">
                <div class="position-sticky" style="top: 1.5rem;">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h2 class="h5 mb-3">Contents</h2>
                            <nav class="nav nav-pills flex-column gap-2 toc" id="toc">
                                <a class="nav-link" href="#intro">Overview</a>
                                <a class="nav-link" href="#general">1. General Information</a>
                                <a class="nav-link" href="#use">2. Use of the Website</a>
                                <a class="nav-link" href="#products">3. Products & Services</a>
                                <a class="nav-link" href="#pricing">4. Pricing & Payments</a>
                                <a class="nav-link" href="#shipping">5. Shipping & Delivery</a>
                                <a class="nav-link" href="#returns">6. Returns & Refunds</a>
                                <a class="nav-link" href="#ip">7. Intellectual Property</a>
                                <a class="nav-link" href="#liability">8. Limitation of Liability</a>
                                <a class="nav-link" href="#links">9. Third-Party Links</a>
                                <a class="nav-link" href="#law">10. Governing Law</a>
                                <a class="nav-link" href="#contact">11. Contact Us</a>
                            </nav>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Content -->
            <section class="col-lg-8 col-xl-9">
                <div class="bg-white shadow-sm rounded-3 p-4 p-md-5">
                    <header class="mb-4">
                        <h1 class="display-6">Terms &amp; Conditions</h1>
                        <p class="mb-1">Effective Date: <strong><span id="effective-date">24-08-2025</span></strong></p>
                        <p class="text-body-secondary mb-0">Website: <a href="https://rumiscollections.com"
                                class="link-primary">rumiscollections.com</a> &middot; Business Name: <strong>Rumi's
                                Collections</strong></p>
                    </header>

                    <hr class="section-divider my-4" />

                    <article id="intro" class="mb-4">
                        <h2 class="h4">Overview</h2>
                        <p>Welcome to <strong>Rumi's Collections</strong> (the “Site”). By accessing or using this Site,
                            you agree to be bound by these Terms &amp; Conditions (the “Terms”). If you do not agree,
                            please do not use the Site.</p>
                    </article>

                    <article id="general" class="mb-4">
                        <h2 class="h4">1. General Information</h2>
                        <ul>
                            <li>This Site is operated by <strong>Rumi's Collections</strong> (“we,” “our,” “us”).</li>
                            <li>By using the Site, you represent that you are at least 18 years old or are using the
                                Site under the supervision of a parent or legal guardian.</li>
                            <li>We may update these Terms from time to time. Continued use of the Site following changes
                                constitutes your acceptance of the revised Terms.</li>
                        </ul>
                    </article>

                    <article id="use" class="mb-4">
                        <h2 class="h4">2. Use of the Website</h2>
                        <ul>
                            <li>You agree not to use our products or services for any unlawful or unauthorized purpose.
                            </li>
                            <li>You agree not to reproduce, duplicate, copy, sell, resell or exploit any portion of the
                                Site, products, or content without our express written permission.</li>
                            <li>You must not transmit any worms, viruses, or destructive code.</li>
                        </ul>
                    </article>

                    <article id="products" class="mb-4">
                        <h2 class="h4">3. Products &amp; Services</h2>
                        <ul>
                            <li>We strive to display product information (including colors, images, and descriptions) as
                                accurately as possible; however, actual colors may vary by device.</li>
                            <li>All products are subject to availability. We reserve the right to limit quantities or
                                discontinue any product at any time.</li>
                            <li>We may correct any errors, inaccuracies, or omissions and to change or update
                                information without prior notice.</li>
                        </ul>
                    </article>

                    <article id="pricing" class="mb-4">
                        <h2 class="h4">4. Pricing &amp; Payments</h2>
                        <ul>
                            <li>Prices are listed on the Site in Indian Rupees (INR) and are subject to change
                                without notice.</li>
                            <li>Taxes, duties, and shipping fees (if applicable) will be calculated at checkout.</li>
                            <li>Payment is due at the time of order via the payment methods available at checkout. By
                                submitting an order, you authorize us (or our payment processor) to charge your selected
                                payment method.</li>
                        </ul>
                    </article>

                    <article id="shipping" class="mb-4">
                        <h2 class="h4">5. Shipping &amp; Delivery</h2>
                        <ul>
                            <li>Estimated shipping times and costs are provided at checkout and may vary by destination
                                and courier.</li>
                            <li>Risk of loss passes to you upon delivery to the shipping address you provide.</li>
                            <li>We are not responsible for delays due to courier issues, customs processing, or events
                                beyond our control.</li>
                        </ul>
                    </article>

                    <article id="returns" class="mb-4">
                        <h2 class="h4">6. Returns &amp; Refunds</h2>
                        <p>Eligibility, timelines, and procedures are detailed in our separate policies:</p>
                        <ul>
                            <li><a href="{{ route('refund.policy') }}" class="link-primary">Refund Policy</a></li>
                            <li><a href="{{ route('cancellation.policy') }}" class="link-primary">Cancellation
                                    Policy</a></li>
                            <li><a href="{{ route('privacy.policy') }}" class="link-primary">Privacy Policy</a></li>
                        </ul>
                    </article>

                    <article id="ip" class="mb-4">
                        <h2 class="h4">7. Intellectual Property</h2>
                        <ul>
                            <li>All content on the Site—including text, graphics, logos, images, and software—is the
                                property of <strong>Rumi's Collections</strong> and is protected by applicable
                                intellectual property laws.</li>
                            <li>Any unauthorized use of our content is prohibited.</li>
                        </ul>
                    </article>

                    <article id="liability" class="mb-4">
                        <h2 class="h4">8. Limitation of Liability</h2>
                        <ul>
                            <li>We do not warrant that the Site will be uninterrupted, timely, secure, or error-free.
                            </li>
                            <li>To the fullest extent permitted by law, <strong>Rumi's Collections</strong> shall not be
                                liable for any indirect, incidental, punitive, or consequential damages arising from
                                your use of the Site or purchase/use of products.</li>
                        </ul>
                    </article>

                    <article id="links" class="mb-4">
                        <h2 class="h4">9. Third-Party Links</h2>
                        <p>The Site may include links to third-party websites. We do not endorse and are not responsible
                            for the content, accuracy, or policies of third-party sites.</p>
                    </article>

                    <article id="law" class="mb-4">
                        <h2 class="h4">10. Governing Law</h2>
                        <p>These Terms are governed by the laws of India, specifically the state of West Bengal. You
                            agree that any
                            dispute shall be subject to the exclusive jurisdiction of the courts located in Kolkata,
                            West Bengal.</p>
                    </article>

                    <article id="contact" class="mb-2">
                        <h2 class="h4">11. Contact Us</h2>
                        <ul class="list-unstyled mb-0">
                            <li>📧 Email: <a href="mailto:support@rumiscollections.com"
                                    class="link-primary">support@rumiscollections.com</a></li>
                            <li>🌐 Website: <a href="https://rumiscollections.com"
                                    class="link-primary">https://rumiscollections.com</a></li>
                        </ul>
                    </article>

                    <hr class="section-divider my-4" />

                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <small class="text-body-secondary">Last updated: <span id="last-updated">[Insert
                                Date]</span></small>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-outline-secondary btn-sm"
                                onclick="window.print();return false;">Print</a>
                            <a href="/" class="btn btn-primary btn-sm">Back to Home</a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    @include('frontend.layouts.partials.footer')
</body>

</html>
