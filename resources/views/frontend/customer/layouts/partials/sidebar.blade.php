<div id="sidebar" class="sidebar collapsed">
    <div class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-angle-right"></i>
    </div>

    <a href="/graahak/dashboard" class="nav-link mt-5" role="tab">
        <i class="fas fa-tachometer-alt"></i> <span class="link-text">Dashboard</span>
    </a>

    <a href="/graahak/addresses" class="nav-link" role="tab">
        <i class="fas fa-shopping-cart"></i> <span class="link-text">Addresses</span>
    </a>

    <a href="{{ route('customer.orders') }}" class="nav-link" role="tab">
        <i class="fas fa-users"></i> <span class="link-text">Orders</span>
    </a>

    <a href="/graahak/profile" class="nav-link" role="tab">
        <i class="fas fa-cog"></i> <span class="link-text">Profile</span>
    </a>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('expanded');
    }
</script>

<script>
    // Highlight active nav item
    $(document).ready(function () {
        let currentUrl = window.location.pathname;
        $(".nav-link").each(function () {
            if ($(this).attr("href") === currentUrl) {
                $(this).addClass("active");
            }
        });
    });
</script>
