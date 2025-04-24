<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layouts.partials.header')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/frontend/navbar-white.css') }}">
    <title>{{ $generics['title'] ?? 'Site Title' }}</title>
</head>

<body style="font-family: 'Poppins', serif">
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
                    @foreach ($category->products->sortByDesc('created_at')->take(4) as $product)
                        @include('frontend.layouts.partials.list')
                    @endforeach
                    <div class="text-center">
                        <div style="position: relative" class="mt-3">
                            <a href="#" class="smr-btn">See More</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>

</html>
<!-- DEVELOPED BY AAROOSHI.COM -->
