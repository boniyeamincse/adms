<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seating Plan - {{ $exam->exam_name }}</title>
    <style>
        @page {
            margin: 1cm;
            size: A4 landscape;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
            font-size: 12px;
        }

        body {
            color: #333;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
        }

        .header p {
            font-size: 12px;
            margin: 5px 0;
            opacity: 0.8;
        }

        .exam-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #e74c3c;
        }

        .exam-info div {
            flex: 1;
            text-align: center;
        }

        .exam-info div:nth-child(2) {
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
        }

        .hall-container {
            page-break-inside: avoid;
            margin-bottom: 30px;
        }

        .hall-header {
            background: #2c3e50;
            color: white;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .seating-layout {
            display: grid;
            gap: 3px;
            margin: 20px 0;
            justify-content: center;
        }

        .seat-row {
            display: flex;
            gap: 3px;
        }

        .seat {
            width: 45px;
            height: 35px;
            border: 1px solid #ccc;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            text-align: center;
            background: #fff;
        }

        .seat.empty {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #6c757d;
        }

        .seat.occupied {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: 1px solid #1e7e34;
            color: white;
        }

        .seat-class-1 { background: linear-gradient(135deg, #f8f9fa, #e9ecef); border: 2px solid #6c757d; }
        .seat-class-2 { background: linear-gradient(135deg, #cce5ff, #99d6ff); border: 2px solid #007bff; }
        .seat-class-3 { background: linear-gradient(135deg, #d4edda, #a8ddb5); border: 2px solid #28a745; }
        .seat-class-4 { background: linear-gradient(135deg, #fff3cd, #ffeaa7); border: 2px solid #ffc107; }
        .seat-class-5 { background: linear-gradient(135deg, #f8d7da, #fab1a0); border: 2px solid #dc3545; }

        .seat-info {
            font-size: 7px;
            font-weight: bold;
        }

        .seat-roll {
            font-size: 6px;
            opacity: 0.8;
        }

        .legend {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            font-size: 10px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .legend-color {
            width: 15px;
            height: 15px;
            border: 1px solid #ddd;
        }

        .student-list {
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .student-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .student-table th,
        .student-table td {
            padding: 5px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .student-table th {
            background: #f8f9fa;
            font-weight: bold;
            font-size: 10px;
        }

        .student-table td {
            font-size: 9px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 9px;
            color: #666;
        }

        .hall-statistics {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            font-size: 10px;
        }

        .hall-statistics div {
            text-align: center;
        }

        .hall-statistics .stat-value {
            font-weight: bold;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        @if($school_info['logo'] && file_exists($school_info['logo']))
            <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents($school_info['logo'])) }}" alt="School Logo" style="max-height: 50px; margin-bottom: 10px;">
        @endif
        <h1>{{ $school_info['name'] }}</h1>
        <p>{{ $school_info['address'] }} | {{ $school_info['phone'] }}</p>
        <h2>EXAMINATION SEATING PLAN</h2>
    </div>

    <!-- Exam Information -->
    <div class="exam-info">
        <div>
            <strong>Exam Name:</strong><br>
            {{ $exam->exam_name }}
        </div>
        <div>
            <strong>Exam Date & Time:</strong><br>
            {{ $exam->start_date->format('d/m/Y') }}<br>
            {{ $exam->start_date->format('h:i A') }} - {{ $exam->end_date->format('h:i A') }}
        </div>
        <div>
            <strong>Generated On:</strong><br>
            {{ $generated_at->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <!-- Legend for Color Coding -->
    <div class="legend">
        <div class="legend-item">
            <div class="legend-color seat-class-1"></div>
            <span>Classwise Distribution</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background: linear-gradient(135deg, #fff3cd, #ffeaa7);"></div>
            <span>Section Separation</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background: linear-gradient(135deg, #e3f2fd, #bbdefb);"></div>
            <span>Handicap Accessible</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background: linear-gradient(135deg, #fce4ec, #f8bbd9);"></div>
            <span>Emergency Exit</span>
        </div>
    </div>

    @foreach($hall_seatings as $hallName => $layout)
        <div class="hall-container">
            <div class="hall-header">{{ $hallName }}</div>

            <!-- Hall Statistics -->
            <div class="hall-statistics">
                <?php
                    $occupiedSeats = 0;
                    $totalSeats = count($layout) * (is_array($layout) && count($layout) > 0 ? count(array_slice($layout, 0, 1)[array_key_first($layout)]) : 0);

                    foreach ($layout as $row) {
                        foreach ($row as $seat) {
                            if (isset($seat['student_name'])) {
                                $occupiedSeats++;
                            }
                        }
                    }
                ?>
                <div>
                    <div>Total Seats:</div>
                    <div class="stat-value">{{ $totalSeats }}</div>
                </div>
                <div>
                    <div>Occupied:</div>
                    <div class="stat-value">{{ $occupiedSeats }}</div>
                </div>
                <div>
                    <div>Utilization:</div>
                    <div class="stat-value">{{ $totalSeats > 0 ? round(($occupiedSeats / $totalSeats) * 100, 1) : 0 }}%</div>
                </div>
            </div>

            <!-- Seating Layout -->
            <div class="seating-layout">
                @for($row = 1; $row <= count($layout); $row++)
                    <div class="seat-row">
                        @for($col = 1; $col <= count($layout[$row]); $col++)
                            @php $seatKey = 'seat-' . $row . '-' . $col; @endphp
                            @if(isset($layout[$row][$col]) && is_array($layout[$row][$col]))
                                @php
                                    $student = $layout[$row][$col];
                                    $classColor = 'seat-class-' . ((ord(strtolower(substr($student['class_name'] ?? 'Unknown', 0, 1))) - ord('a')) % 5 + 1);
                                @endphp
                                <div class="seat occupied {{ $classColor }}">
                                    <div class="seat-info">{{ substr($student['student_name'], 0, 8) }}</div>
                                    <div class="seat-roll">{{ $student['roll_no'] }}</div>
                                </div>
                            @else
                                <div class="seat empty"></div>
                            @endif
                        @endfor
                    </div>
                @endfor
            </div>

            <!-- Student List for this Hall -->
            <div class="student-list">
                <h4 style="font-size: 12px; margin-bottom: 10px; color: #2c3e50;">{{ $hallName }} - Student List</h4>
                <table class="student-table">
                    <thead>
                        <tr>
                            <th>Seat No</th>
                            <th>Name</th>
                            <th>Roll No</th>
                            <th>Class</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($row = 1; $row <= count($layout); $row++)
                            @for($col = 1; $col <= count($layout[$row]); $col++)
                                @if(isset($layout[$row][$col]) && is_array($layout[$row][$col]))
                                    @php $student = $layout[$row][$col] @endphp
                                    <tr>
                                        <td>{{ $this->generateSeatNo($row, $col) }}</td>
                                        <td>{{ $student['student_name'] }}</td>
                                        <td>{{ $student['roll_no'] }}</td>
                                        <td>{{ $student['class_name'] }}</td>
                                    </tr>
                                @endif
                            @endfor
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    @endfor

    <!-- Important Notes -->
    <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-left: 4px solid #e74c3c;">
        <h4 style="color: #e74c3c; font-size: 12px; margin-bottom: 8px;">Important Exam Instructions:</h4>
        <ul style="font-size: 10px; margin-left: 20px;">
            <li>All students must be seated according to their assigned seat numbers</li>
            <li>Students should arrive 30 minutes before exam commencement</li>
            <li>Students must bring their admit cards and valid ID proof</li>
            <li>Only the hall supervisor and authorized exam personnel may enter the hall</li>
            <li>No mobilization of seats is allowed once the exam begins</li>
        </ul>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>{{ $school_info['name'] }}</strong> - Examination Management System</p>
        <p>Generated automatically by the Seating Plan Generator • Report ID: {{ $exam->id }}-{{ $generated_at->format('YmdHis') }}</p>
    </div>
</body>
</html>