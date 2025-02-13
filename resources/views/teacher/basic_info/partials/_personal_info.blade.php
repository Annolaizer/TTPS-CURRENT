<!-- Personal Information Section -->
<div class="card mb-4">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h5 class="text-success mb-0">Personal Information</h5>
        <hr>
    </div>
    <div class="card-body">
        <div class="row g-4" id="personalInfoForm">
            <div class="col-md-6 form-group">
                <label class="form-label small">First Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm @error('first_name') is-invalid @enderror" 
                    name="first_name" 
                    value="{{ old('first_name', auth()->user()->first_name ?? '') }}" 
                    required
                    minlength="2"
                    maxlength="50"
                    pattern="[A-Za-z ]+"
                    title="Please enter a valid first name (letters only)">
                @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 form-group">
                <label class="form-label small">Last Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm @error('last_name') is-invalid @enderror" 
                    name="last_name" 
                    value="{{ old('last_name', auth()->user()->last_name ?? '') }}" 
                    required
                    minlength="2"
                    maxlength="50"
                    pattern="[A-Za-z ]+"
                    title="Please enter a valid last name (letters only)">
                @error('last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label small">Email</label>
                <input type="email" class="form-control form-control-sm" value="{{ auth()->user()->email }}" disabled>
            </div>

            <div class="col-md-6 form-group">
                <label class="form-label small">Phone Number<span class="text-danger">*</span></label>
                <input type="tel" class="form-control form-control-sm @error('phone_number') is-invalid @enderror" 
                    name="phone_number" 
                    pattern="[0-9]{10,20}" 
                    placeholder="e.g., 0712345678" 
                    value="{{ old('phone_number', auth()->user()->personalInfo->phone_number ?? '') }}" 
                    required
                    title="Please enter a valid phone number (10-20 digits)">
                @error('phone_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Enter a valid phone number (10-20 digits)</small>
            </div>

            <div class="col-md-6 form-group">
                <label class="form-label small">Gender<span class="text-danger">*</span></label>
                <select class="form-select form-select-sm select2 @error('gender') is-invalid @enderror" 
                    name="gender" 
                    required
                    data-placeholder="Select gender">
                    <option value=""></option>
                    <option value="male" {{ old('gender', auth()->user()->personalInfo->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender', auth()->user()->personalInfo->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                </select>
                @error('gender')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 form-group">
                <label class="form-label small">Date of Birth<span class="text-danger">*</span></label>
                <input type="date" class="form-control form-control-sm @error('date_of_birth') is-invalid @enderror" 
                    name="date_of_birth" 
                    value="{{ old('date_of_birth', auth()->user()->personalInfo->date_of_birth ?? '') }}" 
                    required
                    max="{{ date('Y-m-d', strtotime('-18 years')) }}"
                    title="You must be at least 18 years old">
                @error('date_of_birth')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">You must be at least 18 years old</small>
            </div>

            <div class="col-md-6">
                <label class="form-label small">Do you have any disability?<span class="text-danger">*</span></label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="disability_status" id="disability_no" value="0" {{ old('disability_status', auth()->user()->personalInfo->disability_status ?? 0) == 0 ? 'checked' : '' }} required>
                    <label class="form-check-label" for="disability_no">No</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="disability_status" id="disability_yes" value="1" {{ old('disability_status', auth()->user()->personalInfo->disability_status ?? 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="disability_yes">Yes</label>
                </div>
                @error('disability_status')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6" id="disability_type_container" style="display: none;">
                <label class="form-label small">Type of Disability</label>
                <select class="form-select form-select-sm" name="disability_type">
                    <option value="">Select disability type</option>
                    <option value="visual" {{ old('disability_type', auth()->user()->personalInfo->disability_type ?? '') == 'visual' ? 'selected' : '' }}>Visual Impairment</option>
                    <option value="hearing" {{ old('disability_type', auth()->user()->personalInfo->disability_type ?? '') == 'hearing' ? 'selected' : '' }}>Hearing Impairment</option>
                    <option value="physical" {{ old('disability_type', auth()->user()->personalInfo->disability_type ?? '') == 'physical' ? 'selected' : '' }}>Physical Disability</option>
                    <option value="speech" {{ old('disability_type', auth()->user()->personalInfo->disability_type ?? '') == 'speech' ? 'selected' : '' }}>Speech Impairment</option>
                    <option value="intellectual" {{ old('disability_type', auth()->user()->personalInfo->disability_type ?? '') == 'intellectual' ? 'selected' : '' }}>Intellectual Disability</option>
                    <option value="learning" {{ old('disability_type', auth()->user()->personalInfo->disability_type ?? '') == 'learning' ? 'selected' : '' }}>Learning Disability</option>
                    <option value="multiple" {{ old('disability_type', auth()->user()->personalInfo->disability_type ?? '') == 'multiple' ? 'selected' : '' }}>Multiple Disabilities</option>
                </select>
                @error('disability_type')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>
            </div>
    </div>
</div>

@push('scripts')
<script>
    // Show/hide disability type field based on disability status
    $(document).ready(function() {
        function toggleDisabilityType() {
            if ($('#disability_yes').is(':checked')) {
                $('#disability_type_container').show();
            } else {
                $('#disability_type_container').hide();
            }
        }

        // Initial state
        toggleDisabilityType();

        // On change
        $('input[name="disability_status"]').change(function() {
            toggleDisabilityType();
        });
    });
</script>
@endpush
