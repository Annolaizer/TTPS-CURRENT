<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Training Report - {{ $training->training_code }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            opacity: 0.1;
            z-index: -1;
        }
        .cover-page {
            height: 100vh;
            padding: 50px;
            text-align: center;
            position: relative;
            background: #fff;
        }
        .ministry-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 40px;
            text-align: center;
        }
        .logo-container {
            margin: 40px 0;
            text-align: center;
        }
        .logo {
            width: 150px;
            height: auto;
        }
        .report-title {
            font-size: 24px;
            font-weight: bold;
            margin: 40px 0;
            text-transform: uppercase;
        }
        .training-details {
            margin: 40px 0;
            font-size: 16px;
        }
        .content {
            padding: 50px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #009c95;
            padding-bottom: 20px;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            background-color: #f5f5f5;
            padding: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #009c95;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .stats-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .stat-box {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            width: 30%;
            margin-bottom: 15px;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #009c95;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        
        .stats-table, .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .stats-table th, .stats-table td,
        .data-table th, .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .stats-table th, .data-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        .stats-table tr:nth-child(even),
        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .stats-table tr:hover,
        .data-table tr:hover {
            background-color: #f0f0f0;
        }

        h3 {
            margin-top: 20px;
            margin-bottom: 10px;
            color: #333;
        }
    </style>
</head>
<body>
    <!-- Cover Page -->
    <div class="cover-page">
        <div class="ministry-header">
            THE UNITED REPUBLIC OF TANZANIA<br>
            MINISTRY OF EDUCATION, SCIENCE AND TECHNOLOGY
        </div>
        
        <div class="logo-container">
            <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo">
        </div>

        <div class="report-title">
            TRAINING REPORT
        </div>

        <div class="training-details">
            <p><strong>{{ $training->title }}</strong></p>
            <p>Training Code: {{ $training->training_code }}</p>
            <p>Duration: {{ $training->duration_days }} Days</p>
            <p>{{ $training->start_date->format('d M Y') }} - {{ $training->end_date->format('d M Y') }}</p>
            <p>Venue: {{ $training->venue_name }}</p>
            <p>Region: {{ optional($training->region)->region_name }}</p>
        </div>
    </div>

    <!-- Watermark on all pages -->
    <div class="watermark">
        <img src="{{ public_path('images/logo.png') }}" alt="Watermark" style="width: 400px;">
    </div>

    <!-- Content Pages -->
    <div class="content">
        <div class="section">
            <h2 class="section-title">Training Overview</h2>
            <table>
                <tr>
                    <th>Education Level</th>
                    <td>{{ $training->education_level }}</td>
                </tr>
                <tr>
                    <th>Organization</th>
                    <td>{{ $training->organization->name }}</td>
                </tr>
                <tr>
                    <th>Phase</th>
                    <td>Phase {{ $training->training_phase }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ ucfirst($training->status) }}</td>
                </tr>
                <tr>
                    <th>Duration</th>
                    <td>{{ $training->duration_days }} Days ({{ $training->start_date->format('d M Y') }} - {{ $training->end_date->format('d M Y') }})</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h2 class="section-title">Training Statistics</h2>
            
            <!-- Teachers Statistics Table -->
            <h3>Teacher Statistics</h3>
            <table class="stats-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Total</th>
                        <th>Male</th>
                        <th>Female</th>
                        <th>Attended</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Assigned Teachers</strong></td>
                        <td>{{ $teacherStats['total_assigned'] }}</td>
                        <td>{{ $teacherStats['male_assigned'] }}</td>
                        <td>{{ $teacherStats['female_assigned'] }}</td>
                        <td>{{ $teacherStats['attended'] }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Facilitators Statistics Table -->
            <h3>Facilitator Statistics</h3>
            <table class="stats-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Total</th>
                        <th>Male</th>
                        <th>Female</th>
                        <th>Attended</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>CPD Facilitators</strong></td>
                        <td>{{ $facilitatorStats['total'] }}</td>
                        <td>{{ $facilitatorStats['male'] }}</td>
                        <td>{{ $facilitatorStats['female'] }}</td>
                        <td>{{ $facilitatorStats['attended'] }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Overall Attendance -->
            <h3>Overall Attendance Rate: {{ number_format($attendanceRate, 1) }}%</h3>
        </div>

        <div class="section">
            <h2 class="section-title">Assigned Teachers</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Registration No.</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignedTeachers as $teacher)
                        @php
                            $user = $teacher->user;
                            $attendanceStatus = $teacher->pivot->attendance_status ?? 'Not Attended';
                        @endphp
                        <tr>
                            <td>{{ $user ? $user->name : 'N/A' }}</td>
                            <td>{{ $user ? ucfirst($user->gender) : 'N/A' }}</td>
                            <td>{{ $user ? $user->phone_number : 'N/A' }}</td>
                            <td>{{ $user ? $user->email : 'N/A' }}</td>
                            <td>{{ $teacher->registration_number }}</td>
                            <td>{{ ucfirst($attendanceStatus) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="section">
            <h2 class="section-title">CPD Facilitators</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($facilitators as $facilitator)
                        @php
                            $attendanceStatus = $facilitator->pivot->attendance_status ?? 'Not Attended';
                        @endphp
                        <tr>
                            <td>{{ $facilitator->name }}</td>
                            <td>{{ ucfirst($facilitator->gender) }}</td>
                            <td>{{ $facilitator->phone_number }}</td>
                            <td>{{ $facilitator->email }}</td>
                            <td>{{ ucfirst($attendanceStatus) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p>Report generated on {{ $generatedAt->format('d M Y H:i:s') }}</p>
            <p>Tanzania Teacher Portal System</p>
        </div>
    </div>
</body>
</html>
