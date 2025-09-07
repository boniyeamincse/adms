<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Report - {{ $period }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }

        .page-break { page-break-before: always; }

        /* Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-bottom: 3px solid #fff;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .header h2 {
            font-size: 18px;
            opacity: 0.9;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 12px;
            opacity: 0.8;
        }

        /* Summary Cards */
        .cards {
            display: table;
            width: 100%;
            margin: 20px 0;
        }

        .card {
            display: table-cell;
            padding: 15px;
            margin: 0 5px;
            background: #f8f9fa;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #007bff;
        }

        .card h3 {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }

        .card p {
            color: #666;
            font-size: 12px;
            margin-bottom: 8px;
        }

        .card .value {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }

        /* Content Sections */
        .section {
            margin: 25px 0;
        }

        .section h3 {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e74c3c;
        }

        /* Students Section */
        .students-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .students-row {
            display: table-row;
        }

        .students-cell {
            display: table-cell;
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }

        .students-cell:first-child {
            font-weight: bold;
            width: 25%;
            background: #f8f9fa;
        }

        .students-cell .number {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
        }

        .students-cell .percentage {
            font-size: 14px;
            color: #28a745;
            margin-top: 5px;
        }

        /* Fee Collection Table */
        .fees-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .fees-table th,
        .fees-table td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .fees-table th {
            background: #f8f9fa;
            font-weight: bold;
            font-size: 12px;
        }

        .currency {
            font-weight: bold;
            color: #28a745;
        }

        .percentage {
            font-weight: bold;
            color: #007bff;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-top: 2px solid #dee2e6;
            font-size: 10px;
            text-align: center;
            color: #666;
        }

        .footer p {
            margin-bottom: 5px;
        }

        .report-meta {
            font-size: 9px;
            color: #888;
            margin-top: 10px;
        }

        /* Status colors */
        .status-paid { color: #28a745; font-weight: bold; }
        .status-pending { color: #ffc107; font-weight: bold; }
        .status-overdue { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ $school_info['name'] }}</h1>
        <h2>Dashboard Summary Report</h2>
        <p>{{ $period }}</p>
    </div>

    <!-- Key Metrics -->
    <div class="section">
        <h3>📊 Key Performance Indicators</h3>
        <div class="cards">
            <div class="card">
                <h3>👨‍🎓 Students</h3>
                <p>Total Registered</p>
                <div class="value">{{ $stats['students']['total'] }}</div>
                <div class="percentage">{{ $stats['students']['active_percentage'] }}% Active</div>
            </div>
            <div class="card">
                <h3>🧾 Fees</h3>
                <p>Collection Rate</p>
                <div class="value">{{ $stats['fees']['paid_percentage'] }}%</div>
                <div class="percentage">৳{{ number_format($stats['fees']['paid_amount']) }} Collected</div>
            </div>
            <div class="card">
                <h3>📄 Admit Cards</h3>
                <p>Total Generated</p>
                <div class="value">{{ $stats['exams']['total_admit_cards'] }}</div>
                <div class="percentage">{{ $stats['exams']['upcoming'] }} Upcoming Exams</div>
            </div>
            <div class="card">
                <h3>💰 Payments</h3>
                <p>This Month</p>
                <div class="value">৳{{ number_format($stats['payments']['total_amount']) }}</div>
                <div class="percentage">{{ $stats['payments']['total'] }} Transactions</div>
            </div>
        </div>
    </div>

    <!-- Student Statistics -->
    <div class="section">
        <h3>👨‍🎓 Student Statistics</h3>
        <table class="students-grid">
            <tr class="students-row">
                <td class="students-cell">
                    <div>Total Students</div>
                    <div class="number">{{ $stats['students']['total'] }}</div>
                </td>
                <td class="students-cell">
                    <div>Active Students</div>
                    <div class="number">{{ $stats['students']['active'] }}</div>
                    <div class="percentage">{{ $stats['students']['active_percentage'] }}% of total</div>
                </td>
                <td class="students-cell">
                    <div>Inactive Students</div>
                    <div class="number">{{ $stats['students']['inactive'] }}</div>
                </td>
                <td class="students-cell">
                    <div>Total Classes</div>
                    <div class="number">{{ $stats['classes']['total_classes'] }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Fee Statistics -->
    <div class="section">
        <h3>💰 Fee Collection Statistics (This Month)</h3>
        <table class="students-grid">
            <tr class="students-row">
                <td class="students-cell">
                    <div>Total Fees</div>
                    <div class="number">{{ $stats['fees']['total'] }}</div>
                </td>
                <td class="students-cell">
                    <div>Paid Fees</div>
                    <div class="number">{{ $stats['fees']['paid'] }}</div>
                    <div class="percentage">{{ $stats['fees']['paid_percentage'] }}% collected</div>
                </td>
                <td class="students-cell">
                    <div>Pending Fees</div>
                    <div class="number">{{ $stats['fees']['total'] - $stats['fees']['paid'] }}</div>
                </td>
                <td class="students-cell">
                    <div>Collection Amount</div>
                    <div class="number">৳{{ number_format($stats['fees']['paid_amount']) }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Exam Statistics -->
    <div class="section">
        <h3>📝 Examination Statistics</h3>
        <table class="students-grid">
            <tr class="students-row">
                <td class="students-cell">
                    <div>Upcoming Exams</div>
                    <div class="number">{{ $stats['exams']['upcoming'] }}</div>
                </td>
                <td class="students-cell">
                    <div>Active Exams</div>
                    <div class="number">{{ $stats['exams']['active'] }}</div>
                </td>
                <td class="students-cell">
                    <div>Total Admit Cards</div>
                    <div class="number">{{ $stats['exams']['total_admit_cards'] }}</div>
                </td>
                <td class="students-cell">
                    <div>Classes</div>
                    <div class="number">{{ $stats['classes']['total_classes'] }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Payment Statistics -->
    <div class="section">
        <h3>💵 Payment Statistics (This Month)</h3>
        <table class="students-grid">
            <tr class="students-row">
                <td class="students-cell">
                    <div>Total Payments</div>
                    <div class="number">{{ $stats['payments']['total'] }}</div>
                </td>
                <td class="students-cell">
                    <div>Total Amount</div>
                    <div class="number">৳{{ number_format($stats['payments']['total_amount']) }}</div>
                </td>
                <td class="students-cell">
                    <div>Average Payment</div>
                    <div class="number">৳{{ number_format($stats['payments']['avg_payment'], 2) }}</div>
                </td>
                <td class="students-cell">
                    <div>Users</div>
                    <div class="number">{{ $stats['users']['total'] }}</div>
                    <div class="percentage">{{ $stats['users']['recent'] }} active this month</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Fee Collection by Class -->
    @if(count($fee_collection) > 0)
    <div class="section">
        <h3>🏫 Fee Collection by Class</h3>
        <table class="fees-table">
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Total Students</th>
                    <th>Total Fees</th>
                    <th>Paid Fees</th>
                    <th>Collection Rate</th>
                    <th>Paid Amount</th>
                    <th>Pending Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fee_collection as $classData)
                <tr>
                    <td>{{ $classData['class'] }}</td>
                    <td>{{ $classData['total_students'] }}</td>
                    <td>{{ $classData['total_fees'] }}</td>
                    <td>{{ $classData['paid_fees'] }}</td>
                    <td class="percentage">{{ $classData['collection_rate'] }}%</td>
                    <td class="currency">৳{{ number_format($classData['paid_amount']) }}</td>
                    <td class="currency">৳{{ number_format($classData['pending_amount']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>{{ $school_info['name'] }}</strong></p>
        <p>{{ $school_info['address'] }} • Phone: {{ $school_info['phone'] }}</p>
        <p>This dashboard report was generated automatically by the school's management system.</p>
        <div class="report-meta">
            Report generated on: {{ $generated_at->format('d/m/Y H:i:s') }}
        </div>
    </div>
</body>
</html>