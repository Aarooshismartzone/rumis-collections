$(document).ready(function () {
    function updateCartSummary() {
        $.ajax({
            url: refreshTotals,
            type: "POST",
            data: {
                _token: csrfToken
            },
            success: function (html) {
                $(".cart-summary").html(html);
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert("Failed to refresh totals.");
            }
        });
    }

    $(".update-quantity").click(function () {
        var cartId = $(this).data("cart-id");
        var action = $(this).data("action");

        $.ajax({
            url: cartUpdateUrl,
            type: "POST",
            data: {
                _token: csrfToken,
                cart_id: cartId,
                action: action
            },
            success: function (response) {
                if (response.success) {
                    $(".cart-quantity-" + cartId).text(response.newQuantity);
                    let total = (response.newQuantity * response.itemRate).toFixed(2);
                    total = Number(total).toLocaleString('en-IN', { minimumFractionDigits: 2 });
                    $(".cart-amount-" + cartId).text("₹" + total);

                    if (response.newQuantity === 0) {
                        $(".cart-item-" + cartId).fadeOut(300, function () {
                            $(this).remove();
                        });
                    }

                    // Refresh totals from server
                    updateCartSummary();
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert("Something went wrong!");
            }
        });
    });

    // Initial call to load the latest cart summary on page load
    updateCartSummary();
});
