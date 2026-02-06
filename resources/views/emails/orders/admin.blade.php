<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Order Received</title>
</head>
<body style="margin:0; padding:0; background-color:#fafafa; font-family: Arial, sans-serif; color:#333; line-height:1.5;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#fafafa; padding:40px 0;">
    <tr>
        <td align="center">
            <!-- Container -->
            <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color:#fff8f9; border-radius:12px; padding:25px; border:1px solid #ff88a6;">
                
                <!-- Header -->
                <tr>
                    <td align="center" style="font-size:24px; font-weight:bold; color:#ff5887; padding-bottom:20px;">
                        New Order Received
                    </td>
                </tr>

                <!-- Customer & Order Details -->
                <tr>
                    <td style="background-color:#ffffff; padding:18px; border-radius:10px; margin-bottom:25px;">
                        <p style="margin:6px 0; font-size:0.95rem;"><strong>Order Number:</strong> <span style="color:#ff5887; font-weight:600;">{{ $order->order_number }}</span></p>
                        <p style="margin:6px 0; font-size:0.95rem;"><strong>Customer:</strong> <span style="color:#ff5887; font-weight:600;">{{ $order->customer_first_name }} {{ $order->customer_last_name }}</span></p>
                        <p style="margin:6px 0; font-size:0.95rem;"><strong>Email:</strong> <span style="color:#ff5887; font-weight:600;">{{ $order->customer_email }}</span></p>
                        <p style="margin:6px 0; font-size:0.95rem;"><strong>Phone:</strong> <span style="color:#ff5887; font-weight:600;">{{ $order->customer_phone }}</span></p>
                        @if(!empty($order->customer_instagram))
                        <p style="margin:6px 0; font-size:0.95rem;"><strong>Instagram:</strong> <span style="color:#ff5887; font-weight:600;">{{ $order->customer_instagram }}</span></p>
                        @endif
                        @if(!empty($order->order_notes))
                            <p style="margin:6px 0; font-size:0.95rem;"><strong>Order Notes:</strong><span style="color:#ff5887; font-weight:600;">{{ $order->order_notes }}</span></p>
                        @endif
                        <p style="margin:6px 0; font-size:0.95rem;"><strong>Delivery Type:</strong> <span style="color:#ff5887; font-weight:600;">{{ $order->delivery_type }}</span></p>

                        @if ($order->delivery_type === 'delivery')
                        <p style="margin:6px 0; font-size:0.95rem;"><strong>Delivery Address:</strong> <span style="color:#ff5887; font-weight:600;">{{ $order->delivery_address }}</span></p>
                        <p style="margin:6px 0; font-size:0.95rem;"><strong>Delivery Town:</strong> <span style="color:#ff5887; font-weight:600;">{{ $order->delivery_town }}</span></p>
                        <p style="margin:6px 0; font-size:0.95rem;"><strong>Delivery Postcode:</strong> <span style="color:#ff5887; font-weight:600;">{{ $order->delivery_postcode }}</span></p>
                        @else
                        <p style="margin:6px 0; font-size:0.95rem;"><strong>Pickup Location:</strong> <span style="color:#ff5887; font-weight:600;">{{ optional($order->pickupLocation)->name }}</span></p>
                        <p style="margin:6px 0; font-size:0.95rem;"><strong>Pickup Date:</strong> <span style="color:#ff5887; font-weight:600;">{{ $order->pickup_date }}</span></p>
                        <p style="margin:6px 0; font-size:0.95rem;"><strong>Pickup Time:</strong> <span style="color:#ff5887; font-weight:600;">{{ $order->pickup_time }}</span></p>
                        @endif

                        <p style="margin:6px 0; font-size:0.95rem;"><strong>Total Amount:</strong> <span style="color:#ff5887; font-weight:600;">£{{ number_format($order->total_amount, 2) }}</span></p>
                    </td>
                </tr>

                <!-- Order Items -->
                @if($order->items->count())
                <tr>
                    <td style="background-color:#ffffff; padding:18px; border-radius:10px; margin-bottom:25px;">
                        <p style="margin:6px 0; font-size:0.95rem; font-weight:bold;">Order Items:</p>
                        <table width="100%" cellpadding="5" cellspacing="0" border="1" style="border-collapse:collapse; border-color:#ff88a6;">
                            <thead>
                                <tr style="background-color:#ff88a6; color:#fff; text-align:left;">
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                <tr>
                                    <td style="padding:5px; font-size:0.95rem;">
                                        {{ $item->product->name ?? 'Item' }}
                                        @if(!empty($item->product_options))
                                            <ul style="margin: 6px 0 0 0; padding-left: 16px;">
                                                @foreach($item->product_options as $name => $value)
                                                    <li>
                                                        <strong>{{ $name }}:</strong> {{ $value }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td style="padding:5px; font-size:0.95rem;">{{ $item->quantity }}</td>
                                    <td style="padding:5px; font-size:0.95rem;">£{{ number_format($item->total_price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
                @endif

                <!-- CTA Button -->
                <tr>
                    <td align="center" style="padding:25px 0;">
                        <a href="{{ url('https://tutusfoods.com/dashboard/orders/' . $order->id) }}" style="background-color:#ff5887; color:#ffffff; text-decoration:none; padding:12px 22px; border-radius:6px; font-weight:bold; display:inline-block;">
                            View Order in Admin
                        </a>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td align="center" style="font-size:0.9rem; color:#555; padding-top:25px;">
                        Thanks,<br>
                        <strong style="color:#ff5887;">Tutu’s Foods</strong>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
