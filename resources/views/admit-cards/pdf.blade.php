<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admit Card - {{ $admit_card->student->name }}</title>
    <style>
        /* Global Styles */
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
            background: #2c3e50;
            color: white;
            padding: 15px;
            text-align: center;
            border-bottom: 3px solid #e74c3c;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 11px;
            opacity: 0.9;
        }

        .school-logo {
            max-height: 60px;
            max-width: 80px;
            margin-bottom: 10px;
        }

        /* Main Content */
        .content {
            padding: 20px;
            display: flex;
            gap: 20px;
        }

        .left-section {
            flex: 2;
        }

        .right-section {
            flex: 1;
            text-align: center;
        }

        /* Student Photo */
        .student-photo {
            width: 120px;
            height: 140px;
            border: 2px solid #ccc;
            margin: 0 auto 15px;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .student-photo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            font-size: 10px;
            color: #666;
            text-align: center;
        }

        /* Table Styles */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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

        /* Seat Number */
        .seat-number {
            background: #e74c3c;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .seat-number-label {
            font-size: 11px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .seat-number-value {
            font-size: 20px;
        }

        /* Exam Information */
        .exam-section {
            margin-bottom: 20px;
        }

        .exam-section h3 {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e74c3c;
        }

        .subjects-list {
            list-style: none;
            padding: 0;
        }

        .subjects-list li {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }

        /* Important Notes */
        .important-notes {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .important-notes h4 {
            color: #856404;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .important-notes ul {
            list-style: disc;
            margin-left: 20px;
            font-size: 11px;
        }

        .important-notes li {
            margin-bottom: 3px;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
        }

        .footer p {
            margin-bottom: 5px;
        }

        .generated-info {
            font-size: 9px;
            color: #888;
        }

        /* Border */
        .card-border {
            border: 2px solid #2c3e50;
            margin: 10px;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 72px;
            color: rgba(52, 73, 94, 0.1);
            font-weight: bold;
            z-index: -1;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="card-border">
        <div class="watermark">{{ $school_info['name'] }}</div>

        <!-- Header -->
        <div class="header">
            @if(file_exists($school_info['logo']))
                <img src="{{ $school_info['logo'] }}" alt="School Logo" class="school-logo">
            @endif
            <h1>{{ $school_info['name'] }}</h1>
            <p>{{ $school_info['address'] }} | {{ $school_info['phone'] }}</p>
            <h2 style="font-size: 14px; margin-top: 8px;">ADMIT CARD</h2>
        </div>

        <!-- Main Content -->
        <div class="content">
            <div class="left-section">
                <!-- Student Information -->
                <table class="info-table">
                    <tr>
                        <td>Student Name</td>
                        <td>{{ $admit_card->student->name }}</td>
                    </tr>
                    <tr>
                        <td>Roll Number</td>
                        <td>{{ $admit_card->student->roll_no }}</td>
                    </tr>
                    <tr>
                        <td>Class</td>
                        <td>{{ $admit_card->student->schoolClass->class_name }}</td>
                    </tr>
                    <tr>
                        <td>Section</td>
                        <td>{{ $admit_card->student->section ? $admit_card->student->section->section_name : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Date of Birth</td>
                        <td>{{ $admit_card->student->dob ? $admit_card->student->dob->format('d/m/Y') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Exam Name</td>
                        <td>{{ $admit_card->exam->exam_name }}</td>
                    </tr>
                    <tr>
                        <td>Exam Type</td>
                        <td>{{ $admit_card->exam->exam_type_text }}</td>
                    </tr>
                    <tr>
                        <td>Exam Date</td>
                        <td>{{ $admit_card->exam->start_date->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td>Exam Time</td>
                        <td>{{ $admit_card->exam->start_date->format('h:i A') }} - {{ $admit_card->exam->end_date->format('h:i A') }}</td>
                    </tr>
                </table>

                <!-- Exam Subjects -->
                <div class="exam-section">
                    <h3>Subjects</h3>
                    <ul class="subjects-list">
                        @foreach($exam_subjects as $subject)
                            <li>{{ $subject->subject_name }} ({{ $admit_card->student->schoolClass->class_name }})</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="right-section">
                <!-- Student Photo -->
                <div class="student-photo">
                    @if($admit_card->student->photo && file_exists(public_path($admit_card->student->photo)))
                        <img src="{{ public_path($admit_card->student->photo) }}" alt="Student Photo">
                    @else
                        <div class="photo-placeholder">
                            PHOTO<br>{{ $admit_card->student->roll_no }}
                        </div>
                    @endif
                </div>

                <!-- Seat Number -->
                <div class="seat-number">
                    <div class="seat-number-label">SEAT NUMBER</div>
                    <div class="seat-number-value">{{ $admit_card->seat_no }}</div>
                </div>

                <!-- Admit Card Number -->
                <div class="seat-number" style="background: #27ae60;">
                    <div class="seat-number-label">ADMIT CARD NO</div>
                    <div class="seat-number-value" style="font-size: 16px;">{{ $admit_card->id }}</div>
                </div>
            </div>
        </div>

        <!-- Important Notes -->
        <div class="important-notes">
            <h4>Important Instructions:</h4>
            <ul>
                <li>Bring this admit card and valid photo ID to the examination hall.</li>
                <li>Arrive at least 30 minutes before the scheduled exam time.</li>
                <li>Mobile phones and electronic devices are strictly prohibited.</li>
                <li>Any attempt at malpractice will result in immediate disqualification.</li>
                <li>Follow all instructions given by the invigilators.</li>
            </ul>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>{{ $school_info['name'] }}</strong></p>
            <p>{{ $school_info['address'] }} • Phone: {{ $school_info['phone'] }}</p>
            <p>Website: {{ $school_info['website'] }}</p>
            <p class="generated-info">
                Generated on: {{ $admit_card->generated_at->format('d/m/Y H:i:s') }}
                | Valid only for: {{ $admit_card->exam->exam_name }}
            </p>
        </div>
    </div>
</body>
</html>