// Training Phase Management
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Form validation
    const phaseForm = document.getElementById('phase-form');
    
    // Pre-fill form fields from training info
    function prefillPhaseForm() {
        const title = $('#training-title').text();
        const orgId = $('#training-org').data('org-id');
        const educationLevel = $('#education-level').text();

        $('input[name="title"]').val(title);
        $('select[name="organization_id"]').val(orgId).trigger('change');
        $('input[name="education_level"]').val(educationLevel);
    }

    // Handle phase modal show
    $('#phase-modal').on('show.bs.modal', function () {
        prefillPhaseForm();
    });

    // Handle phase form submission
    $('#phase-form').on('submit', function(e) {
        e.preventDefault();
        
        if (!phaseForm.checkValidity()) {
            e.stopPropagation();
            $(phaseForm).addClass('was-validated');
            return;
        }

        const formData = new FormData(phaseForm);
        const trainingCode = $('#training-code').text();

        // Add training code to form data
        formData.append('training_code', trainingCode);

        $.ajax({
            url: '/training/phase/create',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Training phase created successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Close modal and refresh page
                        $('#phase-modal').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'Failed to create training phase.',
                    });
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || {};
                Object.keys(errors).forEach(field => {
                    const input = phaseForm.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.setCustomValidity(errors[field][0]);
                        input.nextElementSibling.textContent = errors[field][0];
                    }
                });
                $(phaseForm).addClass('was-validated');
            }
        });
    });

    // Reset validation on input
    $(phaseForm).find('input, select, textarea').on('input change', function() {
        this.setCustomValidity('');
        $(this).removeClass('is-invalid');
    });
});
