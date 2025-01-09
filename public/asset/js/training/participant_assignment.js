// Participant Assignment Management
$(document).ready(function() {
    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const trainingCode = $('#training-code').text().trim();
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
        dropdownParent: $('#participants-modal'),
        templateResult: formatRegion,
        templateSelection: formatRegion
    });

    // Format region option
    function formatRegion(region) {
        if (!region.id) {
            return $(`<span>
                <i class="fas fa-globe me-2"></i>
                <span class="region-name">All Regions</span>
            </span>`);
        }
        return $(`<span>
            <i class="fas fa-map-marker-alt me-2"></i>
            <span class="region-name">${region.text}</span>
        </span>`);
    }

    // Load teachers data
    function loadTeachers() {
        const regionId = $('#region_filter').val();
        $('#teachers-content').html('<div class="text-center py-3"><div class="spinner-border text-primary" role="status"></div></div>');
        
        $.ajax({
            url: `/qualified-teachers/${trainingCode}`,
            type: 'GET',
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
    }

    // Render teachers list
    function renderTeachers(data) {
        const { teachers, total_teachers } = data;
        $('#total-teachers').text(total_teachers);
        
        if (teachers.length === 0) {
            $('#teachers-content').html(`
                <div class="text-center text-muted py-4">
                    <i class="fas fa-search fa-2x mb-2"></i>
                    <p class="mb-0">No teachers found for the selected criteria</p>
                    ${$('#region_filter').val() ? '<small>Try selecting a different region or view all regions</small>' : ''}
                </div>
            `);
            return;
        }
        
        const content = teachers.map(teacher => `
            <div class="teacher-item p-2 border-bottom">
                <div class="d-flex align-items-start">
                    <div class="form-check me-2">
                        <input type="checkbox" class="form-check-input teacher-select" 
                               value="${teacher.id}" 
                               id="teacher-${teacher.id}"
                               ${selectedTeachers.has(teacher.id) ? 'checked' : ''}>
                    </div>
                    <div class="flex-grow-1">
                        <div>
                            <strong class="d-block" style="font-size: 0.9rem;">${teacher.name}</strong>
                            <small class="text-muted d-block">${teacher.registration_number}</small>
                        </div>
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
    }

    // Handle region filter change
    $('#region_filter').on('change', function() {
        loadTeachers();
    });

    // Handle select all
    $('#select-all').on('change', function() {
        const isChecked = $(this).prop('checked');
        $('.teacher-select').prop('checked', isChecked);
        if (isChecked) {
            $('.teacher-select').each(function() {
                selectedTeachers.add($(this).val());
            });
        } else {
            selectedTeachers.clear();
        }
    });

    // Handle individual teacher selection
    $(document).on('change', '.teacher-select', function() {
        const teacherId = $(this).val();
        if ($(this).prop('checked')) {
            selectedTeachers.add(teacherId);
        } else {
            selectedTeachers.delete(teacherId);
            $('#select-all').prop('checked', false);
        }
    });

    // Load initial data
    loadTeachers();

    // Debug info
    console.log('Training code:', trainingCode);
    console.log('CSRF token:', $('meta[name="csrf-token"]').attr('content'));
});
