// Participant Assignment Management
$(document).ready(function() {
    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const trainingCode = $('#training-code-input').val();
    let selectedTeachers = new Set();

    // Initialize DataTable for participants
    const participantsTable = $('#participantsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: `/trainings/${trainingCode}/participants/data`,
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.error('DataTables error:', xhr.responseText);
                $('#participantsTable tbody').html(`
                    <tr>
                        <td colspan="5" class="text-center text-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            ${xhr.responseJSON?.message || 'Error loading participants data'}
                        </td>
                    </tr>
                `);
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'type', name: 'type' },
            { 
                data: 'attendance_status',
                name: 'attendance_status',
                render: function(data) {
                    const statusClass = {
                        'attended': 'success',
                        'not_started': 'warning',
                        'in_progress': 'info',
                        'completed': 'primary',
                        'absent': 'danger'
                    }[data] || 'secondary';
                    
                    const statusText = data ? data.replace(/_/g, ' ') : 'Not Set';
                    return `<span class="badge bg-${statusClass}">${statusText}</span>`;
                }
            },
            { 
                data: 'report_file',
                name: 'report_file',
                render: function(data, type, row) {
                    if (row.attendance_status === 'attended' && data) {
                        return `
                            <a href="/trainings/${trainingCode}/participants/${row.participant_id}/report" 
                               class="btn btn-sm btn-outline-info" 
                               title="Download Report">
                               <i class="fas fa-download"></i>
                            </a>`;
                    }
                    return '<small class="text-muted">-</small>';
                }
            }
        ],
        order: [[1, 'asc']],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        language: {
            emptyTable: "No participants assigned yet",
            processing: `<div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>`
        }
    });

    // Initialize Select2 for region filter
    $('.select2-region').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: 'Select a region to filter',
        allowClear: true,
        dropdownParent: $('#participants-modal')
    });

    // Load teachers when region changes
    $('#region_filter').on('change', function() {
        const regionId = $(this).val();
        $('#teachers-content').html('<div class="text-center py-3"><div class="spinner-border text-primary" role="status"></div></div>');
        
        $.ajax({
            url: `/qualified-teachers/${trainingCode}`,
            method: 'GET',
            data: { region_id: regionId },
            success: function(response) {
                if (response.status === 'success') {
                    renderTeachers(response.data);
                } else {
                    $('#teachers-content').html('<div class="alert alert-danger">Failed to load teachers</div>');
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Error loading teachers.';
                $('#teachers-content').html(`<div class="alert alert-danger">${message}</div>`);
            }
        });
    });

    // Render teachers list
    function renderTeachers(data) {
        if (!data.teachers || data.teachers.length === 0) {
            $('#teachers-content').html(`
                <div class="text-center text-muted py-4">
                    <i class="fas fa-search fa-2x mb-2"></i>
                    <p class="mb-0">No teachers found for the selected criteria</p>
                    ${$('#region_filter').val() ? '<small>Try selecting a different region or view all regions</small>' : ''}
                </div>
            `);
            return;
        }

        const content = data.teachers.map(teacher => `
            <div class="teacher-item p-2 border-bottom">
                <div class="d-flex">
                    <div class="form-check me-3">
                        <input type="checkbox" class="form-check-input teacher-checkbox" 
                               value="${teacher.id}" 
                               id="teacher-${teacher.id}"
                               ${selectedTeachers.has(teacher.id) ? 'checked' : ''}>
                        <label class="form-check-label" for="teacher-${teacher.id}">
                            ${teacher.name}
                        </label>
                    </div>
                    <div class="flex-grow-1 text-start">
                        <small class="text-muted d-block">${teacher.registration_number}</small>
                        <small class="d-block text-muted mt-1">
                            <i class="fas fa-school me-1"></i>${teacher.current_school}
                        </small>
                        <small class="d-block text-muted">
                            <i class="fas fa-map-marker-alt me-1"></i>${teacher.ward_name}, ${teacher.district_name}, ${teacher.region_name}
                        </small>
                        <small class="d-block text-muted">
                            <i class="fas fa-graduation-cap me-1"></i>${teacher.education_level} | 
                            <i class="fas fa-clock me-1"></i>${teacher.years_of_experience} years experience
                        </small>
                    </div>
                </div>
            </div>
        `).join('');

        $('#teachers-content').html(content);
        updateTeacherCount();
        updateSelectAllState();
    }

    // Load teachers initially when modal opens
    $('#participants-modal').on('show.bs.modal', function() {
        $('#region_filter').trigger('change');
        loadQualifiedFacilitators();
    });

    // Handle select all checkbox
    $('#select-all').click(function() {
        const isChecked = $(this).prop('checked');
        $('#teachers-content input[type="checkbox"]').prop('checked', isChecked);
        updateTeacherCount();
    });

    // Handle individual teacher checkbox clicks
    $(document).on('click', '#teachers-content input[type="checkbox"]', function() {
        updateTeacherCount();
        updateSelectAllState();
    });

    // Function to update teacher count
    function updateTeacherCount() {
        const checkedCount = $('#teachers-content input[type="checkbox"]:checked').length;
        $('#total-teachers').text(checkedCount);
    }

    // Function to update select all checkbox state
    function updateSelectAllState() {
        const totalCheckboxes = $('#teachers-content input[type="checkbox"]').length;
        const checkedCount = $('#teachers-content input[type="checkbox"]:checked').length;
        
        if (checkedCount === 0) {
            $('#select-all').prop('checked', false);
            $('#select-all').prop('indeterminate', false);
        } else if (checkedCount === totalCheckboxes) {
            $('#select-all').prop('checked', true);
            $('#select-all').prop('indeterminate', false);
        } else {
            $('#select-all').prop('checked', false);
            $('#select-all').prop('indeterminate', true);
        }
    }

    // Load qualified facilitators
    function loadQualifiedFacilitators() {
        $.ajax({
            url: `/qualified-facilitators/${trainingCode}`,
            method: 'GET',
            success: function(response) {
                if (response.status === 'success' && response.data) {
                    updateFacilitatorsList(response.data);
                    $('#total-facilitators').text(response.data.length);
                } else {
                    console.error('Invalid response format:', response);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load facilitators data'
                    });
                }
            },
            error: function(xhr) {
                console.error('Error loading facilitators:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load facilitators. Please try again.'
                });
            }
        });
    }

    // Update facilitators list
    function updateFacilitatorsList(facilitators) {
        const facilitatorsContent = $('#facilitators-content');
        facilitatorsContent.empty();

        if (!facilitators || facilitators.length === 0) {
            facilitatorsContent.append('<div class="text-center text-muted">No facilitators available</div>');
            return;
        }

        facilitators.forEach(facilitator => {
            let qualifications = {};
            if (facilitator.facilitator && facilitator.facilitator.qualifications) {
                try {
                    qualifications = typeof facilitator.facilitator.qualifications === 'string' ? 
                        JSON.parse(facilitator.facilitator.qualifications) : 
                        facilitator.facilitator.qualifications;
                } catch (e) {
                    console.error('Error parsing qualifications:', e);
                }
            }

            const listItem = $(`
                <div class="facilitator-item" data-name="${facilitator.name.toLowerCase()}" data-specialization="${(facilitator.facilitator ? facilitator.facilitator.specialization : 'N/A').toLowerCase()}">
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-white rounded">
                        <div class="form-check">
                            <input class="form-check-input facilitator-checkbox" type="checkbox" 
                                   value="${facilitator.user_id}" id="facilitator-${facilitator.user_id}">
                            <label class="form-check-label" for="facilitator-${facilitator.user_id}">
                                ${facilitator.name}
                            </label>
                        </div>
                        <div>
                            <span class="badge bg-secondary">${facilitator.facilitator ? facilitator.facilitator.specialization : 'N/A'}</span>
                            <small class="text-muted ms-2">Exp: ${qualifications.experience_years || 'N/A'} yrs</small>
                        </div>
                    </div>
                </div>
            `);
            facilitatorsContent.append(listItem);
        });

        // Initial count
        $('#total-facilitators').text(facilitators.length);
    }

    // Handle facilitator search
    $('#facilitator-search').on('input', function() {
        const searchTerm = $(this).val().toLowerCase().trim();
        
        $('.facilitator-item').each(function() {
            const name = $(this).data('name');
            const specialization = $(this).data('specialization');
            if (name.includes(searchTerm) || specialization.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });

        // Update visible count
        const visibleCount = $('.facilitator-item:visible').length;
        $('#total-facilitators').text(visibleCount);

        // Update select all checkbox
        const visibleChecked = $('.facilitator-item:visible .facilitator-checkbox:checked').length;
        const visibleTotal = $('.facilitator-item:visible .facilitator-checkbox').length;
        $('#select-all-facilitators').prop('checked', visibleTotal > 0 && visibleChecked === visibleTotal);
    });

    // Handle select all facilitators
    $('#select-all-facilitators').change(function() {
        const isChecked = $(this).prop('checked');
        $('.facilitator-item:visible .facilitator-checkbox').prop('checked', isChecked);
    });

    // Handle form submission
    $('#participants-form').on('submit', function(e) {
        e.preventDefault();
        
        const maxParticipants = parseInt($('#max-participants').val()) || 0;
        
        // Get selected teachers and facilitators
        const selectedTeachers = $('.teacher-checkbox:checked').map(function() {
            return $(this).val();
        }).get();
        
        const selectedFacilitators = $('.facilitator-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        // Validate selections
        if (selectedTeachers.length === 0 && selectedFacilitators.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'No Participants Selected',
                text: 'Please select at least one teacher or facilitator to assign.'
            });
            return;
        }

        // Validate maximum participants for teachers
        if (selectedTeachers.length > maxParticipants) {
            Swal.fire({
                icon: 'error',
                title: 'Too Many Teachers Selected',
                text: `Maximum allowed participants is ${maxParticipants}, but you selected ${selectedTeachers.length} teachers.`
            });
            return;
        }

        // Show confirmation dialog
        Swal.fire({
            title: 'Confirm Assignment',
            html: `
                <p>You are about to assign:</p>
                <ul>
                    <li><strong>${selectedTeachers.length}</strong> teachers</li>
                    <li><strong>${selectedFacilitators.length}</strong> CPD facilitators</li>
                </ul>
                <p>to training: <strong>${trainingCode}</strong></p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Assign',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Assigning Participants',
                    html: 'Please wait...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Prepare data for submission
                const formData = {
                    training_code: trainingCode,
                    teachers: selectedTeachers,
                    facilitators: selectedFacilitators,
                    _token: $('meta[name="csrf-token"]').attr('content')
                };

                // Submit assignment
                $.ajax({
                    url: '/assign-training-participants',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Assignment Successful',
                                html: `
                                    <p>Successfully assigned:</p>
                                    <ul>
                                        <li>${selectedTeachers.length} teachers</li>
                                        <li>${selectedFacilitators.length} CPD facilitators</li>
                                    </ul>
                                    <p>to training: ${trainingCode}</p>
                                `,
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                // Close modal and refresh table
                                $('#participants-modal').modal('hide');
                                participantsTable.ajax.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Assignment Failed',
                                text: response.message || 'Failed to assign participants. Please try again.'
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to assign participants. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Assignment Failed',
                            text: errorMessage
                        });
                    }
                });
            }
        });
    });
});
