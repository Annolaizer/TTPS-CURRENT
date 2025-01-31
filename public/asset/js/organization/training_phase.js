// Training Phase Management Module
const TrainingPhaseModule = (function() {
    // Cache DOM elements
    const elements = {
        form: document.getElementById('phase-form'),
        modal: $('#phase-modal'),
        regionSelect: $('select[name="region_id"]'),
        districtSelect: $('select[name="district_id"]'),
        wardSelect: $('select[name="ward_id"]'),
        startDateInput: $('input[name="start_date"]'),
        endDateInput: $('input[name="end_date"]'),
        titleInput: $('input[name="title"]'),
        educationLevelInput: $('input[name="education_level"]'),
        startTimeInput: $('input[name="start_time"]')
    };

    // Constants
    const ENDPOINTS = {
        CREATE_PHASE: `/trainings/${$('input[name="training_code"]').val()}/update-phase`,
        GET_DISTRICTS: (regionId) => `/api/districts/${regionId}`,
        GET_WARDS: (districtId) => `/api/wards/${districtId}`
    };

    // Initialize module
    function init() {
        initializeSelect2();
        bindEvents();
    }

    // Initialize Select2 plugin
    function initializeSelect2() {
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%',
            dropdownParent: elements.modal
        });
    }

    // Bind all event listeners
    function bindEvents() {
        elements.modal.on('show.bs.modal', handleModalShow);
        elements.form.addEventListener('submit', handleFormSubmit);
        elements.regionSelect.on('change', handleRegionChange);
        elements.districtSelect.on('change', handleDistrictChange);
        elements.startDateInput.on('change', handleDateValidation);
        elements.endDateInput.on('change', handleDateValidation);
        elements.startTimeInput.on('change', handleTimeValidation);
        $(elements.form).find('input, select, textarea').on('input change', resetValidation);
    }

    // Pre-fill form with training info
    function prefillPhaseForm() {
        const trainingInfo = {
            title: $('#training-title').text(),
            code: $('#training-code').text(),
            description: $('#training-description').text(),
            educationLevel: $('#education-level').text(),
            subjects: JSON.parse($('#training-subjects').val() || '[]')
        };

        elements.titleInput.val(trainingInfo.title);
        $('input[name="training_code"]').val(trainingInfo.code);
        $('textarea[name="training_description"]').val(trainingInfo.description);
        elements.educationLevelInput.val(trainingInfo.educationLevel);

        // Pre-select subjects
        const subjectsSelect = $('select[name="subjects[]"]');
        subjectsSelect.val(trainingInfo.subjects).trigger('change');
    }

    // Event Handlers
    function handleModalShow() {
        prefillPhaseForm();
    }

    function handleRegionChange() {
        const regionId = $(this).val();
        loadLocationData(regionId, elements.districtSelect, elements.wardSelect, ENDPOINTS.GET_DISTRICTS(regionId), 'districts', 'district');
    }

    function handleDistrictChange() {
        const districtId = $(this).val();
        loadLocationData(districtId, elements.wardSelect, null, ENDPOINTS.GET_WARDS(districtId), 'wards', 'ward');
    }

    function handleDateValidation() {
        const startDate = new Date(elements.startDateInput.val());
        const endDate = new Date(elements.endDateInput.val());
        
        elements.endDateInput.attr('min', elements.startDateInput.val());
        
        if (elements.endDateInput.val() && endDate < startDate) {
            elements.endDateInput.val(elements.startDateInput.val());
        }

        // Calculate duration if both dates are valid
        if (elements.startDateInput.val() && elements.endDateInput.val()) {
            const diffTime = Math.abs(endDate - startDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // Add 1 to include both start and end dates
            $('input[name="duration_days"]').val(diffDays);
        }
    }

    function handleTimeValidation() {
        const timeValue = elements.startTimeInput.val();
        if (timeValue) {
            const [hours, minutes] = timeValue.split(':');
            if (hours < 6 || hours > 18) {
                showAlert('warning', 'Please select a time between 6:00 AM and 6:00 PM');
                elements.startTimeInput.val('08:00');
            }
        }
    }

    function handleFormSubmit(e) {
        e.preventDefault();

        if (!elements.form.checkValidity()) {
            e.stopPropagation();
            elements.form.classList.add('was-validated');
            return;
        }

        const formData = new FormData(elements.form);
        
        $.ajax({
            url: ENDPOINTS.CREATE_PHASE,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    elements.modal.modal('hide');
                    showSuccessMessage();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showAlert('error', response.message || 'Failed to create training phase');
                }
            },
            error: handleFormError
        });
    }

    // Helper Functions
    function loadLocationData(parentId, targetSelect, childSelect, url, dataKey, itemType) {
        if (!parentId) {
            resetLocationSelects(targetSelect, childSelect);
            return;
        }

        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response.status === 'success' && response[dataKey]) {
                    updateLocationSelect(response[dataKey], targetSelect, itemType);
                    if (childSelect) {
                        resetLocationSelects(childSelect);
                    }
                } else {
                    showAlert('error', `Failed to load ${itemType} data`);
                }
            },
            error: function() {
                showAlert('error', `Failed to fetch ${itemType} data`);
            }
        });
    }

    function updateLocationSelect(items, select, itemType) {
        select.empty().append(`<option value="">Select ${itemType}</option>`);
        items.forEach(item => {
            select.append(`<option value="${item.id}">${item.name}</option>`);
        });
    }

    function resetLocationSelects(...selects) {
        selects.forEach(select => {
            if (select) {
                select.empty().append('<option value="">Select location</option>');
            }
        });
    }

    function handleFormError(xhr) {
        if (xhr.status === 422) {
            const errors = xhr.responseJSON.errors;
            Object.keys(errors).forEach(field => {
                const input = elements.form.querySelector(`[name="${field}"]`);
                if (input) {
                    input.setCustomValidity(errors[field][0]);
                    input.classList.add('is-invalid');
                    const feedback = input.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.textContent = errors[field][0];
                    }
                }
            });
        } else {
            showAlert('error', xhr.responseJSON?.message || 'An error occurred while processing your request');
        }
    }

    function resetValidation() {
        this.setCustomValidity('');
        this.classList.remove('is-invalid');
    }

    function showSuccessMessage() {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Training phase has been created successfully.',
            timer: 1500,
            showConfirmButton: false
        });
    }

    function showAlert(type, message) {
        Swal.fire({
            icon: type,
            title: type.charAt(0).toUpperCase() + type.slice(1),
            text: message,
            confirmButtonText: 'OK'
        });
    }

    function showToast(type, message) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        Toast.fire({
            icon: type,
            title: message
        });
    }

    // Public API
    return { init };
})();

// Initialize when document is ready
$(document).ready(function() {
    TrainingPhaseModule.init();
});
