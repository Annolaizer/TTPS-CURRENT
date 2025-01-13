@extends('admin.master_layout.index')

@section('title', 'TTP - Teachers')

@section('content')
@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .filter-input {
            border-radius: 4px;
            border: 1px solid #ced4da;
        }
        .status-badge {
            width: 70px;
            text-align: center;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.7em;
        }
        .card {
            border: none;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .btn-xs {
            width: 24px;
            height: 24px;
            padding: 2px;
            line-height: 20px;
            text-align: center;
            margin: 0 1px;
        }
        .btn-xs i {
            font-size: 12px;
            line-height: 20px;
            margin: 0;
            padding: 0;
            color: white;
        }
        /* Loader Styles */
        .dataTables_processing {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            margin-left: -50%;
            text-align: center;
            background: rgba(255, 255, 255, 0.9);
            padding: 15px;
            z-index: 1;
        }
        .loader {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-radius: 50%;
            border-top: 4px solid #3498db;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .loading-text {
            display: block;
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }
        /* Table loading overlay */
        .table-loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 1000;
            display: none;
            justify-content: center;
            align-items: center;
        }
        .table-responsive {
            position: relative;
        }
        /* DataTables Custom Styling */
        .dataTables_wrapper {
            padding: 1rem 0;
        }
        .dataTables_length {
            float: right;
            margin-right: 1rem;
        }
        .dataTables_length select {
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 0.375rem 2rem 0.375rem 0.75rem;
            font-size: 0.875rem;
            background-color: #fff;
        }
        .dataTables_filter {
            margin-bottom: 1rem;
        }
        .dataTables_filter input {
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            min-width: 200px;
            background-color: #fff;
        }
        .dataTables_info {
            padding-top: 1rem;
            font-size: 0.875rem;
            color: #6c757d;
        }
        .dataTables_paginate {
            padding-top: 1rem;
        }
        /* Export Buttons Styling */
        .dt-buttons {
            margin-bottom: 1rem;
            gap: 0.5rem;
            display: inline-flex;
        }
        .dt-button {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0.5rem 1rem !important;
            font-size: 0.875rem !important;
            border-radius: 4px !important;
            border: none !important;
            margin: 0 !important;
            transition: all 0.2s !important;
            position: relative !important;
        }
        .dt-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.12) !important;
        }
        .dt-button:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0,0,0,0.08) !important;
        }
        /* Table Styling */
        #teachers-table {
            width: 100% !important;
            font-size: 0.75rem !important;
        }
        #teachers-table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            padding: 0.5rem;
            font-size: 0.75rem;
        }
        #teachers-table tbody td {
            padding: 0.4rem 0.5rem;
            vertical-align: middle;
            font-size: 0.75rem;
        }
        .btn-group .btn {
            padding: 0.15rem 0.3rem;
            font-size: 0.7rem;
        }
    </style>
@endpush

<!-- Main Content -->
<main class="main-content">
    <div class="container-fluid">
        <!-- Filters Section -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="row g-3 filters-section">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control filter-input" id="search-filter" placeholder="Search by Name or Registration">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select filter-input" id="education-filter">
                            <option value="">All Education Levels</option>
                            <option value="Certificate">Certificate</option>
                            <option value="Diploma">Diploma</option>
                            <option value="Bachelor">Bachelor's Degree</option>
                            <option value="Masters">Master's Degree</option>
                            <option value="PhD">PhD</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select filter-input" id="subject-filter">
                            <option value="">All Subjects</option>
                            <option value="Mathematics">Mathematics</option>
                            <option value="English">English</option>
                            <option value="Kiswahili">Kiswahili</option>
                            <option value="Science">Science</option>
                            <option value="Social Studies">Social Studies</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select filter-input" id="status-filter">
                            <option value="">All Status</option>
                            <option value="verified">Verified</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teachers Table Section -->
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="card-tools">
                    <button onclick="verifyAllTeachers()" class="btn btn-success">
                        <i class="fas fa-check-double"></i> Verify All Completed
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div class="table-loading-overlay">
                        <div class="text-center">
                            <div class="loader"></div>
                            <span class="loading-text">Loading data...</span>
                        </div>
                    </div>
                    <table id="teachers-table" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Registration No.</th>
                                <th>Name</th>
                                <th>Subject</th>
                                <th>School</th>
                                <th>Experience</th>
                                <th>Education</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teachers as $teacher)
                                <tr>
                                    <td>{{ $teacher->registration_number }}</td>
                                    <td>{{ $teacher->user->name }}</td>
                                    <td>{{ $teacher->teaching_subject }}</td>
                                    <td>{{ $teacher->current_school }}</td>
                                    <td>{{ $teacher->years_of_experience }} years</td>
                                    <td>{{ $teacher->education_level }}</td>
                                    <td>
                                        <span class="badge bg-{{ $teacher->status_badge }} status-badge">
                                            {{ $teacher->status_label }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($teacher->status !== 'verified')
                                            <button onclick="verifyTeacher({{ $teacher->id }})" class="btn btn-success btn-flat btn-xs">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        <a href="{{ route('admin.teachers.edit', ['user_id' => $teacher->user_id]) }}" class="btn btn-warning btn-flat btn-xs">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.teachers.show', ['user_id' => $teacher->user_id]) }}" class="btn btn-info btn-flat btn-xs">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No teachers found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
    <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="//cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Initialize DataTable
        let table = $('#teachers-table').DataTable({
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            order: [[0, "desc"]],
            processing: true,
            language: {
                processing: '<div class="loader"></div><span class="loading-text">Loading data...</span>'
            },
            drawCallback: function(settings) {
                $('.table-loading-overlay').hide();
            },
            preDrawCallback: function(settings) {
                $('.table-loading-overlay').css('display', 'flex');
            },
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

        // Filter handling
        $('#search-filter').on('keyup', function() {
            table.search(this.value).draw();
        });

        $('#education-filter, #subject-filter, #status-filter').on('change', function() {
            let educationVal = $('#education-filter').val();
            let subjectVal = $('#subject-filter').val();
            let statusVal = $('#status-filter').val();

            // Clear all filters first
            table.columns().search('').draw();

            // Apply filters if values exist
            if (educationVal) table.column(5).search(educationVal);
            if (subjectVal) table.column(2).search(subjectVal);
            if (statusVal) table.column(6).search(statusVal);

            table.draw();
        });

        // Status toggle functionality
        $('.toggle-status').on('click', function() {
            let teacherId = $(this).data('teacher-id');
            let currentStatus = $(this).data('current-status');
            let button = $(this);

            // Disable button during processing
            button.prop('disabled', true);

            $.ajax({
                url: `/admin/teachers/${teacherId}/toggle-status`,
                type: 'POST',
                data: {
                    status: currentStatus
                },
                success: function(response) {
                    if (response.success) {
                        let newStatus = currentStatus === 'active' ? 'inactive' : 'active';
                        let newIcon = newStatus === 'active' ? 'check' : 'ban';
                        let newTitle = newStatus === 'active' ? 'Deactivate' : 'Activate';
                        let newButtonClass = newStatus === 'active' ? 'warning' : 'success';

                        // Update button state and appearance
                        button.data('current-status', newStatus)
                              .attr('title', newTitle)
                              .removeClass('btn-outline-success btn-outline-warning')
                              .addClass(`btn-outline-${newButtonClass}`)
                              .find('i')
                              .removeClass('fa-ban fa-check')
                              .addClass(`fa-${newIcon}`);

                        // Update status badge
                        let badgeCell = button.closest('tr').find('.status-badge');
                        badgeCell.removeClass('bg-success bg-danger')
                                .addClass(newStatus === 'active' ? 'bg-success' : 'bg-danger')
                                .text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));

                        // Show success message
                        toastr.options = {
                            closeButton: true,
                            progressBar: true,
                            positionClass: "toast-top-right",
                            timeOut: 3000
                        };
                        toastr.success(response.message || 'Status updated successfully');

                        // Refresh table to ensure consistency
                        table.draw(false);
                    } else {
                        toastr.error(response.message || 'Failed to update status');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred while updating the status';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr.error(errorMessage);
                },
                complete: function() {
                    // Re-enable button after request completes
                    button.prop('disabled', false);
                }
            });
        });

        // Single teacher verification
        $('.verify-teacher').on('click', function() {
            let teacherId = $(this).data('teacher-id');
            let button = $(this);
            
            Swal.fire({
                title: 'Verify Teacher?',
                text: 'Are you sure you want to verify this teacher?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, verify',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/teachers/${teacherId}/toggle-status`,
                        method: 'POST',
                        data: { status: 'verified' },
                        beforeSend: function() {
                            $('.table-loading-overlay').show();
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Success', 'Teacher has been verified', 'success');
                                location.reload();
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Failed to verify teacher', 'error');
                        },
                        complete: function() {
                            $('.table-loading-overlay').hide();
                        }
                    });
                }
            });
        });

        // Verify all completed profiles
        $('.verify-all-btn').on('click', function() {
            Swal.fire({
                title: 'Verify All Completed Profiles?',
                text: 'This will verify all teachers with completed profiles. Continue?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, verify all',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.teachers.verify-completed") }}',
                        method: 'POST',
                        beforeSend: function() {
                            $('.table-loading-overlay').show();
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Success', 'All completed profiles have been verified', 'success');
                                location.reload();
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Failed to verify teachers', 'error');
                        },
                        complete: function() {
                            $('.table-loading-overlay').hide();
                        }
                    });
                }
            });
        });
    });
    </script>

    <script>
        // Common function for showing confirmation dialog
        function showConfirmDialog(title, text, icon = 'warning') {
            return Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, verify!',
                cancelButtonText: 'Cancel'
            });
        }

        // Common function for showing result message
        function showResultMessage(success, message) {
            Swal.fire(
                success ? 'Success!' : 'Error!',
                message,
                success ? 'success' : 'error'
            ).then(() => {
                if (success) window.location.reload();
            });
        }

        function verifyTeacher(userId) {
            showConfirmDialog(
                'Verify Teacher',
                'Are you sure you want to verify this teacher?'
            ).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route("admin.teachers.toggle-status", ["user_id" => "__USER_ID__"]) }}'.replace('__USER_ID__', userId),
                        data: {
                            status: 'verified',
                            _token: '{{ csrf_token() }}'
                        },
                        beforeSend: function() {
                            $('.table-loading-overlay').show();
                        },
                        success: function(response) {
                            showResultMessage(response.success, response.message);
                        },
                        error: function(xhr) {
                            let message = 'Something went wrong while verifying the teacher.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            showResultMessage(false, message);
                        },
                        complete: function() {
                            $('.table-loading-overlay').hide();
                        }
                    });
                }
            });
        }

        function verifyAllTeachers() {
            showConfirmDialog(
                'Verify All Teachers',
                'Are you sure you want to verify all teachers with completed profiles?',
                'question'
            ).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.teachers.verify-completed") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        beforeSend: function() {
                            $('.table-loading-overlay').show();
                        },
                        success: function(response) {
                            showResultMessage(
                                response.success,
                                response.message || 'All completed profiles have been verified'
                            );
                        },
                        error: function() {
                            showResultMessage(false, 'Failed to verify teachers');
                        },
                        complete: function() {
                            $('.table-loading-overlay').hide();
                        }
                    });
                }
            });
        }
    </script>
@endpush
