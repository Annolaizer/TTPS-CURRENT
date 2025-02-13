<!-- Location Information Section -->
<div class="card mb-4" style="z-index: 999; margin-top: -40px">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h4 class="text-success mb-0">Location Information</h4>
        <hr>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-4">
                <label class="form-label small">Region<span class="text-danger">*</span></label>
                <select class="form-select form-select-sm select2" id="region_id" required>
                    <option value="">Select region</option>
                </select>
                @error('region_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            

            <div class="col-md-4">
                <label class="form-label small">District<span class="text-danger">*</span></label>
                <select class="form-select form-select-sm select2" id="district_id" required disabled>
                    <option value="">Select district</option>
                </select>
                @error('district_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label small">Ward<span class="text-danger">*</span></label>
                <select class="form-select form-select-sm select2" name="ward_id" id="ward_id" required disabled>
                    <option value="">Select ward</option>
                </select>
                @error('ward_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-12">
                <label class="form-label small">School Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="school_name" value="{{ old('school_name', auth()->user()->teacherProfile->school_name ?? '') }}" required>
                @error('school_name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 with loading state
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        language: {
            searching: function() {
                return 'Loading...';
            }
        }
    });

    const regionSelect = $('#region_id');
    const districtSelect = $('#district_id');
    const wardSelect = $('#ward_id');
    const currentWardId = '{{ old("ward_id", auth()->user()->teacherProfile->ward_id ?? "") }}';

    // Reset location fields function
    function resetLocationFields() {
        regionSelect.val(null).trigger('change');
        districtSelect.empty().append('<option value="">Select District</option>').prop('disabled', true);
        wardSelect.empty().append('<option value="">Select Ward</option>').prop('disabled', true);
    }

    // Function to load regions
    function loadRegions() {
        regionSelect.prop('disabled', true);
        console.log('Loading regions...'); // Debug log

        $.ajax({
            url: '{{ url("/api/regions") }}',
            type: 'GET',
            success: function(response) {
                console.log('Regions response:', response); // Debug log
                
                const regions = Array.isArray(response) ? response : (response.regions || []);
                
                if (!regions || !regions.length) {
                    console.error('No regions found in response:', response);
                    Swal.fire('Error', 'No regions found in server response', 'error');
                    return;
                }

                regionSelect.empty().append('<option value="">Select Region</option>');
                regions.forEach(function(region) {
                    regionSelect.append(`<option value="${region.id || region.region_id}">${region.name || region.region_name}</option>`);
                });
                
                regionSelect.prop('disabled', false);
                console.log('Regions loaded successfully'); // Debug log

                // If there's a current ward, load the full hierarchy
                if (currentWardId) loadLocationHierarchy(currentWardId);
            },
            error: function(xhr, status, error) {
                console.error('Failed to load regions:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
                
                Swal.fire('Error', 'Failed to load regions. Please try again.', 'error');
                regionSelect.prop('disabled', false);
            }
        });
    }

    // Function to load districts
    function loadDistricts(regionId) {
        if (!regionId) {
            districtSelect.empty().append('<option value="">Select District</option>').prop('disabled', true);
            wardSelect.empty().append('<option value="">Select Ward</option>').prop('disabled', true);
            return;
        }

        districtSelect.prop('disabled', true);
        console.log('Loading districts for region:', regionId); // Debug log

        $.ajax({
            url: `{{ url('/api/districts') }}/${regionId}`,
            type: 'GET',
            success: function(response) {
                console.log('Districts response:', response); // Debug log
                
                const districts = Array.isArray(response) ? response : (response.districts || []);
                
                if (!districts || !districts.length) {
                    console.error('No districts found in response:', response);
                    Swal.fire('Error', 'No districts found for this region', 'error');
                    return;
                }

                districtSelect.empty().append('<option value="">Select District</option>');
                districts.forEach(function(district) {
                    districtSelect.append(`<option value="${district.district_id}">${district.district_name}</option>`);
                });
                
                districtSelect.prop('disabled', false);
                console.log('Districts loaded successfully'); // Debug log
            },
            error: function(xhr, status, error) {
                console.error('Failed to load districts:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
                
                Swal.fire('Error', 'Failed to load districts. Please try again.', 'error');
                districtSelect.prop('disabled', false);
            }
        });
    }

    // Function to load wards
    function loadWards(districtId) {
        if (!districtId) {
            wardSelect.empty().append('<option value="">Select Ward</option>').prop('disabled', true);
            return;
        }

        wardSelect.prop('disabled', true);
        console.log('Loading wards for district:', districtId); // Debug log

        $.ajax({
            url: `{{ url('/api/wards') }}/${districtId}`,
            type: 'GET',
            success: function(response) {
                console.log('Wards response:', response); // Debug log
                
                const wards = Array.isArray(response) ? response : (response.wards || []);
                
                if (!wards || !wards.length) {
                    console.error('No wards found in response:', response);
                    Swal.fire('Error', 'No wards found for this district', 'error');
                    return;
                }

                wardSelect.empty().append('<option value="">Select Ward</option>');
                wards.forEach(function(ward) {
                    wardSelect.append(`<option value="${ward.ward_id}">${ward.ward_name}</option>`);
                });
                
                wardSelect.prop('disabled', false);
                console.log('Wards loaded successfully'); // Debug log
            },
            error: function(xhr, status, error) {
                console.error('Failed to load wards:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
                
                Swal.fire('Error', 'Failed to load wards. Please try again.', 'error');
                wardSelect.prop('disabled', false);
            }
        });
    }

    // Function to load location hierarchy for pre-selected ward
    async function loadLocationHierarchy(wardId) {
        try {
            const response = await $.get(`{{ url('/api/wards') }}/${wardId}`);
            if (response && response.district) {
                regionSelect.val(response.district.region_id).trigger('change');
                
                // Wait for districts to load
                setTimeout(() => {
                    districtSelect.val(response.district_id).trigger('change');
                    
                    // Wait for wards to load
                    setTimeout(() => {
                        wardSelect.val(wardId).trigger('change');
                    }, 500);
                }, 500);
            }
        } catch (error) {
            console.error('Failed to load location hierarchy:', error);
        }
    }

    // Event listeners for cascading selects
    regionSelect.on('change', function() {
        loadDistricts($(this).val());
    });

    districtSelect.on('change', function() {
        loadWards($(this).val());
    });

    // Initial load
    loadRegions();
});
</script>
@endpush
