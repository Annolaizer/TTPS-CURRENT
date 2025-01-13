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
        const startTime = elements.startTimeInput.val();
        const endTime = elements.endDateInput.val();
        if (endTime && startTime) {
            const startDate = new Date(elements.startDateInput.val());
            const endDate = new Date(endTime);
            const startTimeParts = startTime.split(':');
            const startDateTime = new Date(startDate.getFullYear(), startDate.getMonth(), startDate.getDate(), startTimeParts[0], startTimeParts[1]);
            const endDateTime = new Date(endDate.getFullYear(), endDate.getMonth(), endDate.getDate(), 23, 59);
            if (startDateTime > endDateTime) {
                elements.startTimeInput.val('00:00');
            }
        }
    }

    async function handleFormSubmit(e) {
        e.preventDefault();
        
        const form = $(e.target);
        if (!form[0].checkValidity()) {
            e.stopPropagation();
            form.addClass('was-validated');
            return;
        }

        const formData = {
            training_code: form.find('input[name="training_code"]').val(),
            title: form.find('input[name="title"]').val(),
            max_participants: form.find('input[name="max_participants"]').val(),
            start_date: form.find('input[name="start_date"]').val(),
            end_date: form.find('input[name="end_date"]').val(),
            start_time: form.find('input[name="start_time"]').val(),
            description: form.find('input[name="description"]').val(), 
            venue_name: form.find('input[name="venue_name"]').val(),
            region_id: form.find('select[name="region_id"]').val(),
            district_id: form.find('select[name="district_id"]').val(),
            ward_id: form.find('select[name="ward_id"]').val(),
            subjects: form.find('select[name="subjects[]"]').val() || []
        };

        $.ajax({
            url: ENDPOINTS.CREATE_PHASE,
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#phase-modal').modal('hide');
                    form[0].reset();
                    form.removeClass('was-validated');
                    showToast('success', 'Training phase created successfully');
                    setTimeout(() => window.location.reload(), 1500);
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Failed to create training phase';
                showToast('error', message);
                console.error('Error creating phase:', xhr.responseJSON);
            }
        });
    }

    // Helper Functions
    async function loadLocationData(parentId, targetSelect, childSelect, url, dataKey, itemType) {
        if (!parentId) {
            resetLocationSelects(targetSelect, childSelect);
            return;
        }

        try {
            const response = await $.ajax({
                url: url,
                method: 'GET',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            if (Array.isArray(response)) {
                updateLocationSelect(response, targetSelect, itemType);
                if (childSelect) {
                    resetLocationSelects(childSelect);
                }
            } else {
                showAlert('error', response.error || `Failed to load ${itemType}s`);
            }
        } catch (error) {
            console.error(`${itemType} loading error:`, error);
            showAlert('error', `Failed to load ${itemType}s. Please try again.`);
        }
    }

    function updateLocationSelect(items, select, itemType) {
        select.empty().append(`<option value="">Select ${itemType.charAt(0).toUpperCase() + itemType.slice(1)}</option>`);
        items.forEach(item => {
            select.append(`<option value="${item[itemType + '_id']}">${item[itemType + '_name']}</option>`);
        });
        select.prop('disabled', false).trigger('change');
    }

    function resetLocationSelects(...selects) {
        selects.filter(Boolean).forEach(select => {
            select.empty().append('<option value="">Select Option</option>').prop('disabled', true);
        });
    }

    function handleFormError(xhr) {
        const errors = xhr.responseJSON?.errors || {};
        let hasFieldErrors = false;

        Object.entries(errors).forEach(([field, [message]]) => {
            const input = elements.form.querySelector(`[name="${field}"]`);
            if (input) {
                hasFieldErrors = true;
                input.setCustomValidity(message);
                const feedback = input.nextElementSibling;
                if (feedback?.classList.contains('invalid-feedback')) {
                    feedback.textContent = message;
                }
            }
        });

        if (!hasFieldErrors) {
            showAlert('error', xhr.responseJSON?.error || 'Failed to create training phase. Please try again.');
        }

        $(elements.form).addClass('was-validated');
    }

    function resetValidation() {
        this.setCustomValidity('');
        $(this).removeClass('is-invalid');
    }

    async function showSuccessMessage() {
        return Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Training phase created successfully.',
            showConfirmButton: false,
            timer: 1500
        });
    }

    function showAlert(type, message) {
        Swal.fire({
            icon: type,
            title: type === 'success' ? 'Success!' : 'Error!',
            text: message,
            timer: type === 'success' ? 2000 : undefined,
            showConfirmButton: type !== 'success'
        });
    }

    function showToast(type, message) {
        Swal.fire({
            icon: type,
            title: type === 'success' ? 'Success!' : 'Error!',
            text: message,
            timer: type === 'success' ? 2000 : undefined,
            showConfirmButton: type !== 'success'
        });
    }

    // Public API
    return { init };
})();

// Initialize when document is ready
$(document).ready(TrainingPhaseModule.init);
