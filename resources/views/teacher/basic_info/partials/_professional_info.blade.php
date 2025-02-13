<!-- Professional Information Section -->
<div class="card mb-4" style="z-index: 999; margin-top: -40px">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h5 class="text-success mb-0">Professional Information</h5>
        <hr>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label small">Registration Number<span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="registration_number" value="{{ old('registration_number', auth()->user()->teacherProfile->registration_number ?? '') }}" required pattern="[A-Za-z0-9]+" title="Please enter a valid registration number">
                @error('registration_number')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <div class="form-text small">Enter your official teacher registration number</div>
            </div>

            <div class="col-md-6">
                <label class="form-label small">Education Level<span class="text-danger">*</span></label>
                <select class="form-select form-select-sm" name="education_level" required>
                    <option value="">Select education level</option>
                    <option value="Certificate" {{ (old('education_level', auth()->user()->teacherProfile->education_level ?? '') == 'Certificate') ? 'selected' : '' }}>Certificate</option>
                    <option value="Diploma" {{ (old('education_level', auth()->user()->teacherProfile->education_level ?? '') == 'Diploma') ? 'selected' : '' }}>Diploma</option>
                    <option value="Bachelor's Degree" {{ (old('education_level', auth()->user()->teacherProfile->education_level ?? '') == "Bachelor's Degree") ? 'selected' : '' }}>Bachelor's Degree</option>
                    <option value="Master's Degree" {{ (old('education_level', auth()->user()->teacherProfile->education_level ?? '') == "Master's Degree") ? 'selected' : '' }}>Master's Degree</option>
                    <option value="PhD" {{ (old('education_level', auth()->user()->teacherProfile->education_level ?? '') == 'PhD') ? 'selected' : '' }}>PhD</option>
                </select>
                @error('education_level')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <div class="form-text small">Select your highest level of education</div>
            </div>

            <div class="col-md-6">
                <label class="form-label small">Teaching Subjects<span class="text-danger">*</span></label>
                <select class="form-select form-select-sm select2" name="teaching_subjects[]" multiple required data-placeholder="Select your teaching subjects">
                    <option value="Mathematics" {{ in_array('Mathematics', old('teaching_subjects', [])) || (isset(auth()->user()->teacherProfile) && in_array('Mathematics', auth()->user()->teacherProfile->teaching_subjects ?? [])) ? 'selected' : '' }}>Mathematics</option>
                    <option value="English" {{ in_array('English', old('teaching_subjects', [])) || (isset(auth()->user()->teacherProfile) && in_array('English', auth()->user()->teacherProfile->teaching_subjects ?? [])) ? 'selected' : '' }}>English</option>
                    <option value="Kiswahili" {{ in_array('Kiswahili', old('teaching_subjects', [])) || (isset(auth()->user()->teacherProfile) && in_array('Kiswahili', auth()->user()->teacherProfile->teaching_subjects ?? [])) ? 'selected' : '' }}>Kiswahili</option>
                    <option value="Science" {{ in_array('Science', old('teaching_subjects', [])) || (isset(auth()->user()->teacherProfile) && in_array('Science', auth()->user()->teacherProfile->teaching_subjects ?? [])) ? 'selected' : '' }}>Science</option>
                    <option value="Social Studies" {{ in_array('Social Studies', old('teaching_subjects', [])) || (isset(auth()->user()->teacherProfile) && in_array('Social Studies', auth()->user()->teacherProfile->teaching_subjects ?? [])) ? 'selected' : '' }}>Social Studies</option>
                    <option value="Religious Education" {{ in_array('Religious Education', old('teaching_subjects', [])) || (isset(auth()->user()->teacherProfile) && in_array('Religious Education', auth()->user()->teacherProfile->teaching_subjects ?? [])) ? 'selected' : '' }}>Religious Education</option>
                </select>
                <div class="form-text small">Select all subjects you are qualified to teach</div>
                @error('teaching_subjects')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                @error('teaching_subjects.*')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label small">Years of Experience<span class="text-danger">*</span></label>
                <input type="number" class="form-control form-control-sm" name="years_of_experience" value="{{ old('years_of_experience', auth()->user()->teacherProfile->years_of_experience ?? '') }}" required min="0" max="50">
                @error('years_of_experience')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <div class="form-text small">Enter your total years of teaching experience (0-50)</div>
            </div>
        </div>
    </div>
</div>
