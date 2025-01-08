@extends('admin.master_layout.index')

@section('title', 'TTP - Teachers')

@section('content')
@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="//cdn.datatables.net/2.2.0/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teachers Table Section -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
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
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.teachers.show', $teacher->teacher_id) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.teachers.edit', $teacher->teacher_id) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-{{ $teacher->status === 'active' ? 'warning' : 'success' }} toggle-status"
                                                    data-teacher-id="{{ $teacher->teacher_id }}"
                                                    data-current-status="{{ $teacher->status }}"
                                                    title="{{ $teacher->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $teacher->status === 'active' ? 'ban' : 'check' }}"></i>
                                            </button>
                                        </div>
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
    <script src="//cdn.datatables.net/2.2.0/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="//cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let table = new DataTable('#teachers-table', {
            processing: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[1, 'asc']], // Sort by name by default
            dom: '<"top"<"row"<"col-md-6"B><"col-md-6"f>>><"clear">rt<"bottom"<"row"<"col-md-6"l><"col-md-6"p>>>',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-sm btn-success me-2',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-sm btn-danger me-2',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn btn-sm btn-info',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
                }
            ],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search teachers...",
                lengthMenu: "_MENU_ records per page",
                info: "Showing _START_ to _END_ of _TOTAL_ teachers",
                infoEmpty: "No teachers found",
                infoFiltered: "(filtered from _MAX_ total records)",
                processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
            }
        });

        $('#search-filter').on('keyup', function() {
            table.search(this.value).draw();
        });

        $('#education-filter, #subject-filter, #status-filter').on('change', function() {
            let educationVal = $('#education-filter').val();
            let subjectVal = $('#subject-filter').val();
            let statusVal = $('#status-filter').val();

            table.columns().search('').draw();

            if (educationVal) table.columns(5).search(educationVal);
            if (subjectVal) table.columns(2).search(subjectVal);
            if (statusVal) table.columns(6).search(statusVal);

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
    });
    </script>
@endpush
