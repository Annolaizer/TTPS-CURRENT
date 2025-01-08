@extends('admin.master_layout.index')

@section('content')
<main class="main-content">
    <div class="container-fluid py-4">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Edit Teacher Information</h3>
                            </div>
                            <form action="{{ route('admin.teachers.update', $teacher->teacher_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="registration_number">Registration Number</label>
                                                <input type="text" 
                                                    class="form-control @error('registration_number') is-invalid @enderror" 
                                                    id="registration_number" 
                                                    name="registration_number" 
                                                    value="{{ old('registration_number', $teacher->registration_number) }}"
                                                    required>
                                                @error('registration_number')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="education_level">Education Level</label>
                                                <select class="form-control @error('education_level') is-invalid @enderror" 
                                                        id="education_level" 
                                                        name="education_level" 
                                                        required>
                                                    <option value="">Select Education Level</option>
                                                    <option value="Certificate" {{ old('education_level', $teacher->education_level) === 'Certificate' ? 'selected' : '' }}>Certificate</option>
                                                    <option value="Diploma" {{ old('education_level', $teacher->education_level) === 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                                    <option value="Bachelor" {{ old('education_level', $teacher->education_level) === 'Bachelor' ? 'selected' : '' }}>Bachelor's Degree</option>
                                                    <option value="Masters" {{ old('education_level', $teacher->education_level) === 'Masters' ? 'selected' : '' }}>Master's Degree</option>
                                                    <option value="PhD" {{ old('education_level', $teacher->education_level) === 'PhD' ? 'selected' : '' }}>PhD</option>
                                                </select>
                                                @error('education_level')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="teaching_subject">Teaching Subject</label>
                                                <input type="text" 
                                                    class="form-control @error('teaching_subject') is-invalid @enderror" 
                                                    id="teaching_subject" 
                                                    name="teaching_subject" 
                                                    value="{{ old('teaching_subject', $teacher->teaching_subject) }}"
                                                    required>
                                                @error('teaching_subject')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="years_of_experience">Years of Experience</label>
                                                <input type="number" 
                                                    class="form-control @error('years_of_experience') is-invalid @enderror" 
                                                    id="years_of_experience" 
                                                    name="years_of_experience" 
                                                    value="{{ old('years_of_experience', $teacher->years_of_experience) }}"
                                                    required>
                                                @error('years_of_experience')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="current_school">Current School</label>
                                                <input type="text" 
                                                    class="form-control @error('current_school') is-invalid @enderror" 
                                                    id="current_school" 
                                                    name="current_school" 
                                                    value="{{ old('current_school', $teacher->current_school) }}"
                                                    required>
                                                @error('current_school')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="ward_id">Ward</label>
                                                <select class="form-control @error('ward_id') is-invalid @enderror" 
                                                        id="ward_id" 
                                                        name="ward_id" 
                                                        required>
                                                    <option value="">Select Ward</option>
                                                    <!-- Add ward options dynamically -->
                                                </select>
                                                @error('ward_id')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Update Teacher</button>
                                    <a href="{{ route('admin.teachers.show', $teacher->teacher_id) }}" class="btn btn-default float-right">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Add ward selection logic here
        // You can use the existing API endpoints for location data
    });
</script>
@endpush
