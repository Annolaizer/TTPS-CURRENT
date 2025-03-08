@extends('admin.master_layout.index')

@section('title', 'TTP - Trainings')

@section('content')
@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('asset/css/admin/admin_training.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        a{text-decoration: none;}
          /* Style for caret icon */
          .caret-icon {
            cursor: pointer;
            font-size: 12px;
            transition: transform 0.3s;
        }
        .rotate {
            transform: rotate(90deg);
        }
        /* Compact table design */
        table {
            font-size: 0.8rem;
        }
        /* Smaller Action Icons */
        .action-btn {
            border: none;
            background: transparent;
            padding: 2px;
            font-size: 12px;
            color: #333;
            transition: 0.3s;
        }
        .action-btn:hover {
            color: #007bff;
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
                            <input type="text" class="form-control filter-input" id="search-filter" placeholder="Search by Title or Code">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-calendar"></i>
                            </span>
                            <input type="date" class="form-control filter-input" id="start-date">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select select2 filter-input" id="organization-filter">
                            <option value="">All Organizations</option>
                            @if(isset($organizations))
                                @foreach($organizations as $org)
                                    <option value="{{ $org->organization_id }}">{{ $org->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select filter-input" id="ownership-filter">
                            <option value="">All Ownership</option>
                            <option value="government">Government</option>
                            <option value="ngo">NGO</option>
                            <option value="private">Private</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select filter-input" id="status-filter">
                            <option value="">All Status</option>
                            <option value="draft">Draft</option>
                            <option value="pending">Pending</option>
                            <option value="verified">Verified</option>
                            <option value="rejected">Rejected</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <a href="{{ route('admin.trainings.create-training') }}">
                            <button type="button" class="btn btn-add-training">
                                <i class="fas fa-plus"></i> Add
                            </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Training Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="trainings-table" class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>-</th> <!-- Expand/Collapse Column -->
                                <th>Training Code</th>
                                <th>Title</th>
                                <th>Organization</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Add/Edit Training Modal -->
<div class="modal fade" id="training-modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-custom-primary text-white">
                <h5 class="modal-title">Add New Training</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="training-form" class="needs-validation" novalidate>
                    <!-- Basic Training Information -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Organization <span class="text-danger">*</span></label>
                            <select class="form-select select2" name="organization_id" required>
                                <option value="">Select Organization</option>
                                @if(isset($organizations))
                                    @foreach($organizations as $org)
                                        <option value="{{ $org->organization_id }}">{{ $org->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Education Level <span class="text-danger">*</span></label>
                            <select class="form-select" name="education_level" required>
                                <option value="">Select Level</option>
                                <option value="Pre Primary Education">Pre Primary Education</option>
                                <option value="Primary Education">Primary Education</option>
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
                            <input type="date" class="form-control" name="start_date" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="end_date" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Start Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" name="start_time" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Duration (Days) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="duration_days" required min="1">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Region <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="region" name="region_id" required>
                                <option value="">Select Region</option>
                                @if(isset($regions))
                                    @foreach($regions as $region)
                                        <option value="{{ $region->region_id }}">{{ $region->region_name }}</option>
                                    @endforeach
                                @endif
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="training-form" class="btn btn-custom-primary">Save Training</button>
            </div>
        </div>
    </div>
</div>

<!-- View Training Modal -->
<div class="modal fade" id="view-modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-custom-primary text-white">
                <h5 class="modal-title">Training Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- Training Code and Status -->
                    <div class="col-md-6">
                        <label class="fw-bold mb-1">Training Code:</label>
                        <p id="view-training-code" class="mb-0"></p>
                    </div>
                    <div class="col-md-6 text-end">
                        <label class="fw-bold mb-1">Status:</label>
                        <p id="view-status" class="mb-0"></p>
                    </div>

                    <!-- Basic Information -->
                    <div class="col-md-12">
                        <label class="fw-bold mb-1">Title:</label>
                        <p id="view-title" class="mb-0"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold mb-1">Organization:</label>
                        <p id="view-organization" class="mb-0"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold mb-1">Education Level:</label>
                        <p id="view-education-level" class="mb-0"></p>
                    </div>

                    <!-- Training Details -->
                    <div class="col-md-12">
                        <label class="fw-bold mb-1">Description:</label>
                        <p id="view-description" class="mb-0"></p>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold mb-1">Training Phase:</label>
                        <p id="view-phase" class="mb-0"></p>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold mb-1">Duration:</label>
                        <p id="view-duration" class="mb-0"></p>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold mb-1">Maximum Participants:</label>
                        <p id="view-participants" class="mb-0"></p>
                    </div>

                    <!-- Schedule -->
                    <div class="col-md-6">
                        <label class="fw-bold mb-1">Dates:</label>
                        <p id="view-dates" class="mb-0"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold mb-1">Start Time:</label>
                        <p id="view-time" class="mb-0"></p>
                    </div>

                    <!-- Location -->
                    <div class="col-md-12">
                        <label class="fw-bold mb-1">Venue:</label>
                        <p id="view-location" class="mb-0"></p>
                    </div>

                    <!-- Subjects -->
                    <div class="col-md-12">
                        <label class="fw-bold mb-1">Subjects:</label>
                        <p id="view-subjects" class="mb-0"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <div class="d-flex gap-2 pending-actions">
                    <button type="button" class="btn btn-success verify-training-btn">
                        <i class="fas fa-check me-1"></i> Verify
                    </button>
                    <button type="button" class="btn btn-danger reject-training-btn">
                        <i class="fas fa-times me-1"></i> Reject
                    </button>
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Training Modal -->
<div class="modal fade" id="reject-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Reject Training</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="reject-form">
                    <input type="hidden" name="training_code" id="reject-training-code">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="reject-form" class="btn btn-danger">Confirm Rejection</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.trainings = {!! $trainingsJson !!};
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script>
        // Add CSRF token to all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script src="{{ asset('asset/js/admin/training.js') }}"></script>
@endpush
@endsection