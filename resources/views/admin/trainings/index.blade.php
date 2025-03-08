@extends('admin.master_layout.index')

@section('title', 'TTP - Subjects')

@section('content')
<style>
    .training-form {
        border-radius: 10px;
        background: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }
    .session-block {
        background: #f9f9f9;
        border-radius: 8px;
        padding: 15px;
        margin-top: 10px;
    }
    .select2-container {
        width: 100% !important;
    }
</style>

<main class="main-content">
    <div class="container-fluid py-4">
        <div class="container mt-4">
            <form id="training-form" class="needs-validation training-form" novalidate>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Training Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="col-md-12">
                        <label for="description" class="form-label">Training Description <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" rows="3" class="form-control"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Training Phase <span class="text-danger">*</span></label>
                        <select class="form-select" name="training_phase" required>
                            <option value="">Select Phase</option>
                            <option value="1">Phase 1</option>
                            <option value="2">Phase 2</option>
                            <option value="3">Phase 3</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Training Level of Education <span class="text-danger">*</span></label>
                        <select class="form-select" name="education_level" required>
                            <option value="">Select Level</option>
                            <option value="Pre Primary Education">Pre Primary Education</option>
                            <option value="Primary Education">Primary Education</option>
                            <option value="Lower Secondary Education">Lower Secondary Education</option>
                            <option value="Higher Secondary Education">Higher Secondary Education</option>
                        </select>
                    </div>
                    <!-- Subject & Program Selection -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="subject" required>
                                <option value="">Select Subject</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Program <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="program" required disabled>
                                <option value="">Select Program</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="sessions-container"></div>
                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-success" id="add-session"><i class="fas fa-plus"></i> Add Session</button>
                    </div>
                    <div class="col-md-6 text-end">
                        <button type="submit" class="btn btn-success">💾 Save Training</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

@push('scripts')
<!-- subject population Handler -->
<script>
    $(document).ready(function(){
        $.ajax({
            url: '/admin/subjects/all-subjects',
            method: "GET",
            dataType: "json",
            success: function(response) {
                if (response.error === false && response.data) {
                    let subjectDropdown = $('#subject');
                    subjectDropdown.empty().append('<option value="">Select Subject</option>');

                    // Loop through response.data array
                    $.each(response.data, function(index, subject) {
                        subjectDropdown.append(`<option value="${subject.subject_id}">${subject.subject_name}</option>`);
                    });

                    // Enable the subject dropdown
                    subjectDropdown.prop('disabled', false);
                } else {
                    console.error("Error: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });

        // Enable Program dropdown when a subject is selected
        $('#subject').change(function() {
            $('#program').prop('disabled', false);
        });
    });
</script>
<!-- session Handler-->
<script>
    $(document).ready(function() {
        $('.select2').select2({ width: '100%' });
        let sessionCount = 0;

        function fetchRegions(selectElement) {
            $.getJSON(`/api/regions`, function(data) {
                selectElement.empty().append('<option value="">Select Region</option>');
                $.each(data, function(index, region) {
                    selectElement.append(`<option value="${region.region_id}">${region.region_name}</option>`);
                });
            }).fail(function() {
                console.error("Failed to fetch regions.");
            });
        }

        function fetchDistricts(regionId, selectElement) {
            $.getJSON(`/api/districts/${regionId}`, function(data) {
                selectElement.empty().append('<option value="">Select District</option>').prop('disabled', false);
                $.each(data, function(index, district) {
                    selectElement.append(`<option value="${district.district_id}">${district.district_name}</option>`);
                });
            }).fail(function() {
                console.error("Failed to fetch districts.");
            });
        }

        function fetchWards(districtId, selectElement) {
            $.getJSON(`/api/wards/${districtId}`, function(data) {
                selectElement.empty().append('<option value="">Select Ward</option>').prop('disabled', false);
                $.each(data, function(index, ward) {
                    selectElement.append(`<option value="${ward.ward_id}">${ward.ward_name}</option>`);
                });
            }).fail(function() {
                console.error("Failed to fetch wards.");
            });
        }

        $('#add-session').click(function() {
            sessionCount++;
            let sessionHTML = `
            <div class="session-block border p-3 mb-3">
                <h5>Session ${sessionCount}</h5>
                <hr>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Region <span class="text-danger">*</span></label>
                        <select class="form-select select2 session-region" required>
                            <option value="">Loading...</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">District <span class="text-danger">*</span></label>
                        <select class="form-select select2 session-district" required disabled>
                            <option value="">Select District</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Ward <span class="text-danger">*</span></label>
                        <select class="form-select select2 session-ward" required disabled>
                            <option value="">Select Ward</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Venue Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Number of Participants <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="number_of_participants" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Start Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" required name="start_time">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Start Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control session-start-date" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">End Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control session-end-date" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Duration (Days)</label>
                        <input type="number" class="form-control session-duration" readonly>
                    </div>
                </div>
            </div>
            `;

            $('#sessions-container').append(sessionHTML);
            let newSession = $('#sessions-container .session-block:last-child');
            newSession.find('.select2').select2({ width: '100%' });

            // Fetch regions for this session
            fetchRegions(newSession.find('.session-region'));
        });

        // Handle Region selection → Fetch Districts
        $(document).on('change', '.session-region', function() {
            let parent = $(this).closest('.session-block');
            let regionId = $(this).val();
            if (regionId) {
                fetchDistricts(regionId, parent.find('.session-district'));
            } else {
                parent.find('.session-district').empty().append('<option value="">Select District</option>').prop('disabled', true);
                parent.find('.session-ward').empty().append('<option value="">Select Ward</option>').prop('disabled', true);
            }
        });

        // Handle District selection → Fetch Wards
        $(document).on('change', '.session-district', function() {
            let parent = $(this).closest('.session-block');
            let districtId = $(this).val();
            if (districtId) {
                fetchWards(districtId, parent.find('.session-ward'));
            } else {
                parent.find('.session-ward').empty().append('<option value="">Select Ward</option>').prop('disabled', true);
            }
        });

        // Handle date calculations
        $(document).on('change', '.session-start-date, .session-end-date', function() {
            let parent = $(this).closest('.session-block');
            let startDate = new Date(parent.find('.session-start-date').val());
            let endDate = new Date(parent.find('.session-end-date').val());
            if (!isNaN(startDate) && !isNaN(endDate) && endDate >= startDate) {
                let duration = (endDate - startDate) / (1000 * 60 * 60 * 24) + 1;
                parent.find('.session-duration').val(duration);
            } else {
                parent.find('.session-duration').val('');
            }
        });
    });
</script>
@endpush
@endsection
