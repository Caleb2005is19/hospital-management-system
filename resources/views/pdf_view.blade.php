<!DOCTYPE html>
<html>
<head>
    <title>Medical Prescription</title>
    <style>
        body { font-family: sans-serif; padding: 40px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
        .hospital-name { font-size: 24px; font-weight: bold; text-transform: uppercase; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .rx-box { border: 2px solid #000; padding: 20px; min-height: 200px; margin-top: 20px; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; }
        .btn-print { background: #333; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        @media print { .btn-print { display: none; } }
    </style>
</head>
<body>

    <div class="header">
        <div class="hospital-name">🏥 St. Laravel Hospital</div>
        <p>123 Web Framework St, Code City | Phone: 555-0199</p>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>Patient Name:</strong> {{ $appointment->patient->name }}</td>
            <td style="text-align: right;"><strong>Date:</strong> {{ $appointment->date }}</td>
        </tr>
        <tr>
            <td><strong>Email:</strong> {{ $appointment->patient->email }}</td>
            <td style="text-align: right;"><strong>Ref ID:</strong> #{{ $appointment->id }}</td>
        </tr>
    </table>

    <h3>Diagnosis:</h3>
    <p>{{ $appointment->reason }}</p>

    <div class="rx-box">
        <h3>💊 Rx (Prescription):</h3>
        <p style="font-size: 18px; line-height: 1.6;">
            {!! nl2br(e($appointment->prescription)) !!}
        </p>
    </div>

    <div class="footer">
        <p>Doctor Signature: __________________________</p>
        <p>This is a computer-generated document.</p>
    </div>

    <div style="text-align: center; margin-top: 40px;">
        <button onclick="window.print()" class="btn-print">🖨️ Click to Print</button>
    </div>

</body>
</html>