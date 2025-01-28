<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('asset/images/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <title>TTPS - Trainings</title>
    <style>
        :root {
            --primary-color: #009c95;
            --secondary-color: #cc3344;
            --hover-color: #007c77;
            --hover-dark: #006c68;
            --light-bg: #f8f9fa;
            --navbar-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            padding-top: var(--navbar-height);
            color: #333;
        }

        .navbar {
            background-color: var(--primary-color);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1020;
            min-height: var(--navbar-height);
            padding: 0.625rem 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .training-section {
            padding: 2rem 0;
        }

        .filters {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }

        .table-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 1.5rem;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: var(--light-bg);
            color: #555;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        .table td {
            vertical-align: middle;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-pending {
            background-color: #fff3e0;
            color: #ef6c00;
        }

        .status-verified {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .status-ongoing {
            background-color: #e3f2fd;
            color: #1565c0;
        }

        .status-completed {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }

        .status-rejected {
            background-color: #ffebee;
            color: #c62828;
        }

        .btn-filter {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-filter:hover {
            background-color: var(--hover-color);
            color: white;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #dee2e6;
            padding: 0.5rem 1rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 156, 149, 0.25);
        }

        .pagination {
            margin-top: 1.5rem;
            justify-content: center;
        }

        .page-link {
            color: var(--primary-color);
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <a href="#" class="navbar-brand">
                <img src="{{ asset('asset/images/logo.png') }}" alt="Logo" id="logo" style="height: 40px;">
                <span class="portal-name" style="color: white; margin-left: 10px;">Tanzania Teacher Portal</span>
            </a>
            <a href="{{ route('organization.dashboard') }}" class="btn btn-filter">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </nav>

    <div class="container training-section">
        <!-- Filters -->
        <div class="filters">
            <div class="row g-3">
                <div class="col-md-11">
                    <form action="{{ route('organization.trainings') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="search" placeholder="Search training..." value="{{ $search }}">
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="status">
                                <option value="all">All Status</option>
                                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="verified" {{ $status == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="ongoing" {{ $status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <input type="date" class="form-control" name="date_from" placeholder="From" value="{{ $date_from }}">
                                <span class="input-group-text">to</span>
                                <input type="date" class="form-control" name="date_to" placeholder="To" value="{{ $date_to }}">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-filter w-100" id="training-add" data-bs-toggle="modal" data-bs-target="#training-modal">
                        <i class="fas fa-plus-circle"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-card">
            @if($trainings->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-folder-open text-muted" style="font-size: 3rem;"></i>
                    <p class="mt-3 text-muted">No trainings found</p>
                </div>
            @else
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <span class="me-2">Show</span>
                        <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href=this.value">
                            @foreach($perPageOptions as $option)
                                <option value="{{ request()->fullUrlWithQuery(['per_page' => $option]) }}" 
                                        {{ $perPage == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                        <span class="ms-2">entries</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Code</th>
                                <th>Education Level</th>
                                <th>Phase</th>
                                <th>Start Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trainings as $training)
                                <tr>
                                    <td>{{ $training->title }}</td>
                                    <td>{{ $training->training_code }}</td>
                                    <td>{{ $training->education_level }}</td>
                                    <td>Phase {{ $training->training_phase }}</td>
                                    <td>{{ date('d M Y', strtotime($training->start_date)) }}</td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower($training->status) }}">
                                            {{ ucfirst($training->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('organization.trainings.show', $training->training_id) }}" class="btn btn-sm btn-info text-white">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('organization.trainings.show', $training->training_id) }}" class="btn btn-sm btn-warning text-white">
                                            <i class="fas fa-pen-alt"></i>
                                        </a>
                                        <a href="{{ route('organization.trainings.show', $training->training_id) }}" class="btn btn-sm btn-danger text-white">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="pagination-info">
                        Showing {{ $trainings->firstItem() ?? 0 }} to {{ $trainings->lastItem() ?? 0 }} of {{ $trainings->total() }} entries
                    </div>
                    <div class="datatable-pagination">
                        @if ($trainings->hasPages())
                            <div class="pagination">
                                {{-- Previous Page Link --}}
                                <span class="paginate_button {{ $trainings->onFirstPage() ? 'disabled' : '' }}">
                                    <a href="{{ $trainings->previousPageUrl() }}" {{ $trainings->onFirstPage() ? 'tabindex="-1"' : '' }}>«</a>
                                </span>

                                {{-- Pagination Elements --}}
                                @foreach ($trainings->getUrlRange(1, $trainings->lastPage()) as $page => $url)
                                    <span class="paginate_button {{ $page == $trainings->currentPage() ? 'current' : '' }}">
                                        <a href="{{ $url }}">{{ $page }}</a>
                                    </span>
                                @endforeach

                                {{-- Next Page Link --}}
                                <span class="paginate_button {{ !$trainings->hasMorePages() ? 'disabled' : '' }}">
                                    <a href="{{ $trainings->nextPageUrl() }}" {{ !$trainings->hasMorePages() ? 'tabindex="-1"' : '' }}>»</a>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Add/Edit Training Modal -->
    <div class="modal fade" id="training-modal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-custom-primary text-white">
                    <h5 class="modal-title text-black">Add New Training</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="training-form" class="needs-validation" novalidate>
                    <div class="modal-body">
                        <input type="text" name="organization_id" id="organization_id" value="{{ $organization->organization_id}}" hidden>
                        <!-- Basic Training Information -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Organization <span class="text-danger">*</span></label>
                                <input class="form-control" name="organisation_name" required value="{{ $organization->name }}" readonly>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Education Level <span class="text-danger">*</span></label>
                                <select class="form-select" name="education_level" required>
                                    <option value="">Select Level</option>
                                    <option value="Pre Primary Education">Pre Primary Education</option>
                                    <option value="Primary Education">Primary</option>
                                    <option value="Lower Secondary Education">Lower Secondary</option>
                                    <option value="Higher Secondary Education">Higher Secondary</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <!-- Training Details -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Training Phase <span class="text-danger">*</span></label>
                                <select class="form-select" name="training_phase" required>
                                    <option value="">Select Phase</option>
                                    <option value="1">Phase 1</option>
                                    <option value="2">Phase 2</option>
                                    <option value="3">Phase 3</option>
                                    <option value="4">Phase 4</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Maximum Participants <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="max_participants" required min="1">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="description" rows="3" required></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <!-- Schedule -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="start_date" required id="start_date">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="end_date" required id="end_date">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Start Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="start_time" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Duration (Days) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="duration_days" id="duration_days" required min="1" readonly>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Region <span class="text-danger">*</span></label>
                                <select class="form-select select2" id="region" name="region_id" required>
                                    <option value="">Select Region</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">District <span class="text-danger">*</span></label>
                                <select class="form-select select2" id="district" name="district_id" required disabled>
                                    <option value="">Select District</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ward <span class="text-danger">*</span></label>
                                <select class="form-select select2" name="ward_id" id="ward" required disabled>
                                    <option value="">Select Ward</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Venue Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="venue_name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <!-- Subjects -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Subjects <span class="text-danger">*</span></label>
                                <select class="form-select select2" name="subjects[]" multiple required>
                                    @if(isset($subjects))
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->subject_id }}">{{ $subject->subject_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" form="training-form" class="btn btn-success save-training-btn" id="save-training">
                            <i class="fas fa-save me-1"></i>Save Training
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script>
        $(document).ready(function() {
            // Set up CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // Date validation and duration calculation
            $('input[name="start_date"], input[name="end_date"]').on('change', function() {
                const startDate = $('input[name="start_date"]').val();
                const endDate = $('input[name="end_date"]').val();
                
                if (startDate && endDate) {
                    const start = moment(startDate);
                    const end = moment(endDate);
                    
                    if (end.isBefore(start)) {
                        $(this).addClass('is-invalid');
                        $(this).next('.invalid-feedback').text('End date cannot be before start date');
                        $('input[name="duration_days"]').val('');
                    } else {
                        $(this).removeClass('is-invalid');
                        $(this).next('.invalid-feedback').text('');
                        const duration = end.diff(start, 'days') + 1;
                        $('input[name="duration_days"]').val(duration);
                    }
                }
            });

            // Time validation
            $('input[name="start_time"]').on('change', function() {
                const timeRegex = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;
                const time = $(this).val();
                
                if (!timeRegex.test(time)) {
                    $(this).addClass('is-invalid');
                    $(this).next('.invalid-feedback').text('Please enter a valid time in HH:MM format');
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').text('');
                }
            });

            // Region change event
            $('#region').on('change', function() {
                let regionId = $(this).val();
                let districtSelect = $('#district');
                let wardSelect = $('#ward');
                
                // Clear and disable dropdowns
                districtSelect.empty().append('<option value="">Select District</option>').prop('disabled', true);
                wardSelect.empty().append('<option value="">Select Ward</option>').prop('disabled', true);
                
                if (regionId) {
                    console.log('Loading districts for region:', regionId);
                    
                    $.ajax({
                        url: `/api/districts/${regionId}`,
                        type: 'GET',
                        success: function(response) {
                            console.log('Districts response:', response);
                            
                            // Handle both array and object formats
                            const districts = Array.isArray(response) ? response : (response.districts || []);
                            
                            if (!districts || !districts.length) {
                                console.error('No districts found in response:', response);
                                return;
                            }

                            districts.forEach(function(district) {
                                districtSelect.append(`<option value="${district.district_id}">${district.district_name}</option>`);
                            });
                            districtSelect.prop('disabled', false).trigger('change');
                            
                            console.log('Districts loaded successfully');
                        },
                        error: function(xhr, status, error) {
                            console.error('Failed to load districts:', {
                                status: status,
                                error: error,
                                response: xhr.responseText
                            });
                        }
                    });
                }
            });

            // District change event
            $('#district').on('change', function() {
                let districtId = $(this).val();
                let wardSelect = $('#ward');
                
                // Clear and disable ward dropdown
                wardSelect.empty().append('<option value="">Select Ward</option>').prop('disabled', true);
                
                if (districtId) {
                    console.log('Loading wards for district:', districtId);
                    
                    $.ajax({
                        url: `/api/wards/${districtId}`,
                        type: 'GET',
                        success: function(response) {
                            console.log('Wards response:', response);

                            const wards = Array.isArray(response) ? response : (response.wards || []);

                            if (!wards || !wards.length) {
                                console.error('No wards found in response:', response);
                                return;
                            }
                            wards.forEach(function(ward) {
                                wardSelect.append(`<option value="${ward.ward_id}">${ward.ward_name}</option>`);
                            });
                            wardSelect.prop('disabled', false).trigger('change');
                            
                            console.log('Wards loaded successfully');
                        },
                        error: function(xhr, status, error) {
                            console.error('Failed to load wards:', {
                                status: status,
                                error: error,
                                response: xhr.responseText
                            });
                        }
                    });
                }
            });

            // Load initial data when modal opens
            $('#training-modal').on('show.bs.modal', function() {
                // Get regions from the PHP variable
                const regions = @json($regions);
                console.log('Regions from PHP:', regions);

                // Get the region select element
                const regionSelect = $('#region');
                regionSelect.empty().append('<option value="">Select Region</option>');

                // Populate regions dropdown
                regions.forEach(region => {
                    regionSelect.append(new Option(region.region_name, region.region_id));
                });

                // Reset other selects
                $('#district').empty().append('<option value="">Select District</option>').prop('disabled', true);
                $('#ward').empty().append('<option value="">Select Ward</option>').prop('disabled', true);

                // Refresh Select2
                $('.select2').trigger('change');
            });

            // Reset form when modal is closed
            $('#training-modal').on('hidden.bs.modal', function () {
                // Reset form
                $('#training-form')[0].reset();
                
                // Reset select2 fields
                $('.select2').val(null).trigger('change');
                
                // Reset location fields
                $('#region').val(null).trigger('change');
                $('#district').prop('disabled', true).empty().append('<option value="">Select District</option>');
                $('#ward').prop('disabled', true).empty().append('<option value="">Select Ward</option>');
                
                // Remove validation errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            });

            $('#save-training').click(function (e) {
                e.preventDefault(); // Prevent default form submission behavior

                // Show a loading alert with Swal.fire
                let loadingSwal = Swal.fire({
                    title: 'Saving Training...',
                    text: 'Please wait while your training is being saved.',
                    allowOutsideClick: false, // Prevent user from dismissing the dialog
                    didOpen: () => {
                        Swal.showLoading(); // Show loading spinner
                    }
                });

                // Get the form data
                let form = $('#training-form')[0];
                let formData = new FormData(form);

                $.ajax({
                    url: '{{ route("organization.trainings.store") }}', // Backend route
                    method: 'POST', // HTTP method
                    data: formData, // Form data
                    processData: false, // Prevent jQuery from processing data
                    contentType: false, // Prevent jQuery from setting content type
                    success: function (response) {
                        // Close the loading Swal and show a success notification
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Training has been saved successfully.',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Hide modal and reset the form
                        $('#training-modal').modal('hide');
                        $('#training-form')[0].reset();

                        // Refresh the trainings table/list if the function exists
                        if (typeof refreshTrainings === 'function') {
                            refreshTrainings();
                        }
                    },
                    error: function (xhr) {
                        // Close the loading Swal before showing the error notification
                        Swal.close();

                        // Parse error message
                        let errorMessage = 'Something went wrong. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        // Show error notification
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                            confirmButtonText: 'OK' // Ensure the user can see and dismiss the error
                        });
                    },
                    complete: function () {
                        // Re-enable the submit button and restore original text
                        $('.save-training-btn').prop('disabled', false).html('<i class="fas fa-save me-1"></i>Save Training');
                    }
                });
            });


        });
    </script>
</body>
</html>
