// Training Phase Management
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%',
        dropdownParent: $('#phase-modal')
    });

    // Pre-fill form fields from training info
    function prefillPhaseForm() {
        const title = $('#training-title').text();
        const educationLevel = $('#education-level').text();
        const trainingCode = $('#training-code').text();

        $('input[name="title"]').val(title);
        $('input[name="training_code"]').val(trainingCode);
        $('input[name="education_level"]').val(educationLevel);
    }

    // Handle phase modal show
    $('#phase-modal').on('show.bs.modal', function () {
        prefillPhaseForm();
    });

    // Handle region change
    $('select[name="region_id"]').on('change', function() {
        const regionId = $(this).val();
        const districtSelect = $('select[name="district_id"]');
        const wardSelect = $('select[name="ward_id"]');
        
        if (regionId) {
            $.ajax({
                url: `/admin/regions/${regionId}/districts`,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === 'success') {
                        districtSelect.empty().append('<option value="">Select District</option>');
                        response.districts.forEach(district => {
                            districtSelect.append(`<option value="${district.district_id}">${district.district_name}</option>`);
                        });
                        districtSelect.prop('disabled', false).trigger('change');
                        wardSelect.empty().append('<option value="">Select Ward</option>').prop('disabled', true);
                    } else {
                        showAlert('error', response.message || 'Failed to load districts');
                    }
                },
                error: function(xhr) {
                    showAlert('error', 'Failed to load districts. Please try again.');
                    console.error('District loading error:', xhr);
                }
            });
        } else {
            districtSelect.empty().append('<option value="">Select District</option>').prop('disabled', true);
            wardSelect.empty().append('<option value="">Select Ward</option>').prop('disabled', true);
        }
    });

    // Handle district change
    $('select[name="district_id"]').on('change', function() {
        const districtId = $(this).val();
        const wardSelect = $('select[name="ward_id"]');
        
        if (districtId) {
            $.ajax({
                url: `/admin/districts/${districtId}/wards`,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === 'success') {
                        wardSelect.empty().append('<option value="">Select Ward</option>');
                        response.wards.forEach(ward => {
                            wardSelect.append(`<option value="${ward.ward_id}">${ward.ward_name}</option>`);
                        });
                        wardSelect.prop('disabled', false).trigger('change');
                    } else {
                        showAlert('error', response.message || 'Failed to load wards');
                    }
                },
                error: function(xhr) {
                    showAlert('error', 'Failed to load wards. Please try again.');
                    console.error('Ward loading error:', xhr);
                }
            });
        } else {
            wardSelect.empty().append('<option value="">Select Ward</option>').prop('disabled', true);
        }
    });

    // Handle date validation
    $('input[name="start_date"]').on('change', function() {
        const startDate = $(this).val();
        const endDateInput = $('input[name="end_date"]');
        endDateInput.attr('min', startDate);
        if (endDateInput.val() && endDateInput.val() < startDate) {
            endDateInput.val(startDate);
        }
    });

    // Handle phase form submission
    const phaseForm = document.getElementById('phase-form');
    $(phaseForm).on('submit', function(e) {
        e.preventDefault();
        
        if (!this.checkValidity()) {
            e.stopPropagation();
            $(this).addClass('was-validated');
            return;
        }

        const formData = new FormData(this);
        const trainingCode = $('#training-code').text();
        
        $.ajax({
            url: '/training/phase/create',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status === 'success') {
                    showAlert('success', 'Training phase created successfully');
                    $('#phase-modal').modal('hide');
                    location.reload();
                } else {
                    showAlert('error', response.message || 'Failed to create training phase');
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || {};
                let hasFieldErrors = false;
                
                Object.keys(errors).forEach(field => {
                    const input = phaseForm.querySelector(`[name="${field}"]`);
                    if (input) {
                        hasFieldErrors = true;
                        input.setCustomValidity(errors[field][0]);
                        const feedback = input.nextElementSibling;
                        if (feedback && feedback.classList.contains('invalid-feedback')) {
                            feedback.textContent = errors[field][0];
                        }
                    }
                });

                if (!hasFieldErrors) {
                    showAlert('error', xhr.responseJSON?.message || 'Failed to create training phase. Please try again.');
                }
                
                $(phaseForm).addClass('was-validated');
            }
        });
    });

    // Reset validation on input
    $(phaseForm).find('input, select, textarea').on('input change', function() {
        this.setCustomValidity('');
        $(this).removeClass('is-invalid');
    });

    // Reset form when modal is closed
    $('#phase-modal').on('hidden.bs.modal', function() {
        const form = $('#phase-form');
        form.removeClass('was-validated');
        form[0].reset();
        $('select[name="district_id"]').empty().append('<option value="">Select District</option>').prop('disabled', true);
        $('select[name="ward_id"]').empty().append('<option value="">Select Ward</option>').prop('disabled', true);
        $('.select2').val(null).trigger('change');
    });

    // Helper function to show alerts
    function showAlert(type, message) {
        Swal.fire({
            icon: type,
            title: type === 'success' ? 'Success!' : 'Error!',
            text: message,
            timer: type === 'success' ? 2000 : undefined,
            showConfirmButton: type !== 'success'
        });
    }
});
