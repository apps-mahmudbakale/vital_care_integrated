<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $ref }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 12px; margin: 0; padding: 20px; }
        .receipt-container { max-width: 400px; margin: auto; border: 1px solid #eee; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { text-align: center; border-bottom: 1px dashed #000; padding-bottom: 10px; margin-bottom: 10px; }
        .header h2 { margin: 5px 0; }
        .details-row { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { text-align: left; padding: 5px 0; }
        .table th { border-bottom: 1px solid #000; }
        .total { border-top: 1px dashed #000; margin-top: 10px; padding-top: 5px; font-weight: bold; }
        .footer { text-align: center; margin-top: 20px; font-style: italic; font-size: 10px; }
        @media print {
            body { padding: 0; }
            .receipt-container { border: none; box-shadow: none; width: 100%; max-width: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <h2>VITAL CARE EMR</h2>
            <p>123 Medical Way, Health City</p>
            <p>Tel: +234 800 000 0000</p>
            <p><strong>PAYMENT RECEIPT</strong></p>
        </div>

        <div class="details">
            <div class="details-row"><span>Receipt No:</span> <span>#{{ $ref }}</span></div>
            <div class="details-row"><span>Date:</span> <span>{{ date('d-M-Y H:i') }}</span></div>
            <div class="details-row"><span>Patient:</span> <span>{{ $billing->patient->user->firstname }} {{ $billing->patient->user->lastname }}</span></div>
            <div class="details-row"><span>Hospital No:</span> <span>{{ $billing->patient->hospital_no }}</span></div>
            <div class="details-row"><span>Cashier:</span> <span>{{ auth()->user()->firstname }}</span></div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Service</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->service }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">{{ number_format($item->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            <div class="details-row" style="font-size: 14px;">
                <span>TOTAL PAID:</span>
                <span>₦ {{ number_format($payment->paying_amount ?? $amount, 2) }}</span>
            </div>
            <div class="details-row">
                <span>Payment Method:</span>
                <span>{{ $payment->payment_method ?? 'N/A' }}</span>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for choosing Vital Care!</p>
            <p>This is a computer-generated receipt.</p>
        </div>

        <div class="no-print" style="margin-top: 20px; text-align: center;">
            <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Print Receipt</button>
            <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer; margin-left: 10px;">Close</button>
        </div>
    </div>

    <script>
        // Auto-trigger print if needed
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
