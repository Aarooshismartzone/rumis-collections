@php
    $freeDeliveryMin = $generics['delivery_free_min_price'] ?? 0;
    $delivery = ($totalPrice >= ($generics['delivery_free_min_price'] ?? 0)) ? 0 : ($generics['delivery_charges'] ?? 0);
    $gst = $generics['is_gst'] ? $totalPrice * ($generics['gst_rate_percent'] / 100) : 0;
    $grandTotal = $totalPrice + $gst + $delivery;
@endphp

<table class="w-100">
    <tr style="font-weight: bold;">
        <td>Total Price</td>
        <td id="cart-total-price" style="text-align: right">₹{{ number_format($totalPrice, 2) }}</td>
    </tr>
    <tr style="font-style: italic">
        <td>Delivery Charge</td>
        <td id="delivery-charge" style="text-align: right">₹{{ number_format($delivery, 2) }}</td>
    </tr>
    @if ($generics['is_gst'])
        <tr style="font-style: italic">
            <td>Taxes and Fees (GST)</td>
            <td id="tax-amount" style="text-align: right">₹{{ number_format($gst, 2) }}</td>
        </tr>
    @endif
    <tr style="font-weight: bold; color: green;">
        <td>Amount you pay</td>
        <td id="total-amount" style="text-align: right">₹{{ number_format($grandTotal, 2) }}</td>
    </tr>
</table>
