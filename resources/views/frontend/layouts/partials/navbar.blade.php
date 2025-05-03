<nav class="navbar navbar-expand-lg navbar-transparent position-absolute w-100" style="z-index: 1">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="/">
            <img src="{{ asset('images/logo/logo_white.png') }}" class="navbar-logo">
        </a>
        <div class="d-flex">
            <div class="align-items-center icons-m-view" style="font-size: 14px">
                <a href="{{ route('frontend.shoppingcart') }}" class="text-white me-3 position-relative">
                    <i class="fas fa-shopping-cart"></i>
                    @if ($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('frontend.wishlist') }}" class="text-white me-3 position-relative">
                    <i class="fas fa-heart"></i>
                    @if ($wishlistCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                            {{ $wishlistCount }}
                        </span>
                    @endif
                </a>
                <a href="#" class="text-white">
                    <i class="fas fa-user"></i>
                </a>
            </div>

            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active text-white" aria-current="page" href="/">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="{{ route('frontend.shop') }}" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Shop
                    </a>
                    <ul class="dropdown-menu">
                        @foreach ($categories as $category)
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('frontend.shop', ['category_slug' => $category->category_slug]) }}">
                                    {{ $category->category_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Contact</a>
                </li>
            </ul>
            <form class="d-flex align-items-center position-relative me-3" role="search" method="GET" action="{{ route('frontend.search') }}">
                <input name="query" class="form-control me-2 searchbox" type="search" placeholder="Search" aria-label="Search">
                <button class="btn search-icon" style="margin-left: -10px; margin-right: 10px;" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>            
            <div class="align-items-center icons-d-view" style="font-size: 14px">
                <a href="{{ route('frontend.shoppingcart') }}" class="text-white me-3 position-relative">
                    <i class="fas fa-shopping-cart"></i>
                    @if ($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('frontend.wishlist') }}" class="text-white me-3 position-relative">
                    <i class="fas fa-heart"></i>
                    @if ($wishlistCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                            {{ $wishlistCount }}
                        </span>
                    @endif
                </a>
                <a href="#" class="text-white">
                    <i class="fas fa-user"></i>
                </a>
            </div>
        </div>
    </div>
</nav>
