$(document).ready(function () {
    function updateCartSummary() {
        $.ajax({
            url: refreshTotals,
            type: "POST",
            data: {
                _token: csrfToken
            },
            success: function (html) {
                $(".cart-summary").html(html); // This now contains updated delivery charge logic
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
                    if (response.cart_empty) {
                        location.reload();
                    }

                    if (response.no_item) {
                        $(".cart-item-" + cartId).fadeOut(300, function () {
                            $(this).remove();
                            updateCartSummary(); // Update after removal
                        });
                    } else {
                        $(".cart-quantity-" + cartId).text(response.newQuantity);
                        let total = (response.newQuantity * response.itemRate).toFixed(2);
                        total = Number(total).toLocaleString('en-IN', { minimumFractionDigits: 2 });
                        $(".cart-amount-" + cartId).text("₹" + total);
                        updateCartSummary(); // Update after quantity change
                    }
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert("Something went wrong!");
            }
        });
    });

    // Initial cart summary load
    updateCartSummary();
});
