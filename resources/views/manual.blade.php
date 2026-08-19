<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hospital Management System (HMS) - Master Operations Manual</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #0f172a; max-width: 900px; margin: 0 auto; padding: 30px; background: #f8fafc; }
        .cover { background: linear-gradient(135deg, #1e1b4b 0%, #4338ca 100%); color: #fff; padding: 40px; border-radius: 12px; margin-bottom: 30px; }
        h1 { font-size: 24px; margin-top: 0; }
        h2 { font-size: 18px; color: #4338ca; border-bottom: 2px solid #e0e7ff; padding-bottom: 5px; margin-top: 30px; }
        h3 { font-size: 14px; color: #1e293b; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; background: #fff; font-size: 13px; }
        th, td { border: 1px solid #cbd5e1; padding: 10px; text-align: left; }
        th { background: #1e1b4b; color: #fff; }
        tr:nth-child(even) { background: #f1f5f9; }
        .callout { background: #eff6ff; border-left: 4px solid #3b82f6; padding: 15px; border-radius: 0 8px 8px 0; margin: 15px 0; }
        .danger { background: #fee2e2; border-left: 4px solid #dc2626; color: #991b1b; padding: 15px; border-radius: 0 8px 8px 0; margin: 15px 0; }
        .btn-print { position: fixed; top: 20px; right: 20px; background: #4338ca; color: #fff; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <a href="#" onclick="window.print();return false;" class="btn-print">🖨️ Print / Save as PDF</a>

    <div class="cover">
        <h1>Hospital Platform (HMS & EHR)</h1>
        <p>Comprehensive User, Administrator, and Technical Operations Manual</p>
        <p><small>System Version: v2.4.0-PROD | Laravel 12 / PHP 8.5 Stack | August 2026</small></p>
    </div>

    <h2>1. System Overview & Architecture</h2>
    <p>The Hospital Platform is a monolithic role-based system designed to unify reception intake, nursing triage, doctor consultations, laboratory diagnostics, pharmacy dispensing, cashier invoicing, and longitudinal EHR archiving.</p>
    
    <div class="callout">
        <strong>Core Pipeline:</strong> Reception (/patients) → Triage (/triage) → Doctor (/doctor/queue) → Lab & Pharmacy → Billing (/billing) → Longitudinal EHR Archive (/patient-records).
    </div>

    <h2>2. Role Access & Permission Matrix</h2>
    <table>
        <tr><th>Module</th><th>Admin</th><th>Reception</th><th>Nurse</th><th>Doctor</th><th>Lab</th><th>Pharmacy</th><th>Cashier</th></tr>
        <tr><td>Reception Intake (/patients)</td><td>✅</td><td>✅</td><td>❌</td><td>❌</td><td>❌</td><td>❌</td><td>❌</td></tr>
        <tr><td>EHR Record Archive (/patient-records)</td><td>✅</td><td>✅</td><td>✅</td><td>✅</td><td>❌</td><td>❌</td><td>❌</td></tr>
        <tr><td>Triage Queue (/triage)</td><td>✅</td><td>❌</td><td>✅</td><td>✅</td><td>❌</td><td>❌</td><td>❌</td></tr>
        <tr><td>Doctor Console (/doctor/queue)</td><td>✅</td><td>❌</td><td>❌</td><td>✅</td><td>❌</td><td>❌</td><td>❌</td></tr>
        <tr><td>Laboratory Queue (/lab/queue)</td><td>✅</td><td>❌</td><td>❌</td><td>✅</td><td>✅</td><td>❌</td><td>❌</td></tr>
        <tr><td>Pharmacy Dispensary (/pharmacy)</td><td>✅</td><td>❌</td><td>❌</td><td>❌</td><td>❌</td><td>✅</td><td>❌</td></tr>
        <tr><td>Cashier Billing (/billing)</td><td>✅</td><td>❌</td><td>❌</td><td>❌</td><td>❌</td><td>❌</td><td>✅</td></tr>
        <tr><td>Revenue Control (/revenue-control)</td><td>✅</td><td>❌</td><td>❌</td><td>❌</td><td>❌</td><td>❌</td><td>✅</td></tr>
    </table>

    <h2>3. Step-by-Step Departmental Workflows</h2>
    <h3>Reception & Patient Intake</h3>
    <ol>
        <li>Navigate to <strong>Reception</strong> (<code>/patients</code>).</li>
        <li>Search the patient's phone number or National ID in the search bar.</li>
        <li>If existing, click <strong>+ New Visit</strong>. If new, fill out the <strong>Register New Patient</strong> form and click <strong>Register & Queue Patient</strong>.</li>
    </ol>

    <h3>Nursing Triage</h3>
    <ol>
        <li>Open the <strong>Triage Queue</strong> (<code>/triage</code>).</li>
        <li>Record Blood Pressure, Temperature, Pulse Rate, Respiratory Rate, Weight, and Height.</li>
        <li>Click <strong>Save & Forward to Doctor</strong>.</li>
    </ol>

    <h3>Doctor Consultation</h3>
    <ol>
        <li>Open the <strong>Doctor Queue</strong> (<code>/doctor/queue</code>).</li>
        <li>Click <strong>📋 Past History</strong> to inspect the longitudinal EHR timeline.</li>
        <li>Document Chief Complaints, Clinical Findings, and Diagnosis.</li>
        <li>Issue Lab Orders and Electronic Prescriptions, then click <strong>Finalize Consultation</strong>.</li>
    </ol>

    <h3>Pharmacy & Lab Fulfillment</h3>
    <ul>
        <li><strong>Laboratory (/lab/queue):</strong> Collect specimen, mark sample collected, and publish results.</li>
        <li><strong>Pharmacy (/pharmacy):</strong> Verify prescription items against inventory shelf stock and click <strong>Dispense</strong>.</li>
    </ul>

    <h3>Billing & Cashier Settlement</h3>
    <ol>
        <li>Open <strong>Billing</strong> (<code>/billing</code>) and open the active encounter invoice.</li>
        <li>Verify itemized fees (Consultation, Lab, Pharmacy, Nursing).</li>
        <li>Select Tender Type (Cash, M-Pesa, Corporate Scheme), enter reference, and process payment.</li>
        <li>Click <strong>Print Official Receipt</strong> to close the encounter.</li>
    </ol>

    <h2>4. Master Troubleshooting Directory</h2>
    <table>
        <tr><th>Problem</th><th>Likely Cause</th><th>Immediate User Fix</th><th>Technical IT Solution</th></tr>
        <tr><td>419 Page Expired</td><td>CSRF Token Timeout</td><td>Hard refresh (Ctrl+F5) and re-login</td><td>Flush sessions table</td></tr>
        <tr><td>500 Server Error</td><td>Missing relation/cache glitch</td><td>Refresh page after 30 seconds</td><td>Run <code>php artisan optimize:clear</code></td></tr>
        <tr><td>Patient missing in Doctor room</td><td>Triage vitals not submitted</td><td>Complete vitals in /triage</td><td>Check encounter state in DB</td></tr>
        <tr><td>Drug stock shows 0</td><td>Catalog balance exhausted</td><td>Alert Pharmacy Lead</td><td>Adjust inventory in /pharmacy</td></tr>
        <tr><td>SQLite database locked</td><td>Concurrent write collision</td><td>Wait 5 seconds and retry</td><td>Check disk I/O and process locks</td></tr>
    </table>

    <div class="danger">
        <strong>⚠️ CRITICAL SECURITY WARNING (DO NOT DO THIS):</strong>
        <br>1. Never share login credentials or leave active terminals unattended.
        <br>2. Never photograph patient records with personal mobile devices.
        <br>3. Never modify the SQLite database file directly with external tools.
    </div>

    <h2>5. System Gaps & Recommendations</h2>
    <table>
        <tr><th>Finding</th><th>Severity</th><th>Impact</th><th>Recommended Action</th></tr>
        <tr><td>SQLite Concurrency Limits</td><td>HIGH</td><td>Risk of database lock under heavy traffic</td><td>Migrate production DB to MySQL 8.x / MariaDB</td></tr>
        <tr><td>Inpatient (IPD) Module Missing</td><td>MEDIUM</td><td>No dedicated ward/bed management screens</td><td>Develop IPD ward migration schema</td></tr>
        <tr><td>Manual Database Backups</td><td>HIGH</td><td>Backups rely on terminal commands</td><td>Configure automated cron backup via spatie-backup</td></tr>
    </table>

</body>
</html>
</html>
