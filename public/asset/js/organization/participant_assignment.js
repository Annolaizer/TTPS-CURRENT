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
    let searchTimeout;

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
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        responsive: true,
        language: {
            search: '',
            searchPlaceholder: 'Search participants...',
            lengthMenu: '_MENU_ per page',
            info: 'Showing _START_ to _END_ of _TOTAL_ participants',
            infoEmpty: 'No participants found',
            emptyTable: '<div class="text-center p-4">' +
                '<i class="fas fa-users fa-3x text-muted mb-3"></i>' +
                '<p class="text-muted">No participants found</p>' +
                '</div>'
        }
    });

    // Initialize Select2 for region filter
    $('.select2-region').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: 'Select a region to filter',
        allowClear: true,
        dropdownParent: $('#participants-modal'),
        templateResult: function(data) {
            if (!data.id) return data.text;
            return $(`<span><i class="${$(data.element).data('icon')} me-2"></i>${data.text}</span>`);
        },
        templateSelection: function(data) {
            if (!data.id) return data.text;
            return $(`<span><i class="${$(data.element).data('icon')} me-2"></i>${data.text}</span>`);
        }
    });

    // Handle select all checkbox
    $('#select-all').on('change', function() {
        const isChecked = $(this).prop('checked');
        $('.teacher-checkbox').prop('checked', isChecked);
        updateTeacherCount();
    });

    // Load teachers when region changes
    $('#region_filter').on('change', function() {
        const regionId = $(this).val();
        const trainingCode = $('#training-code-input').val();
        
        if (!trainingCode) {
            console.error('Training code not found');
            $('#teachers-content').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Error: Training code not found
                </div>
            `);
            return;
        }

        $('#teachers-content').html(`
            <div class="text-center py-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="mt-2">Loading teachers...</div>
            </div>
        `);
        
        $.ajax({
            url: `/trainings/${trainingCode}/available-teachers`,
            method: 'GET',
            data: { region_id: regionId },
            success: function(response) {
                console.log('API Response:', response); // Debug log
                if (response.status === 'success' && response.data && response.data.teachers) {
                    renderTeachers(response.data);
                } else {
                    $('#teachers-content').html(`
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            ${response.message || 'No qualified teachers found'}
                        </div>
                    `);
                }
            },
            error: function(xhr) {
                console.error('Error loading teachers:', xhr);
                $('#teachers-content').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        ${xhr.responseJSON?.message || 'Failed to load qualified teachers. Please try again.'}
                    </div>
                `);
            }
        });
    });

    // Load teachers initially when modal opens
    $('#participants-modal').on('show.bs.modal', function() {
        $('#region_filter').trigger('change');
        loadQualifiedFacilitators();
    });

    // Handle select all functionality
    $(document).on('change', '#select-all-teachers', function() {
        const isChecked = $(this).prop('checked');
        $('.teacher-checkbox').prop('checked', isChecked);
        updateTeacherCount();
    });

    $(document).on('change', '.teacher-checkbox', function() {
        updateTeacherCount();
        updateSelectAllState();
    });

    // Update teacher count
    function updateTeacherCount() {
        const count = $('.teacher-checkbox:checked').length;
        $('#selected-teachers-count').text(count);
    }

    // Update select all checkbox state
    function updateSelectAllState() {
        const totalTeachers = $('.teacher-checkbox').length;
        const selectedCount = $('.teacher-checkbox:checked').length;

        const selectAllCheckbox = $('#select-all-teachers');
        if (selectedCount === 0) {
            selectAllCheckbox.prop('checked', false);
            selectAllCheckbox.prop('indeterminate', false);
        } else if (selectedCount === totalTeachers) {
            selectAllCheckbox.prop('checked', true);
            selectAllCheckbox.prop('indeterminate', false);
        } else {
            selectAllCheckbox.prop('checked', false);
            selectAllCheckbox.prop('indeterminate', true);
        }
    }

    // Render teachers list
    function renderTeachers(data) {
        console.log('Rendering teachers with data:', data); // Debug log
        
        if (!data || !data.teachers || data.teachers.length === 0) {
            $('#teachers-content').html(`
                <div class="text-center text-muted py-4">
                    <i class="fas fa-user-slash fa-2x mb-2"></i>
                    <p class="mb-0">No available teachers found</p>
                    <small class="d-block mt-2">Possible reasons:</small>
                    <ul class="list-unstyled small">
                        <li>• Teachers are already assigned to other trainings during this period</li>
                        <li>• Teachers have reached their training participation limit for this year</li>
                        <li>• No teachers match the required education level</li>
                    </ul>
                    ${$('#region_filter').val() ? '<small class="d-block mt-2">Try selecting a different region or view all regions</small>' : ''}
                </div>
            `);
            return;
        }

        const maxParticipants = parseInt($('#max-participants').val()) || 0;
        const currentSelected = $('#teachers-content input[type="checkbox"]:checked').length;

        const content = data.teachers.map(teacher => `
            <div class="teacher-item p-2 border-bottom">
                <div class="d-flex">
                    <div class="form-check me-3">
                        <input type="checkbox" class="form-check-input teacher-checkbox" 
                               value="${teacher.id}" 
                               id="teacher-${teacher.id}"
                               ${selectedTeachers.has(teacher.id) ? 'checked' : ''}
                               ${(currentSelected >= maxParticipants && !selectedTeachers.has(teacher.id)) ? 'disabled' : ''}>
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

        $('#teachers-content').html(`
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Note:</strong> Only showing teachers who:
                <ul class="mb-0 mt-1">
                    <li>Have no schedule conflicts with this training</li>
                    <li>Haven't exceeded their training participation limit</li>
                    <li>Match the required education level</li>
                </ul>
            </div>
            ${content}
        `);
        
        // Update counts and states
        $('#total-teachers').text(data.total_teachers || data.teachers.length);
        updateTeacherCount();
        updateSelectAllState();
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
    }

    // Handle facilitator search
    $('#facilitator-search').on('input', function() {
        const searchTerm = $(this).val().trim();
        clearTimeout(searchTimeout);
        
        if (searchTerm.length < 2) {
            loadQualifiedFacilitators();
            return;
        }

        $('#facilitator-results').html('<div class="text-center py-3"><div class="spinner-border text-primary" role="status"></div></div>');
        
        searchTimeout = setTimeout(() => {
            $.ajax({
                url: `/trainings/${trainingCode}/search-facilitators`,
                method: 'GET',
                data: { query: searchTerm },
                success: function(response) {
                    if (response.status === 'success') {
                        updateFacilitatorsList(response.data);
                    } else {
                        $('#facilitator-results').html(`
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                ${response.message || 'No facilitators found'}
                            </div>
                        `);
                    }
                },
                error: function(xhr) {
                    $('#facilitator-results').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            ${xhr.responseJSON?.message || 'Failed to search facilitators'}
                        </div>
                    `);
                }
            });
        }, 500);
    });

    // Update facilitator count
    function updateFacilitatorCount() {
        const count = $('.facilitator-checkbox:checked').length;
        $('#selected-facilitators-count').text(count);
    }

    $(document).on('change', '.facilitator-checkbox', updateFacilitatorCount);

    // Handle form submission
    $('#participants-form').on('submit', function(e) {
        e.preventDefault();

        const selectedTeachers = $('.teacher-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        const selectedFacilitators = $('.facilitator-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedTeachers.length === 0 && selectedFacilitators.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Participants Selected',
                text: 'Please select at least one teacher or facilitator to assign.',
                confirmButtonText: 'OK'
            });
            return;
        }

        Swal.fire({
            title: 'Assigning Participants',
            text: 'Please wait while we process your request...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            },
            didOpen: () => {
                // Prepare data for submission
                const formData = {
                    training_code: trainingCode,
                    teacher_ids: selectedTeachers,
                    facilitator_ids: selectedFacilitators
                };

                // Submit the form
                $.ajax({
                    url: `/trainings/${trainingCode}/assign-training-participants`,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#participants-modal').modal('hide');
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message || 'Participants have been assigned successfully.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                participantsTable.ajax.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Warning',
                                text: response.message || 'Some participants could not be assigned.',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        $('#participants-modal').modal('hide');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Failed to assign participants. Please try again.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });
});
