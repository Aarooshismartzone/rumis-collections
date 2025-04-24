@php
    $gst = $generics['is_gst'] ? $totalPrice * ($generics['gst_rate_percent'] / 100) : 0;
    $delivery = $generics['delivery_charges'] ?? 0;
    $grandTotal = $totalPrice + $gst + $delivery;
@endphp

<table class="w-100">
    <tr>
        <td>Total Price</td>
        <td id="cart-total-price" style="text-align: right">₹{{ number_format($totalPrice, 2) }}</td>
    </tr>
    <tr>
        <td>Delivery Charge</td>
        <td id="delivery-charge" style="text-align: right">₹{{ number_format($delivery, 2) }}</td>
    </tr>
    @if ($generics['is_gst'])
        <tr>
            <td>Taxes and Fees (GST)</td>
            <td id="tax-amount" style="text-align: right">₹{{ number_format($gst, 2) }}</td>
        </tr>
    @endif
    <tr class="font-bold">
        <td>Amount you pay</td>
        <td id="total-amount" style="text-align: right">₹{{ number_format($grandTotal, 2) }}</td>
    </tr>
</table>
