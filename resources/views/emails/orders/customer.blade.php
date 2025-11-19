<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Confirmation</title>
</head>
<body style="margin:0; padding:0; background-color:#fafafa; font-family: Arial, sans-serif; line-height:1.5; color:#333;">

  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#fafafa; padding:20px 0;">
    <tr>
      <td align="center">
        <!-- Container -->
        <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color:#fff8f9; border:1px solid #ff88a6; border-radius:12px; padding:24px;">
          
          <!-- Header with Logo -->
            <tr>
            <td align="center" style="padding-bottom:24px;">
                <table cellpadding="0" cellspacing="0" border="0" style="display:inline-table; vertical-align:middle;">
                <tr>
                    <!-- Logo -->
                    <td style="padding-right:10px; vertical-align:middle;">
                    <img src="https://tutusfoods.com/storage/tutus_logo.webp" alt="Tutu’s Foods" width="40" height="40" style="display:block; border:0;">
                    </td>
                    <!-- Brand Name -->
                    <td style="font-size:24px; font-weight:bold; color:#ff5887; vertical-align:middle;">
                    Tutu’s Foods
                    </td>
                </tr>
                </table>
            </td>
            </tr>


          <!-- Welcome Panel -->
          <tr>
            <td style="background-color:#ffffff; border-left:4px solid #ff5887; padding:16px; border-radius:6px; margin-bottom:24px;">
              <h1 style="font-size:20px; margin:0 0 12px 0; color:#ff5887;">🎉 Thank You for Your Order, {{ $order->customer_first_name }}!</h1>
              <p style="margin:0;">We’re excited to prepare your order. Here’s a summary:</p>
            </td>
          </tr>

          <!-- Order Overview -->
          <tr>
            <td style="padding-top:16px; padding-bottom:16px;">
              <h2 style="font-size:18px; color:#ff5887; margin:0 0 12px 0;">🧾 Order Overview</h2>
              <p style="margin:6px 0;"><strong>Order Number:</strong> <span style="color:#ff5887; font-weight:600;">{{ $order->order_number }}</span></p>
              <p style="margin:6px 0;"><strong>Total Amount:</strong> £{{ number_format($order->total_amount, 2) }}</p>
              <p style="margin:6px 0;"><strong>Delivery Type:</strong> {{ $order->delivery_type }}</p>
              @if ($order->delivery_type === 'delivery')
              <p style="margin:6px 0;"><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
              @else
              <p style="margin:6px 0;"><strong>Pickup Location:</strong> {{ optional($order->pickupLocation)->name }}</p>
              <p style="margin:6px 0;"><strong>Pickup Date:</strong> {{ $order->pickup_date }}</p>
              <p style="margin:6px 0;"><strong>Pickup Time:</strong> {{ $order->pickup_time }}</p>
              @endif
            </td>
          </tr>

          <!-- Order Items Table -->
          <tr>
            <td style="padding-top:16px; padding-bottom:16px;">
              <h2 style="font-size:18px; color:#ff5887; margin:0 0 12px 0;">🍰 Order Items</h2>
              <table width="100%" cellpadding="5" cellspacing="0" border="1" style="border-collapse:collapse; border-color:#ff88a6;">
                <thead>
                  <tr style="background-color:#ff88a6; color:#fff; text-align:left;">
                    <th style="padding:8px;">Item</th>
                    <th style="padding:8px;">Qty</th>
                    <th style="padding:8px;">Price</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($order->items as $item)
                  <tr>
                    <td style="padding:8px;">
                      {{ $item->product->name ?? 'Item' }}
                      @if($item->product_options)
                      <div style="font-size:12px; color:#888;">{{ $item->product_options }}</div>
                      @endif
                    </td>
                    <td style="padding:8px;">{{ $item->quantity }}</td>
                    <td style="padding:8px;">£{{ number_format($item->total_price, 2) }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </td>
          </tr>

          <!-- Thank You Section -->
          <tr>
            <td style="padding-top:16px; padding-bottom:16px;">
              <h2 style="font-size:18px; color:#ff5887; margin:0 0 12px 0;">💖 Thank You!</h2>
              <p style="margin:6px 0;">Thank you for choosing <strong>Tutu’s Foods.</strong> Honest, handcrafted food — inspired by tradition, perfected for today.</p>
              <p style="margin:6px 0;">If you have any questions, reply to this email or reach us at:<br>📞 <strong>+443333392295</strong></p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td align="center" style="font-size:12px; color:#666; padding-top:24px;">
              © {{ date('Y') }} Tutu’s Foods. All rights reserved.
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>

</body>
</html>
