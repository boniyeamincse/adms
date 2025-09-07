<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Fee Report - {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }

        .page-break { page-break-after: always; }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 11px;
            opacity: 0.8;
        }

        .report-info {
            background: #f8f9fa;
            padding: 15px;
            border-left: 5px solid #007bff;
            margin-bottom: 20px;
        }

        .report-info h3 {
            color: #007bff;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-row { display: table-row; }
        .info-cell {
            display: table-cell;
            padding: 5px 0;
        }

        .info-cell:first-child {
            font-weight: bold;
            width: 40%;
        }

        .stats-section {
            background: #ffffff;
            border: 1px solid #e9ecef;
            margin-bottom: 20px;
            padding: 15px;
        }

        .stats-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .stats-header {
            display: table-header-group;
            background: #f8f9fa;
        }

        .stats-row {
            display: table-row;
            border-bottom: 1px solid #dee2e6;
        }

        .stats-cell {
            display: table-cell;
            padding: 12px 8px;
            text-align: center;
            vertical-align: middle;
        }

        .stats-cell:first-child {
            text-align: left;
            font-weight: bold;
        }

        .total-row {
            background: #007bff !important;
            color: white !important;
            font-weight: bold;
        }

        .fees-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .fees-table th,
        .fees-table td {
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            text-align: left;
        }

        .fees-table th {
            background: #f8f9fa;
            font-weight: bold;
            font-size: 11px;
        }

        .status-paid { color: #28a745; font-weight: bold; }
        .status-pending { color: #ffc107; font-weight: bold; }
        .status-overdue { color: #dc3545; font-weight: bold; }

        .footer {
            margin-top: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
            font-size: 9px;
            text-align: center;
        }

        .currency {
            font-weight: bold;
            color: #28a745;
        }

        .large-number {
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <!-- Report Header -->
    <div class="header">
        <h1>{{ $school_info['name'] }}</h1>
        <h2>Monthly Fee Collection Report</h2>
        <p>{{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</p>
    </div>

    <!-- Report Info -->
    <div class="report-info">
        <h3>Report Summary</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell">Report Period:</div>
                <div class="info-cell">{{ date('01/m/Y', mktime(0, 0, 0, $month, 1, $year)) }} - {{ date('t/m/Y', mktime(0, 0, 0, $month, 1, $year)) }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell">Generated On:</div>
                <div class="info-cell">{{ now()->format('d/m/Y H:i:s') }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell">Total Fees:</div>
                <div class="info-cell"><span class="large-number">{{ $fees->count() }}</span> fees</div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="stats-section">
        <h3 style="margin-bottom: 15px; color: #007bff; font-size: 14px;">Collection Summary</h3>
        <table class="stats-grid">
            <thead class="stats-header">
                <tr class="stats-row">
                    <th class="stats-cell">Status</th>
                    <th class="stats-cell">Count</th>
                    <th class="stats-cell">Amount</th>
                    <th class="stats-cell">Percentage</th>
                </tr>
            </thead>
            <tbody>
                <tr class="stats-row">
                    <td class="stats-cell">Paid</td>
                    <td class="stats-cell">{{ $statistics['paid_fees'] }}</td>
                    <td class="stats-cell"><span class="currency">৳{{ number_format($statistics['paid_amount'], 2) }}</span></td>
                    <td class="stats-cell">
                        @if($fees->count() > 0)
                            {{ number_format(($statistics['paid_fees'] / $fees->count()) * 100, 1) }}%
                        @else
                            0.0%
                        @endif
                    </td>
                </tr>
                <tr class="stats-row">
                    <td class="stats-cell">Pending</td>
                    <td class="stats-cell">{{ $statistics['pending_fees'] }}</td>
                    <td class="stats-cell"><span class="currency">৳{{ number_format($statistics['pending_amount'], 2) }}</span></td>
                    <td class="stats-cell">
                        @if($fees->count() > 0)
                            {{ number_format(($statistics['pending_fees'] / $fees->count()) * 100, 1) }}%
                        @else
                            0.0%
                        @endif
                    </td>
                </tr>
                <tr class="stats-row">
                    <td class="stats-cell">Overdue</td>
                    <td class="stats-cell">{{ $statistics['overdue_fees'] }}</td>
                    <td class="stats-cell"><span class="currency">৳{{ number_format($statistics['overdue_amount'], 2) }}</span></td>
                    <td class="stats-cell">
                        @if($fees->count() > 0)
                            {{ number_format(($statistics['overdue_fees'] / $fees->count()) * 100, 1) }}%
                        @else
                            0.0%
                        @endif
                    </td>
                </tr>
                <tr class="stats-row total-row">
                    <td class="stats-cell">TOTAL</td>
                    <td class="stats-cell">{{ $fees->count() }}</td>
                    <td class="stats-cell"><span class="currency">৳{{ number_format($statistics['total_amount'], 2) }}</span></td>
                    <td class="stats-cell">100.0%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Detailed Fees Report -->
    <h3 style="margin-bottom: 15px; color: #007bff; font-size: 14px;">Detailed Fee Records</h3>
    <table class="fees-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Student Name</th>
                <th>Roll No</th>
                <th>Class</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fees->sortBy('created_at') as $fee)
                <tr>
                    <td>{{ $fee->created_at->format('d/m/Y') }}</td>
                    <td>{{ $fee->student->name }}</td>
                    <td>{{ $fee->student->roll_no }}</td>
                    <td>{{ $fee->student->schoolClass->class_name }}</td>
                    <td class="currency">৳{{ number_format($fee->amount, 2) }}</td>
                    <td class="status-{{ $fee->status }}">
                        {{ ucfirst($fee->status) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p><strong>{{ $school_info['name'] }}</strong></p>
        <p>This report was generated automatically by the school's fee management system.</p>
        <p>Report generated on: {{ now()->format('d/m/Y at H:i:s') }}</p>
    </div>
</body>
</html>