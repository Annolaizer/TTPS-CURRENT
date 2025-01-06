@extends('admin.master_layout.index')

@section('title', 'TTP - Training Assignment')

@php
use App\Helpers\StatusHelper;
@endphp

@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('asset/css/admin/admin_training.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .training-info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .info-card {
            background: #fff;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .info-card h6 {
            color: #6c757d;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }
        .info-card p {
            margin: 0;
            font-size: 1rem;
            font-weight: 500;
        }
        .phase-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .badge {
            padding: 0.5em 1em;
            font-size: 0.875em;
            text-transform: capitalize;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            transform: translateY(-1px);
        }
        .text-muted {
            color: #6c757d !important;
            font-style: italic;
        }
        .table td {
            vertical-align: middle;
        }
    </style>
@endpush
@section('content')
<!-- Main Content -->
<main class="main-content">
    <div class="container-fluid">
        <!-- Training Details Grid -->
        <div class="training-info-grid">
            <div class="info-card">
                <h6>Training Code</h6>
                <p id="training-code">{{ $training->training_code }}</p>
            </div>
            <div class="info-card">
                <h6>Title</h6>
                <p id="training-title">{{ $training->title }}</p>
            </div>
            <div class="info-card">
                <h6>Organization</h6>
                <p id="training-org" data-org-id="{{ $training->organization_id }}">{{ $training->organization->name }}</p>
            </div>
            <div class="info-card">
                <h6>Education Level</h6>
                <p id="education-level">{{ $training->education_level }}</p>
            </div>
            <div class="info-card">
                <h6>Current Phase</h6>
                <p id="training-phase">{{ $training->training_phase }}</p>
            </div>
            <div class="info-card">
                <h6>Status</h6>
                <p><span id="training-status" class="badge bg-{{ StatusHelper::getBadgeClass($training->status) }}">{{ $training->status }}</span></p>
            </div>
            <div class="info-card">
                <h6>Start Date</h6>
                <p id="start-date">{{ $training->start_date }}</p>
            </div>
            <div class="info-card">
                <h6>End Date</h6>
                <p id="end-date">{{ $training->end_date }}</p>
            </div>
            <div class="info-card">
                <!-- Phase Update Section -->
                <div class="phase-card">
                    <h5 class="mb-4">Training Actions</h5>
                    <div class="d-grid gap-2">
                        <button id="addPhaseBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#phase-modal">
                            <i class="fas fa-plus"></i> Add New Phase
                        </button>
                        <button id="assign-participants-btn" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#participants-modal">
                            <i class="fas fa-users"></i> Assign Participants
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phase Modal -->
        <div class="modal fade" id="phase-modal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-custom-primary text-white">
                        <h5 class="modal-title">Add New Training Phase</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="phase-form" class="needs-validation" novalidate>
                            <!-- Basic Training Information -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-12">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" required disabled>
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
                                    <input type="text" class="form-select" name="education_level" required id="education_level">
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
                                        <option value="5">Phase 5</option>
                                        <option value="6">Phase 6</option>
                                        <option value="7">Phase 7</option>
                                        <option value="8">Phase 8</option>
                                        <option value="9">Phase 9</option>
                                        <option value="10">Phase 10</option>
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
                                    <input type="date" class="form-control" name="start_date" id="start_date" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="end_date" id="end_date" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Start Time <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" name="start_time" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Duration (Days)</label>
                                    <input type="number" class="form-control" name="duration_days" id="duration_days" readonly>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Region <span class="text-danger">*</span></label>
                                    <select class="form-select select2" name="region_id" id="region" required>
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
                                    <select class="form-select select2" name="district_id" id="district" required disabled>
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
                                    <select class="form-select select2" name="subjects[]" multiple required disabled>
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
                        <button type="submit" form="phase-form" class="btn btn-custom-primary">Save Phase</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Assignment Section -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-custom-primary text-white">
                        <h5 class="mb-0">Training Participants</h5>
                    </div>
                    <div class="card-body">
                        <table id="participantsTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Name</th>
                                    <th>Participant Type</th>
                                    <th>Status</th>
                                    <th>Report</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Training Participants Modal -->
        <div class="modal fade" id="participants-modal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-custom-primary text-white">
                        <h5 class="modal-title">Assign Training Participants</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="participants-form" class="needs-validation" novalidate>
                            <!-- Training Type -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="training_type" id="mandatory" value="mandatory" checked>
                                        <label class="form-check-label" for="mandatory">Mandatory Training</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="training_type" id="optional" value="optional">
                                        <label class="form-check-label" for="optional">Optional Training</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Available Teachers Section -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">Available Teachers</h6>
                                            <small class="text-muted">Teachers matching education level and availability</small>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <input type="text" class="form-control" id="teacher-search" placeholder="Search teachers...">
                                            </div>
                                            <div class="table-responsive" style="max-height: 400px;">
                                                <table class="table table-hover" id="available-teachers-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Education Level</th>
                                                            <th>School</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Populated dynamically -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Available Facilitators Section -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">Available CPD Facilitators</h6>
                                            <small class="text-muted">Facilitators available for the training period</small>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <input type="text" class="form-control" id="facilitator-search" placeholder="Search facilitators...">
                                            </div>
                                            <div class="table-responsive" style="max-height: 400px;">
                                                <table class="table table-hover" id="available-facilitators-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Specialization</th>
                                                            <th>Experience</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Populated dynamically -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="participants-form" class="btn btn-custom-primary">Save Assignments</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('asset/js/admin/training_assignment.js') }}"></script>
    <script>
        // Initialize Select2 for static elements
        $(document).ready(function() {
            // Initialize Select2 for phase modal
            $('#phase-modal .select2').select2({
                width: '100%',
                dropdownParent: $('#phase-modal'),
                placeholder: 'Select an option'
            });

            // Initialize location selects
            $('#district').select2({
                width: '100%',
                placeholder: 'Select District',
                dropdownParent: $('#phase-modal')
            });

            $('#ward').select2({
                width: '100%',
                placeholder: 'Select Ward',
                dropdownParent: $('#phase-modal')
            });
        });
    </script>
@endpush

@endsection