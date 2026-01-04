<!DOCTYPE html>
<html>

<head>
    <title>Receipt #{{ $bill->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background: #eee;
            padding: 20px;
        }

        .receipt-container {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border: 1px dashed #333;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .hospital-name {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .items-table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }

        .items-table th {
            text-align: left;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .items-table td {
            padding: 5px 0;
        }

        .total-row {
            border-top: 2px solid #000;
            font-weight: bold;
            font-size: 18px;
            margin-top: 20px;
            padding-top: 10px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
        }

        .btn-print {
            display: block;
            width: 100%;
            padding: 10px;
            background: #333;
            color: white;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            font-family: sans-serif;
        }

        @media print {
            body {
                background: white;
            }

            .receipt-container {
                border: none;
                box-shadow: none;
            }

            .btn-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="receipt-container">
        <div class="header">
            <div class="hospital-name">🏥 Laravel General Hospital</div>
            <p>123 Code Street, Tech City</p>
            <p>Tel: +254 700 000 000</p>
        </div>

        <div class="info-row">
            <span>Date:</span>
            <span>{{ $bill->created_at->format('d-M-Y H:i') }}</span>
        </div>
        <div class="info-row">
            <span>Receipt #:</span>
            <span>{{ str_pad($bill->id, 6, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="info-row">
            <span>Patient:</span>
            <span>{{ $bill->patient->name }}</span>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Consultation Fee</td>
                    <td style="text-align: right;">{{ number_format($bill->doctor_charge, 2) }}</td>
                </tr>
                <tr>
                    <td>Pharmacy / Meds</td>
                    <td style="text-align: right;">{{ number_format($bill->medicine_charge, 2) }}</td>
                </tr>
                <tr>
                    <td>Ward Charges</td>
                    <td style="text-align: right;">{{ number_format($bill->room_charge, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="info-row total-row">
            <span>TOTAL PAID:</span>
            <span>KES {{ number_format($bill->total_amount, 2) }}</span>
        </div>

        <div style="text-align: center; margin-top: 20px; font-weight: bold; border: 1px solid #000; padding: 5px;">
            PAID STAMP
        </div>

        <div class="footer">
            <p>Served by: {{ Auth::user()->name }}</p>
            <p>Thank you for trusting us with your health.</p>
            <p>Get Well Soon!</p>
        </div>

        <a href="#" onclick="window.print(); return false;" class="btn-print">🖨️ Print Receipt</a>
        <a href="{{ url('/home') }}" class="btn-print" style="background: #666; margin-top: 10px;">⬅ Back to Dashboard</a>
    </div>

</body>

</html>