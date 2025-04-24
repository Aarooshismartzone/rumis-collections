<div id="carouselExampleIndicators" class="carousel slide">
    <!-- Carousel Indicators -->
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
            aria-current="true" aria-label="Slide 1"></button>

        @php 
            $ai_images = [];
            for ($i = 1; $i <= 6; $i++) {
                $field = "ai_$i";
                if (!empty($product->$field)) {
                    $ai_images[] = $product->$field;
                }
            }
        @endphp

        @foreach ($ai_images as $index => $ai_image)
            <button type="button" data-bs-target="#carouselExampleIndicators" 
                data-bs-slide-to="{{ $index + 1 }}" aria-label="Slide {{ $index + 2 }}"></button>
        @endforeach
    </div>

    <!-- Carousel Inner -->
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ asset('storage/' . $product->image) }}" 
                alt="{{ $product->product_name }}" class="master-product">
        </div>

        @foreach ($ai_images as $ai_image)
            <div class="carousel-item">
                <img src="{{ asset('storage/' . $ai_image) }}" 
                    alt="{{ $product->product_name }}" class="master-product">
            </div>
        @endforeach
    </div>

    <!-- Carousel Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
        data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
        data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
