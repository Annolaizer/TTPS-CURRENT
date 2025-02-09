<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('asset/images/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <title>TTP - Teacher Trainings</title>
    <style>
        :root {
            --primary-color: #009c95;
            --secondary-color: #cc3344;
            --hover-color: #007c77;
            --hover-dark: #006c68;
            --light-bg: #f8f8f8;
            --navbar-height: 70px;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-decoration: none;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: var(--light-bg);
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background-color: var(--primary-color);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--navbar-height);
            z-index: 1000;
            padding: 10px 0;
            display: flex;
            align-items: center;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
        }
        .navbar-brand:hover {
            color: rgba(255, 255, 255, 0.9);
        }
        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }
        .portal-name {
            font-size: 1.2rem;
            font-weight: bold;
        }
        .user-profile {
            position: relative;
        }
        .profile-trigger {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: white;
            height: 40px;
            width: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .profile-trigger:hover {
            background-color: var(--hover-color);
            border-color: var(--hover-color);
        }
        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            min-width: 250px;
            display: none;
            z-index: 1000;
            margin-top: 10px;
        }
        .profile-dropdown.show {
            display: block;
        }
        .profile-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }
        .profile-header i {
            font-size: 48px;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        .profile-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .profile-email {
            color: #666;
            font-size: 0.9em;
        }
        .profile-menu {
            padding: 10px 0;
        }
        .profile-menu a {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .profile-menu a:hover {
            background-color: var(--light-bg);
            color: var(--primary-color);
        }
        .profile-menu i {
            margin-right: 10px;
            width: 20px;
            color: var(--primary-color);
        }
        .divider {
            height: 1px;
            background-color: #eee;
            margin: 5px 0;
        }
        .main-content {
            padding-top: calc(var(--navbar-height) + 20px);
            padding-left: 20px;
            padding-right: 20px;
            flex: 1;
        }
        .card-statistics {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            margin-bottom: 20px;
        }
        .card-statistics:hover {
            transform: translateY(-5px);
        }
        .icon-container {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        .icon-container i {
            color: white;
            font-size: 24px;
        }
        .stat-content {
            flex: 1;
        }
        .stat-title {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
        .stat-value {
            margin: 5px 0 0;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .table-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .table-card .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 15px 20px;
        }
        .table-card .card-body {
            padding: 20px;
        }
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 15px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            color: white !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--hover-color) !important;
            border-color: var(--hover-color) !important;
            color: white !important;
        }
        .btn-info {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        .btn-info:hover {
            background-color: var(--hover-color);
            border-color: var(--hover-color);
            color: white;
        }
        .badge {
            padding: 6px 12px;
            font-weight: 500;
            border-radius: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ route('teacher.dashboard') }}" class="navbar-brand">
                <img src="{{ asset('asset/images/logo.png') }}" alt="Logo" height="40">
                <span class="portal-name">Tanzania Teacher Portal</span>
            </a>
            <div class="user-profile">
                <button class="profile-trigger" onclick="toggleDropdown()">
                    <i class="fas fa-user"></i>
                </button>
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-header">
                        <i class="fas fa-user"></i>
                        <div class="profile-name">{{ Auth::user()->name }}</div>
                        <div class="profile-email">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="profile-menu">
                        <a href="{{ route('teacher.dashboard') }}">
                            <i class="fas fa-home"></i>Home
                        </a>
                        <a href="{{ route('teacher.training.index') }}">
                            <i class="fas fa-certificate"></i>Training
                        </a>
                        <a href="{{ route('teacher.settings') }}">
                            <i class="fas fa-cog"></i>Account settings
                        </a>
                        <div class="divider"></div>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>Logout
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-container bg-primary">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="stat-content">
                                <h5 class="stat-title">Total Sessions</h5>
                                <p class="stat-value">{{ $stats['total'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-container bg-success">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="stat-content">
                                <h5 class="stat-title">Completed</h5>
                                <p class="stat-value">{{ $stats['completed'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="col-lg-3 col-md-6">
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-container bg-info">
                                <i class="fas fa-chalkboard"></i>
                            </div>
                            <div class="stat-content">
                                <h5 class="stat-title">Ongoing</h5>
                                <p class="stat-value">{{ $stats['ongoing'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="col-lg-3 col-md-6">
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-container bg-warning">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="stat-content">
                                <h5 class="stat-title">Upcoming</h5>
                                <p class="stat-value">{{ $stats['upcoming'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card table-card">
                    <div class="card-header">
                        <h5 class="mb-0">Teacher Training Sessions</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="trainingsTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Training Title</th>
                                        <th>Organization</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trainings as $training)
                                    <tr>
                                        <td>{{ $training->title }}</td>
                                        <td>{{ $training->organization_name }}</td>
                                        <td>{{ $training->start_date }}</td>
                                        <td>{{ $training->end_date }}</td>
                                        <td>
                                            @php
                                                // Get the current teacher's participation status
                                                $participationStatus = $training->getTeacherParticipationStatus();
                                                
                                                $statusClass = match($participationStatus) {
                                                    'Invitation Pending' => 'bg-warning text-dark',
                                                    'Accepted' => 'bg-info text-white',
                                                    'Attended' => 'bg-success text-white',
                                                    'Rejected' => 'bg-danger text-white',
                                                    'Not Invited' => 'bg-secondary text-white',
                                                    default => 'bg-light text-dark'
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }}">
                                                {{ $participationStatus }}
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm" 
                                                    onclick="viewTrainingDetails('{{ $training->training_id }}')"
                                                    title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Comprehensive Training Modal -->
    @foreach($trainings as $training)
    <div class="modal fade" id="trainingModal{{ $training->training_id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--primary-color); color: white;">
                    <h5 class="modal-title">Training Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Training Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 style="color: var(--primary-color);">Basic Information</h6>
                            <p><strong>Title:</strong> {{ $training->title ?? 'Not specified' }}</p>
                            <p><strong>Organization:</strong> {{ $training->organization_name ?? 'Not specified' }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge" style="background-color: {{ 
                                    $training->status == 'completed' ? 'var(--success-color)' : 
                                    ($training->status == 'verified' ? 'var(--primary-color)' : 
                                    ($training->status == 'rejected' ? 'var(--danger-color)' : 
                                    ($training->status == 'pending' ? '#ffc107' : '#6c757d'))) 
                                }}; color: {{ 
                                    $training->status == 'pending' ? '#000' : '#fff' 
                                }}">
                                    {{ ucfirst($training->status ?? 'pending') }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 style="color: var(--primary-color);">Schedule</h6>
                            <p><strong>Start Date:</strong> {{ $training->start_date ? \Carbon\Carbon::parse($training->start_date)->format('M d, Y') : 'Not specified' }}</p>
                            <p><strong>End Date:</strong> {{ $training->end_date ? \Carbon\Carbon::parse($training->end_date)->format('M d, Y') : 'Not specified' }}</p>
                            <p><strong>Duration:</strong> 
                                @if($training->start_date && $training->end_date)
                                    {{ \Carbon\Carbon::parse($training->start_date)->diffInDays(\Carbon\Carbon::parse($training->end_date)) + 1 }} days
                                @else
                                    Not specified
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Training Description -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 style="color: var(--primary-color);">Description</h6>
                            <p>{{ $training->description ?? 'No description available' }}</p>
                        </div>
                    </div>

                    <!-- Training Location -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 style="color: var(--primary-color);">Location Details</h6>
                            <p><strong>Venue:</strong> {{ $training->location ?? 'Not specified' }}</p>
                            @if($training->location_details)
                                <p>{{ $training->location_details }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Training Actions -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 style="color: var(--primary-color);">Actions</h6>
                            <div class="d-flex gap-2">
                                @if($training->status == 'verified' && !$training->attendance_confirmed)
                                    <button type="button" class="btn btn-success" 
                                            onclick="confirmAttendance('{{ $training->training_id }}')">
                                        <i class="fas fa-check"></i> Confirm Attendance
                                    </button>
                                @endif

                                @if($training->status == 'completed' || ($training->end_date && \Carbon\Carbon::parse($training->end_date)->addDay()->isPast()))
                                    <button type="button" class="btn btn-primary" 
                                            onclick="toggleReportForm('{{ $training->training_id }}')">
                                        <i class="fas fa-file-upload"></i> Upload Report
                                    </button>
                                @endif

                                @if($training->status == 'pending')
                                    <button type="button" class="btn btn-success" 
                                            onclick="acceptTraining('{{ $training->training_id }}')">
                                        <i class="fas fa-check"></i> Accept
                                    </button>
                                    <button type="button" class="btn btn-danger" 
                                            onclick="showRejectionForm('{{ $training->training_id }}')">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Report Upload Form (Initially Hidden) -->
                    <div class="row mb-4" id="reportForm{{ $training->training_id }}" style="display: none;">
                        <div class="col-12">
                            <h6 style="color: var(--primary-color);">Upload Training Report</h6>
                            <form id="trainingReportForm{{ $training->training_id }}" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="report{{ $training->training_id }}" class="form-label">Upload Training Report</label>
                                    <input type="file" 
                                           class="form-control" 
                                           id="report{{ $training->training_id }}" 
                                           name="report_file" 
                                           accept=".pdf,.doc,.docx"
                                           required>
                                </div>
                                <div class="mb-3">
                                    <label for="report_remarks{{ $training->training_id }}" class="form-label">Additional Remarks (Optional)</label>
                                    <textarea 
                                        class="form-control" 
                                        id="report_remarks{{ $training->training_id }}" 
                                        name="report_remarks" 
                                        rows="3" 
                                        placeholder="Enter any additional remarks about the training"
                                        maxlength="500"></textarea>
                                </div>
                                <div class="text-end">
                                    <button type="button" 
                                            class="btn btn-primary" 
                                            onclick="submitReport('{{ $training->training_id }}')">
                                        Submit Report
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Rejection Form -->
                    <div class="row mb-4" id="rejectionForm{{ $training->training_id }}" style="display: none;">
                        <div class="col-12">
                            <div class="card border-danger">
                                <div class="card-body">
                                    <h6 style="color: var(--danger-color);">Rejection Reason</h6>
                                    <form id="trainingRejectionForm{{ $training->training_id }}">
                                        @csrf
                                        <div class="mb-3">
                                            <textarea class="form-control" name="rejection_reason" rows="3" 
                                                    placeholder="Please provide a reason for rejecting this training..." required></textarea>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-danger" onclick="rejectTraining('{{ $training->training_id }}')">
                                                Confirm Rejection
                                            </button>
                                            <button type="button" class="btn btn-secondary" 
                                                    onclick="hideRejectionForm('{{ $training->training_id }}')">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Training History -->
                    @if($training->status == 'completed')
                    <div class="row">
                        <div class="col-12">
                            <h6 style="color: var(--primary-color);">Training History</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-clock text-muted"></i> Started: {{ $training->start_date ? \Carbon\Carbon::parse($training->start_date)->format('M d, Y') : 'Not specified' }}</li>
                                @if($training->attendance_confirmed)
                                    <li><i class="fas fa-check text-success"></i> Attendance Confirmed: {{ \Carbon\Carbon::parse($training->attendance_confirmed_at)->format('M d, Y') }}</li>
                                @endif
                                @if($training->report_submitted)
                                    <li><i class="fas fa-file-alt" style="color: var(--primary-color);"></i> Report Submitted: {{ \Carbon\Carbon::parse($training->report_submitted_at)->format('M d, Y') }}</li>
                                @endif
                                <li><i class="fas fa-flag-checkered text-success"></i> Completed: {{ $training->end_date ? \Carbon\Carbon::parse($training->end_date)->format('M d, Y') : 'Not specified' }}</li>
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Add CSRF token to all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        function toggleDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');

            // Close dropdown when clicking outside
            document.addEventListener('click', function closeDropdown(e) {
                if (!e.target.closest('.user-profile')) {
                    dropdown.classList.remove('show');
                    document.removeEventListener('click', closeDropdown);
                }
            });
        }

        $(document).ready(function() {
            $('#trainingsTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[3, 'desc']], // Sort by date column descending
                columnDefs: [
                    { 
                        targets: -1,
                        orderable: false,
                        searchable: false,
                        width: '80px'
                    }
                ],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });
        });

        function viewTrainingDetails(id) {
            if (!id) return;
            $('#trainingModal' + id).modal('show');
        }

        function toggleReportForm(id) {
            $('#reportForm' + id).slideToggle();
        }

        function showRejectionForm(id) {
            // Hide any other forms that might be open
            $('.rejection-form').hide();
            $('#reportForm' + id).hide();
            
            // Show the rejection form
            $('#rejectionForm' + id).slideDown();
            
            // Scroll to the form
            $('#rejectionForm' + id)[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function hideRejectionForm(id) {
            $('#rejectionForm' + id).slideUp();
        }

        function submitReport(id) {
            const formData = new FormData($('#trainingReportForm' + id)[0]);
            const reportRemarks = formData.get('report_remarks');
            
            Swal.fire({
                title: 'Submit Report',
                text: 'Are you sure you want to submit this training report?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: 'var(--primary-color)',
                cancelButtonColor: 'var(--secondary-color)',
                confirmButtonText: 'Yes, submit report'
            }).then((result) => {
                if (result.isConfirmed) {
                    const reportUrl = `{{ route('teacher.training.upload-report', ['id' => ':id']) }}`.replace(':id', id);
                    console.log('Report Upload URL:', reportUrl);
                    
                    // Validate file
                    const reportFile = formData.get('report_file');
                    if (!reportFile || reportFile.size === 0) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Please select a report file to upload.',
                            icon: 'error',
                            confirmButtonColor: 'var(--primary-color)'
                        });
                        return;
                    }

                    // Validate file type
                    const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                    if (!allowedTypes.includes(reportFile.type)) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Please upload a PDF or Word document.',
                            icon: 'error',
                            confirmButtonColor: 'var(--primary-color)'
                        });
                        return;
                    }

                    // Validate file size (10MB)
                    if (reportFile.size > 10 * 1024 * 1024) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'File size should not exceed 10MB.',
                            icon: 'error',
                            confirmButtonColor: 'var(--primary-color)'
                        });
                        return;
                    }
                    
                    $.ajax({
                        url: reportUrl,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            console.log('Report Upload Success:', response);
                            Swal.fire({
                                title: 'Success!',
                                text: 'Report uploaded successfully!',
                                icon: 'success',
                                confirmButtonColor: 'var(--primary-color)'
                            }).then(() => {
                                $('#trainingModal' + id).modal('hide');
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            console.error('Report Upload Error:', xhr);
                            let errorMessage = 'Error uploading report. Please try again.';
                            
                            // Try to parse more detailed error message
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.message) {
                                    errorMessage = response.message;
                                }
                            } catch(e) {}

                            Swal.fire({
                                title: 'Error!',
                                text: errorMessage,
                                icon: 'error',
                                confirmButtonColor: 'var(--primary-color)'
                            });
                        }
                    });
                }
            });
        }

        function acceptTraining(id) {
            if (!id) return;
            Swal.fire({
                title: 'Accept Training',
                text: 'Are you sure you want to accept this training?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: 'var(--primary-color)',
                cancelButtonColor: 'var(--secondary-color)',
                confirmButtonText: 'Yes, accept training'
            }).then((result) => {
                if (result.isConfirmed) {
                    const acceptUrl = `{{ route('teacher.training.accept', ['id' => ':id']) }}`.replace(':id', id);
                    console.log('Accept Training URL:', acceptUrl);
                    
                    $.ajax({
                        url: acceptUrl,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log('Accept Training Success:', response);
                            Swal.fire({
                                title: 'Success!',
                                text: 'Training accepted successfully!',
                                icon: 'success',
                                confirmButtonColor: 'var(--primary-color)'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            console.error('Accept Training Error:', xhr.responseText);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Error accepting training. Please try again.',
                                icon: 'error',
                                confirmButtonColor: 'var(--primary-color)'
                            });
                        }
                    });
                }
            });
        }

        function confirmAttendance(id) {
            if (!id) return;
            Swal.fire({
                title: 'Confirm Attendance',
                text: 'Are you sure you want to confirm your attendance for this training?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: 'var(--primary-color)',
                cancelButtonColor: 'var(--secondary-color)',
                confirmButtonText: 'Yes, confirm attendance'
            }).then((result) => {
                if (result.isConfirmed) {
                    const confirmUrl = `{{ route('teacher.training.confirm-attendance', ['id' => ':id']) }}`.replace(':id', id);
                    console.log('Confirm Attendance URL:', confirmUrl);
                    
                    $.ajax({
                        url: confirmUrl,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log('Confirm Attendance Success:', response);
                            Swal.fire({
                                title: 'Success!',
                                text: 'Attendance confirmed successfully!',
                                icon: 'success',
                                confirmButtonColor: 'var(--primary-color)'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            console.error('Confirm Attendance Error:', xhr.responseText);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Error confirming attendance. Please try again.',
                                icon: 'error',
                                confirmButtonColor: 'var(--primary-color)'
                            });
                        }
                    });
                }
            });
        }

        function rejectTraining(id) {
            const reason = $('#trainingRejectionForm' + id + ' textarea[name="rejection_reason"]').val();
            if (!reason) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please provide a reason for rejection.',
                    icon: 'error',
                    confirmButtonColor: 'var(--primary-color)'
                });
                return;
            }

            Swal.fire({
                title: 'Reject Training',
                text: 'Are you sure you want to reject this training?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger-color)',
                cancelButtonColor: 'var(--secondary-color)',
                confirmButtonText: 'Yes, reject training'
            }).then((result) => {
                if (result.isConfirmed) {
                    const rejectUrl = `{{ route('teacher.training.reject', ['id' => ':id']) }}`.replace(':id', id);
                    console.log('Reject Training URL:', rejectUrl);
                    
                    $.ajax({
                        url: rejectUrl,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            reason: reason
                        },
                        success: function(response) {
                            console.log('Reject Training Success:', response);
                            Swal.fire({
                                title: 'Success!',
                                text: 'Training rejected successfully!',
                                icon: 'success',
                                confirmButtonColor: 'var(--primary-color)'
                            }).then(() => {
                                $('#trainingModal' + id).modal('hide');
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            console.error('Reject Training Error:', xhr.responseText);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Error rejecting training. Please try again.',
                                icon: 'error',
                                confirmButtonColor: 'var(--primary-color)'
                            });
                        }
                    });
                }
            });
        }
    </script>
</body>
</html>