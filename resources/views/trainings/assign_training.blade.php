@extends('admin.master_layout.index')

@section('title', 'TTP - Training Assignment')

@php
use App\Helpers\StatusHelper;
@endphp

@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('asset/css/admin/admin_training.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css" rel="stylesheet" />
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
        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(2.25rem + 2px) !important;
        }
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder {
            color: #757575;
            line-height: 2.25rem;
        }
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
            position: absolute;
            top: 50%;
            right: 3px;
            width: 20px;
            transform: translateY(-50%);
        }
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: 2.25rem;
        }
        .select2-container--bootstrap4 .select2-results__option {
            padding: 0.75rem 1rem;
        }
        .select2-container--bootstrap4 .select2-results__option--highlighted {
            background-color: var(--bs-primary) !important;
        }
        .select2-search--dropdown .select2-search__field {
            padding: 0.5rem;
            border-radius: 0.25rem;
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
                <input type="hidden" name="training_code" value="{{ $training->training_code }}" id="training-code-input">
                <input type="hidden" id="training-subjects" value="{{ json_encode($training->subjects->pluck('subject_id')) }}">
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
                            <input type="hidden" name="training_code" value="{{ $training->training_code }}">
                            
                            <!-- Basic Training Information -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" required>
                                    <div class="invalid-feedback">Please provide a title.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Maximum Participants <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="max_participants" required>
                                    <div class="invalid-feedback">Please specify maximum participants.</div>
                                </div>
                            </div>
                            
                            <!-- Dates and Time -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="start_date" required>
                                    <div class="invalid-feedback">Please select a start date.</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="end_date" required>
                                    <div class="invalid-feedback">Please select an end date.</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Start Time <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" name="start_time" required>
                                    <div class="invalid-feedback">Please specify the start time.</div>
                                </div>
                            </div>

                            <!-- Training Details -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Training Phase <span class="text-danger">*</span></label>
                                    <select class="form-select" name="training_phase" required>
                                        <option value="">Select Phase</option>
                                        @for($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}">Phase {{ $i }}</option>
                                        @endfor
                                    </select>
                                    <div class="invalid-feedback">Please select a phase number</div>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" rows="3" disabled>{{ $training->description }}</textarea>
                                    <input type="hidden" name="description" value="{{ $training->description }}">
                                    <div class="invalid-feedback">Please provide a description for this phase</div>
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Region <span class="text-danger">*</span></label>
                                    <select class="form-select select2" name="region_id" required>
                                        <option value="">Select Region</option>
                                        @foreach($regions as $region)
                                            <option value="{{ $region->region_id }}">{{ $region->region_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select a region</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">District <span class="text-danger">*</span></label>
                                    <select class="form-select select2" name="district_id" required disabled>
                                        <option value="">Select District</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a district</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Ward <span class="text-danger">*</span></label>
                                    <select class="form-select select2" name="ward_id" required disabled>
                                        <option value="">Select Ward</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a ward</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Venue Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="venue_name" required>
                                    <div class="invalid-feedback">Please enter the venue name</div>
                                </div>
                            </div>

                            <!-- Subjects -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-12">
                                    <label class="form-label">Subjects <span class="text-danger">*</span></label>
                                    <select class="form-select select2" multiple name="subjects[]" required>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->subject_id }}">{{ $subject->subject_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select at least one subject</div>
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
                            <input type="hidden" id="training-code-input" name="training_code" value="{{ $training->training_code }}">
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
                                <input type="hidden" id="max-participants" name="max_participants" value="{{ $training->max_participants }}">
                            </div>

                            <div class="row">
                                <!-- Available Teachers Section -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <label for="region_filter" class="form-label">
                                                    Filter by Region
                                                </label>
                                                <select class="form-select select2-region" id="region_filter" name="region_filter" style="width: 100%">
                                                    <option value="" data-icon="fas fa-globe">All Regions</option>
                                                    @foreach($regions as $region)
                                                        <option value="{{ $region->region_id }}">{{ $region->region_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="teachers-container border rounded p-3">
                                                <!-- Header with Select All and Counter -->
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="select-all">
                                                        <label class="form-check-label" for="select-all">Select All</label>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted">Total Teachers: <span id="total-teachers">0</span></small>
                                                    </div>
                                                </div>

                                                <!-- Scrollable Teachers List -->
                                                <div class="teachers-scroll" style="height: 400px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 4px; background-color: #f8f9fa;">
                                                    <div id="teachers-content" class="p-2">
                                                        <!-- Teachers will be dynamically loaded here -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Available Facilitators Section -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <label for="facilitator-search" class="form-label">
                                                    Search Facilitators
                                                </label>
                                                <input type="text" class="form-control" id="facilitator-search" placeholder="Search by name or specialization...">
                                            </div>
                                            <div class="facilitators-container border rounded p-3">
                                                <!-- Header with Select All and Counter -->
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="select-all-facilitators">
                                                        <label class="form-check-label" for="select-all-facilitators">Select All</label>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted">Total Facilitators: <span id="total-facilitators">0</span></small>
                                                    </div>
                                                </div>

                                                <!-- Scrollable Facilitators List -->
                                                <div class="facilitators-scroll" style="height: 400px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 4px; background-color: #f8f9fa;">
                                                    <div id="facilitators-content" class="p-2">
                                                        <!-- Facilitators will be dynamically loaded here -->
                                                    </div>
                                                </div>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom Scripts -->
    <script src="{{ asset('asset/js/training/training_phase.js') }}"></script>
    <script src="{{ asset('asset/js/training/participant_assignment.js') }}"></script>
@endpush
@endsection