<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layouts.partials.header')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/frontend/navbar-black.css') }}">
    <title>Shop - Rumi's Collections</title>
    <style>
        .empty-cart {
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: bold;
        }

        .see-more-btn {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body style="font-family: 'Poppins', serif">
    @include('frontend.layouts.partials.navbar-black')

    <div class="container">
        <div class="section-title pt-4">
            Shop for {{ isset($category) ? $category->category_name : 'products' }}
        </div>
        <div class="row mt-2" id="product-list">
            @foreach ($products as $product)
                @include('frontend.layouts.partials.list')
            @endforeach
        </div>

        <div class="see-more-btn">
            @if ($products->hasMorePages())
                <button id="loadMore" class="btn btn-primary" data-next-page="{{ $products->nextPageUrl() }}">See
                    More</button>
            @endif
        </div>
    </div>

    <script>
        $(document).on('click', '#loadMore', function() {
            var nextPage = $(this).data('next-page');

            if (!nextPage) return;

            $.get(nextPage, function(data) {
                $('#product-list').append($(data).find('#product-list').html());

                var newNextPage = $(data).find('#loadMore').data('next-page');
                if (newNextPage) {
                    $('#loadMore').data('next-page', newNextPage);
                } else {
                    $('#loadMore').remove();
                }
            });
        });
    </script>
</body>

</html>
