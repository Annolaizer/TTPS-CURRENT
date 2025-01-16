@extends('admin.master_layout.index')

@section('title', 'TTP - Reports & Analytics')

@section('content')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.2/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        .card {
            border: none;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            height: 100%;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0,0,0,0.125);
            padding: 1rem;
        }
        .card-body {
            padding: 1.5rem;
        }
        .btn-success {
            background-color: #198754;
            border-color: #198754;
        }
        .btn-success:hover {
            background-color: #157347;
            border-color: #146c43;
        }
        .table {
            margin-bottom: 0;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
        }
        .table thead th {
            font-weight: 600;
            color: #344767;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
            background-color: #f8f9fa;
        }
        .table tbody td {
            padding: 12px;
            vertical-align: middle;
            color: #495057;
            border-bottom: 1px solid #e9ecef;
            font-weight: 400;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .badge {
            font-size: 11px;
            padding: 5px 8px;
            font-weight: 500;
        }
        .dataTables_info, 
        .dataTables_length {
            font-size: 13px;
            color: #6c757d;
        }
        .dataTables_length select {
            font-size: 13px;
            padding: 4px 24px 4px 8px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            background-color: #fff;
        }
        .dataTables_filter {
            font-size: 13px;
        }
        .dataTables_filter input {
            font-size: 13px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            font-size: 13px;
        }
        canvas {
            max-height: 300px;
            margin-bottom: 1rem;
        }
        .stats-card {
            transition: transform 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .table-responsive {
            margin-top: 1rem;
        }
        /* DataTable Styling */
        .dataTables_wrapper .dataTables_filter {
            float: right;
            margin-bottom: 1rem;
        }
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 0.375rem 0.75rem;
            margin-left: 0.5rem;
        }
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #dee2e6;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            margin: 0 2px;
            border: none !important;
            background: none !important;
            color: #6c757d !important;
            padding: 5px 12px;
            border-radius: 4px;
            font-weight: 500;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #e9ecef !important;
            color: #198754 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #198754 !important;
            color: white !important;
            border: none;
            font-weight: 600;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #157347 !important;
            color: white !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            color: #dee2e6 !important;
            cursor: not-allowed;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.previous,
        .dataTables_wrapper .dataTables_paginate .paginate_button.next {
            padding: 5px 15px;
            margin: 0 5px;
        }
        .dataTables_wrapper .dataTables_info {
            color: #6c757d;
            padding-top: 1rem;
        }
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1.5rem;
        }
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 0.375rem 0.75rem;
            margin-left: 0.5rem;
            width: 250px;
            transition: border-color 0.15s ease-in-out;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #198754;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
        }
    </style>
@endpush

<main class="main-content">
    <div class="container-fluid py-4">
        <!-- Header Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">System Reports & Analytics</h5>
                            <p class="text-muted mb-0">Comprehensive overview of system statistics and metrics</p>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-success" onclick="exportToPDF()">
                                <i class="fas fa-file-pdf me-2"></i>Export PDF
                            </button>
                            <button type="button" class="btn btn-success ms-2" onclick="exportToExcel()">
                                <i class="fas fa-file-excel me-2"></i>Export Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Teacher Statistics -->
            <div class="col-md-6 mb-4">
                <div class="card stats-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-chalkboard-teacher me-2 text-primary"></i>
                                Teacher Statistics
                            </h5>
                            <span class="badge bg-primary">{{ $teacherStats->sum('total') }} Total</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="teacherChart"></canvas>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Education Level</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Active</th>
                                        <th class="text-center">Inactive</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teacherStats as $stat)
                                    <tr>
                                        <td>{{ $stat->education_level }}</td>
                                        <td class="text-center">{{ $stat->total }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ $stat->active }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger">{{ $stat->inactive }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Organization Statistics -->
            <div class="col-md-6 mb-4">
                <div class="card stats-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-building me-2 text-success"></i>
                                Organization Statistics
                            </h5>
                            <span class="badge bg-success">{{ $orgStats->total }} Total</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="orgChart"></canvas>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Total Organizations</th>
                                        <th class="text-center">Active</th>
                                        <th class="text-center">Inactive</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">{{ $orgStats->total }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ $orgStats->active }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger">{{ $orgStats->inactive }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- User Role Distribution -->
            <div class="col-md-6 mb-4">
                <div class="card stats-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-users me-2 text-info"></i>
                                User Role Distribution
                            </h5>
                            <span class="badge bg-info">{{ $userRoleStats->sum('total') }} Users</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="userRoleChart"></canvas>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Role</th>
                                        <th class="text-center">Total Users</th>
                                        <th class="text-center">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($userRoleStats as $stat)
                                    <tr>
                                        <td>{{ ucfirst($stat->role) }}</td>
                                        <td class="text-center">{{ $stat->total }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-info">
                                                {{ round(($stat->total / $userRoleStats->sum('total')) * 100, 1) }}%
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Training Statistics -->
            <div class="col-md-6 mb-4">
                <div class="card stats-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-graduation-cap me-2 text-warning"></i>
                                Training Statistics
                            </h5>
                            <span class="badge bg-warning">{{ $trainingStats->total_enrollments }} Total</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="trainingChart"></canvas>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Total Enrollments</th>
                                        <th class="text-center">Completed</th>
                                        <th class="text-center">In Progress</th>
                                        <th class="text-center">Not Attended</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $trainingStats->total_enrollments }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ $trainingStats->completed }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning">{{ $trainingStats->in_progress }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger">{{ $trainingStats->not_attended }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Gender Distribution -->
            <div class="col-12 mb-4">
                <div class="card stats-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-venus-mars me-2 text-primary"></i>
                                Gender Distribution
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Gender</th>
                                        <th class="text-center">Total Teachers</th>
                                        <th class="text-center">Total Participants</th>
                                        <th class="text-center">Participation Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($genderStats as $stat)
                                    <tr>
                                        <td>{{ ucfirst($stat->gender) }}</td>
                                        <td class="text-center">{{ $stat->total_teachers }}</td>
                                        <td class="text-center">{{ $stat->total_participants }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $stat->total_teachers > 0 ? 'primary' : 'secondary' }}">
                                                {{ $stat->total_teachers > 0 
                                                    ? round(($stat->total_participants / $stat->total_teachers) * 100, 1) . '%'
                                                    : '0%'
                                                }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Training Details -->
            <div class="col-12">
                <div class="card stats-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-2 text-success"></i>
                                Training Details
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover" id="training-details-table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Organization</th>
                                        <th>Description</th>
                                        <th class="text-center">Male</th>
                                        <th class="text-center">Female</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Attended</th>
                                        <th class="text-center">In Progress</th>
                                        <th class="text-center">Not Attended</th>
                                        <th class="text-center">Attendance Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trainingDetails as $training)
                                    <tr>
                                        <td>{{ $training->title }}</td>
                                        <td>{{ $training->organization }}</td>
                                        <td>{{ Str::limit($training->description, 50) }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $training->male_participants }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ $training->female_participants }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">
                                                {{ $training->male_participants + $training->female_participants }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ $training->attended }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning">{{ $training->in_progress }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger">{{ $training->not_attended }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $training->attendance_rate >= 70 ? 'bg-success' : ($training->attendance_rate >= 50 ? 'bg-warning' : 'bg-danger') }}">
                                                {{ $training->attendance_rate }}%
                                            </span>
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
</main>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Teacher Statistics Chart
        new Chart(document.getElementById('teacherChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($teacherStats->pluck('education_level')) !!},
                datasets: [{
                    label: 'Active Teachers',
                    data: {!! json_encode($teacherStats->pluck('active')) !!},
                    backgroundColor: 'rgba(25, 135, 84, 0.5)',
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 1
                }, {
                    label: 'Inactive Teachers',
                    data: {!! json_encode($teacherStats->pluck('inactive')) !!},
                    backgroundColor: 'rgba(220, 53, 69, 0.5)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Organization Statistics Chart
        new Chart(document.getElementById('orgChart'), {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Inactive'],
                datasets: [{
                    data: [{{ $orgStats->active }}, {{ $orgStats->inactive }}],
                    backgroundColor: [
                        'rgba(25, 135, 84, 0.5)',
                        'rgba(220, 53, 69, 0.5)'
                    ],
                    borderColor: [
                        'rgba(25, 135, 84, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // User Role Distribution Chart
        new Chart(document.getElementById('userRoleChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($userRoleStats->pluck('role')->map(function($role) { return ucfirst($role); })) !!},
                datasets: [{
                    data: {!! json_encode($userRoleStats->pluck('total')) !!},
                    backgroundColor: [
                        'rgba(13, 110, 253, 0.5)',
                        'rgba(25, 135, 84, 0.5)',
                        'rgba(255, 193, 7, 0.5)',
                        'rgba(220, 53, 69, 0.5)'
                    ],
                    borderColor: [
                        'rgba(13, 110, 253, 1)',
                        'rgba(25, 135, 84, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Training Status Chart
        new Chart(document.getElementById('trainingChart'), {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Ongoing', 'Upcoming'],
                datasets: [{
                    data: [
                        {{ $trainingStats->completed }},
                        {{ $trainingStats->in_progress }},
                        {{ $trainingStats->not_attended }}
                    ],
                    backgroundColor: [
                        'rgba(25, 135, 84, 0.5)',   // success (green) - Completed
                        'rgba(255, 193, 7, 0.5)',   // warning (yellow) - Ongoing
                        'rgba(13, 110, 253, 0.5)'   // primary (blue) - Upcoming
                    ],
                    borderColor: [
                        'rgba(25, 135, 84, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(13, 110, 253, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    title: {
                        display: true,
                        text: 'Training Status Distribution',
                        padding: {
                            bottom: 30
                        }
                    }
                }
            }
        });

        // Initialize DataTable for training details
        $('#training-details-table').DataTable({
            pageLength: 10,
            order: [[7, 'desc']],
            responsive: true,
            language: {
                search: "",
                searchPlaceholder: "Search...",
                paginate: {
                    first: '<i class="fas fa-angle-double-left"></i>',
                    previous: '<i class="fas fa-angle-left"></i>',
                    next: '<i class="fas fa-angle-right"></i>',
                    last: '<i class="fas fa-angle-double-right"></i>'
                }
            },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip'
        });
    });

    // Export functions
    function exportToPDF() {
        window.location.href = '{{ route("admin.reports.export-pdf") }}';
    }

    function exportToExcel() {
        window.location.href = '{{ route("admin.reports.export-excel") }}';
    }
</script>
@endpush
