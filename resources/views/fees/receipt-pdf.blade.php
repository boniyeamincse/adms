<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Receipt - {{ $school_info['receipt_no'] }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }

        /* Header */
        .header {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #007bff;
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 8px;
        }

        .header p {
            font-size: 11px;
            color: #666;
            margin-bottom: 3px;
        }

        .receipt-title {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
            margin-top: 15px;
            text-transform: uppercase;
        }

        /* Receipt Number */
        .receipt-number {
            background: #007bff;
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        /* Main Content */
        .content {
            padding: 20px;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
            text-transform: uppercase;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }

        /* Table Styles */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #ddd;
        }

        .info-table td:first-child {
            font-weight: bold;
            width: 35%;
            background-color: #f8f9fa;
        }

        .info-table td:nth-child(2) {
            width: 65%;
        }

        /* Amount Section */
        .amount-section {
            background: #f8f9fa;
            border: 2px solid #28a745;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
        }

        .amount-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
        }

        .amount-value {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }

        /* Payment Status */
        .status-section {
            text-align: center;
            margin-top: 20px;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
        }

        .status-paid {
            background: #28a745;
            color: white;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
            margin-top: 20px;
        }

        .footer p {
            margin-bottom: 5px;
        }

        .generated-info {
            font-size: 9px;
            color: #888;
            margin-top: 10px;
        }

        /* Divider */
        .divider {
            border: none;
            height: 2px;
            background: #ddd;
            margin: 15px 0;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            color: rgba(52, 152, 219, 0.08);
            font-weight: bold;
            z-index: -1;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div style="position: relative;">
        <div class="watermark">{{ $school_info['name'] }}</div>

        <!-- Header -->
        <div class="header">
            <h1>{{ $school_info['name'] }}</h1>
            <p>{{ $school_info['address'] }}</p>
            <p>Phone: {{ $school_info['phone'] }} | Email: {{ $school_info['website'] }}</p>
            <div class="receipt-title">Fee Payment Receipt</div>
        </div>

        <!-- Receipt Number -->
        <div class="receipt-number">
            Receipt No: {{ $school_info['receipt_no'] }}
        </div>

        <!-- Main Content -->
        <div class="content">
            <!-- Student Information -->
            <div class="info-section">
                <div class="section-title">Student Details</div>
                <table class="info-table">
                    <tr>
                        <td>Student Name</td>
                        <td>{{ $fee->student->name }}</td>
                    </tr>
                    <tr>
                        <td>Roll Number</td>
                        <td>{{ $fee->student->roll_no }}</td>
                    </tr>
                    <tr>
                        <td>Class</td>
                        <td>{{ $fee->student->schoolClass->class_name }}</td>
                    </tr>
                    @if($fee->student->section)
                    <tr>
                        <td>Section</td>
                        <td>{{ $fee->student->section->section_name }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            <!-- Fee Information -->
            <div class="info-section">
                <div class="section-title">Fee Details</div>
                <table class="info-table">
                    <tr>
                        <td>Fee Description</td>
                        <td>{{ $fee->student->schoolClass->class_name }} Tuition Fee</td>
                    </tr>
                    <tr>
                        <td>Fee Amount</td>
                        <td>{{ $fee->formatted_amount }}</td>
                    </tr>
                    <tr>
                        <td>Payment Status</td>
                        <td>{{ ucfirst($fee->status) }}</td>
                    </tr>
                    <tr>
                        <td>Fee Date</td>
                        <td>{{ $fee->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @if($fee->status === 'paid')
                    <tr>
                        <td>Payment Date</td>
                        <td>{{ $fee->updated_at->format('d/m/Y') }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            <!-- Amount Section -->
            <div class="amount-section">
                <div class="amount-label">Amount Paid</div>
                <div class="amount-value">{{ $fee->formatted_amount }}</div>
            </div>

            <!-- Payment Status -->
            <div class="status-section">
                <div class="status-badge status-{{ $fee->status }}">
                    @if($fee->status === 'paid')
                        ✓ PAID
                    @elseif($fee->status === 'pending')
                        ⏳ PENDING
                    @elseif($fee->status === 'overdue')
                        ⚠️ OVERDUE
                    @endif
                </div>
            </div>
        </div>

        <hr class="divider">

        <!-- Footer -->
        <div class="footer">
            <p>This is a computer generated receipt and does not require signature.</p>
            <p><strong>{{ $school_info['name'] }}</strong></p>
            <p>{{ $school_info['address'] }} • Phone: {{ $school_info['phone'] }}</p>
            <p class="generated-info">
                Generated on: {{ $fee->created_at->format('d/m/Y H:i:s') }}
                | Payment Status: {{ ucfirst($fee->status) }}
            </p>
        </div>
    </div>
</body>
</html>