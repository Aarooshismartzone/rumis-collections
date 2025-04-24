<div class="nav flex-column nav-pills me-3 sidebar" id="v-pills-tab" role="tablist" aria-orientation="vertical">
    <a href="/backend/dashboard" class="nav-link" role="tab">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>

    <div class="nav-item">
        <a href="#categoriesSubmenu" class="nav-link dropdown-toggle" data-bs-toggle="collapse">
            <i class="fas fa-list-alt"></i> Categories
        </a>
        <div class="collapse" id="categoriesSubmenu">
            <a href="/backend/categories" class="nav-link ms-3">View Categories</a>
            <a href="/backend/categories/add" class="nav-link ms-3">Add a Category</a>
        </div>
    </div>

    <div class="nav-item">
        <a href="#productsSubmenu" class="nav-link dropdown-toggle" data-bs-toggle="collapse">
            <i class="fas fa-box"></i> Products
        </a>
        <div class="collapse" id="productsSubmenu">
            <a href="/backend/products" class="nav-link ms-3">View Products</a>
            <a href="/backend/products/add" class="nav-link ms-3">Add a Product</a>
        </div>
    </div>

    <a href="/backend/orders" class="nav-link" role="tab">
        <i class="fas fa-shopping-cart"></i> Orders
    </a>
    <a href="/backend/customers" class="nav-link" role="tab">
        <i class="fas fa-users"></i> Customers
    </a>
    <a href="/backend/settings" class="nav-link" role="tab">
        <i class="fas fa-cog"></i> Settings
    </a>
</div>

<script>
    $(document).ready(function () {
        let currentUrl = window.location.pathname;

        $(".nav-link").each(function () {
            if ($(this).attr("href") === currentUrl) {
                $(this).addClass("active");
                $(this).closest(".collapse").addClass("show"); // Expand parent submenu if applicable
            }
        });
    });
</script>
