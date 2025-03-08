$(document).ready(function() {
    // Handle form submission
    $('#training-form').on('submit', function(e) {
        e.preventDefault();
        
        // Remove previous validation errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Create form data manually instead of using FormData constructor
        const formData = new FormData();
        
        // Add CSRF token and method
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        
        // Get all form values
        const formValues = {
            title: $('input[name="title"]').val(),
            organization_id: $('select[name="organization_id"]').val(),
            education_level: $('select[name="education_level"]').val(),
            training_phase: $('select[name="training_phase"]').val(),
            max_participants: $('input[name="max_participants"]').val(),
            description: $('textarea[name="description"]').val(),
            venue_name: $('input[name="venue_name"]').val(),
            duration_days: $('input[name="duration_days"]').val(),
            region_id: $('select[name="region_id"]').val(),
            district_id: $('select[name="district_id"]').val(),
            ward_id: $('select[name="ward_id"]').val(),
            start_date: $('input[name="start_date"]').val(),
            end_date: $('input[name="end_date"]').val(),
            start_time: $('input[name="start_time"]').val()
        };
        
        // Format dates after validation
        if (formValues.start_date) {
            formValues.start_date = moment(formValues.start_date, 'YYYY-MM-DD').format('YYYY-MM-DD');
        }
        if (formValues.end_date) {
            formValues.end_date = moment(formValues.end_date, 'YYYY-MM-DD').format('YYYY-MM-DD');
        }
        if (formValues.start_time) {
            formValues.start_time = moment(formValues.start_time, 'HH:mm').format('HH:mm');
        }
        
        // Debug log raw values
        console.log('Raw Form Values:', {
            ...formValues,
            subjects: $('select[name="subjects[]"]').val()
        });
        
        // Validate required fields
        let hasError = false;
        Object.entries(formValues).forEach(([key, value]) => {
            const field = $(`[name="${key}"]`);
            if (field.prop('required') && (!value || (Array.isArray(value) && value.length === 0))) {
                field.addClass('is-invalid');
                field.next('.invalid-feedback').text(`The ${key.replace('_', ' ')} field is required`);
                hasError = true;
            }
        });
        
        if (hasError) {
            return;
        }
        
        // Add all form fields
        Object.entries(formValues).forEach(([key, value]) => {
            if (value) {
                formData.append(key, value);
            }
        });

        // Add subjects array
        const subjects = $('select[name="subjects[]"]').val();
        if (subjects && subjects.length > 0) {
            subjects.forEach((subjectId, index) => {
                formData.append(`subjects[${index}]`, subjectId);
            });
        }

        // Get training code if editing
        const trainingCode = $('input[name="training_code"]').val();
        const isEditing = !!trainingCode;
        
        // Set up request URL and method
        let url = '/admin/trainings';
        let method = 'POST';
        
        if (isEditing) {
            url = `/admin/trainings/${trainingCode}/update`;
            method = 'PUT';
            formData.append('_method', 'PUT');
            console.log(trainingCode, 'Editing training:', url);
        }
        
        // Debug log final form data
        const formDataObj = {};
        for (let [key, value] of formData.entries()) {
            formDataObj[key] = value;
        }
        console.log('Final Form Data:', {
            isEditing,
            method,
            url,
            trainingCode,
            formData: formDataObj
        });

        // Show loading
        Swal.fire({
            title: 'Processing...',
            text: `${isEditing ? 'Updating' : 'Creating'} training`,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Send request
        $.ajax({
            url: url,
            method: method,
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-HTTP-Method-Override': method
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: isEditing ? 'Training updated successfully!' : 'Training added successfully!',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    // Refresh table and close modal
                    $('#trainings-table').DataTable().ajax.reload();
                    $('#training-modal').modal('hide');
                });
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred while processing your request.';
                
                // Log the error details
                console.error('AJAX Error:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    response: xhr.responseJSON
                });
                
                if (xhr.status === 422) {
                    // Handle validation errors
                    const errors = xhr.responseJSON.errors;
                    errorMessage = 'Validation failed:';
                    Object.entries(errors).forEach(([field, messages]) => {
                        // Add field error to message
                        errorMessage += `\n- ${field}: ${messages.join(', ')}`;
                        
                        // Show error on form field
                        const input = $(`[name="${field}"]`);
                        if (input.length) {
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').text(messages[0]);
                        }
                        
                        // Log the field value for debugging
                        console.log(`Field ${field}:`, {
                            value: formData.get(field),
                            element: $(`[name="${field}"]`).length ? 'found' : 'not found',
                            elementValue: $(`[name="${field}"]`).val()
                        });
                    });
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: errorMessage.replace(/\n/g, '<br>'),
                    customClass: {
                        content: 'text-start'
                    }
                });
            }
        });
    });

    // Reset form when modal is closed
    $('#training-modal').on('hidden.bs.modal', function () {
        // Reset form
        $('#training-form')[0].reset();
        
        // Reset select2 fields
        $('select[name="organization_id"]').val(null).trigger('change');
        $('select[name="education_level"]').val(null).trigger('change');
        $('select[name="training_phase"]').val(null).trigger('change');
        $('select[name="subjects[]"]').val(null).trigger('change');
        
        // Reset location fields
        $('#region').val(null).trigger('change');
        $('#district').prop('disabled', true).empty().append('<option value="">Select District</option>');
        $('#ward').prop('disabled', true).empty().append('<option value="">Select Ward</option>');
        
        // Remove validation errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Reset title
        $('#training-modal .modal-title').text('Add Training');
        
        // Remove training code if exists
        $('input[name="training_code"]').remove();
    });
});
