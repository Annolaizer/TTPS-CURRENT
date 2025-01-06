$(document).ready(function() {
    // Get training code from the page
    const trainingCode = $('#training-code').text();

    // Initialize DataTable for participants
    const participantsTable = $('#participantsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/admin/trainings/' + trainingCode + '/participants/data',
            error: function(xhr, error, thrown) {
                console.error('DataTables error:', error, thrown);
                alert('Error loading participants. Please try refreshing the page.');
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'type', name: 'type' },
            { 
                data: 'attendance_status',
                name: 'attendance_status',
                render: function(data, type, row) {
                    if (type === 'display') {
                        let statusText = data.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                        return `<span class="status-badge status-${data}">${statusText}</span>`;
                    }
                    return data;
                }
            },
            {
                data: 'report_file',
                name: 'report_file',
                render: function(data, type, row) {
                    if (type === 'display') {
                        if (row.attendance_status === 'attended') {
                            return `<a href="/admin/trainings/${trainingCode}/participants/${row.participant_id}/report" 
                                      class="btn-download" 
                                      title="Download Report">
                                      <i class="fas fa-download"></i>
                                   </a>`;
                        }
                        return '<span class="text-muted">-</span>';
                    }
                    return data;
                }
            }
        ],
        order: [[0, 'asc']],
        responsive: true,
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
            emptyTable: "No participants assigned to this training",
            zeroRecords: "No matching records found"
        },
        drawCallback: function() {
            $('.btn-download').css({
                'min-width': '100px',
                'font-weight': '500',
                'background-color': '#009c95',
                'color': '#fff',
                'border-radius': '4px',
                'padding': '8px 10px',
                'margin': '2px',
                'white-space': 'nowrap'
            });
        }
    });

    // Initialize DataTables for participants modal
    function initializeParticipantsTable() {
        $('#available-participants-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                url: '/admin/trainings/available-participants',
                type: 'GET',
                data: function(d) {
                    d.training_code = $('#training-code').text();
                    d.search = $('#participant-search').val();
                    d.training_type = $('input[name="training_type"]:checked').val();
                }
            },
            columns: [
                { data: 'name' },
                { data: 'education_level' },
                { data: 'school' },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `<button class="btn btn-sm btn-primary assign-participant" data-id="${row.id}">
                                    <i class="fas fa-plus"></i> Assign
                                </button>`;
                    }
                }
            ]
        });
    }

    // Initialize Select2 for location selects
    $('#region, #district, #ward').select2({
        width: '100%',
        dropdownParent: $('#phase-modal')
    });

    // Region change event
    $('#region').on('change', function() {
        let regionId = $(this).val();
        let districtSelect = $('#district');
        let wardSelect = $('#ward');
        
        // Reset and disable district and ward dropdowns
        districtSelect.val(null).trigger('change').prop('disabled', true);
        wardSelect.val(null).trigger('change').prop('disabled', true);
        
        if (regionId) {
            // Enable and load districts
            $.ajax({
                url: `/api/districts/${regionId}`, 
                type: 'GET',
                success: function(response) {
                    const districts = Array.isArray(response) ? response : (response.districts || []);
                    
                    if (!districts || !districts.length) {
                        Swal.fire('Error', 'No districts found in this region', 'error');
                        return;
                    }

                    // Clear existing options
                    districtSelect.empty().append('<option value="">Select District</option>');
                    
                    // Add new options
                    districts.forEach(function(district) {
                        const option = new Option(district.district_name, district.district_id, false, false);
                        districtSelect.append(option);
                    });
                    
                    // Enable select
                    districtSelect.prop('disabled', false).trigger('change');
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to load districts. ';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage += xhr.responseJSON.message;
                    }
                    Swal.fire('Error', errorMessage, 'error');
                }
            });
        }
    });

    // District change event
    $('#district').on('change', function() {
        let districtId = $(this).val();
        let wardSelect = $('#ward');
        
        // Reset and disable ward dropdown
        wardSelect.val(null).trigger('change').prop('disabled', true);
        
        if (districtId) {
            // Enable and load wards
            $.ajax({
                url: `/api/wards/${districtId}`,
                type: 'GET',
                success: function(response) {
                    const wards = Array.isArray(response) ? response : (response.wards || []);
                    
                    if (!wards || !wards.length) {
                        Swal.fire('Error', 'No wards found in this district', 'error');
                        return;
                    }

                    // Clear existing options
                    wardSelect.empty().append('<option value="">Select Ward</option>');
                    
                    // Add new options
                    wards.forEach(function(ward) {
                        const option = new Option(ward.ward_name, ward.ward_id, false, false);
                        wardSelect.append(option);
                    });
                    
                    // Enable select
                    wardSelect.prop('disabled', false).trigger('change');
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to load wards. ';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage += xhr.responseJSON.message;
                    }
                    Swal.fire('Error', errorMessage, 'error');
                }
            });
        }
    });

    // Show participants modal only for Ministry of Education trainings
    $('#assign-participants-btn').on('click', function(e) {
        e.preventDefault(); // Prevent the default modal trigger
        
        const organization = $('#training-org').text().trim();
        if (organization !== 'Ministry of Education') {
            Swal.fire({
                icon: 'info',
                title: 'Not Applicable',
                text: 'Participant assignment is only available for Ministry of Education trainings.'
            });
            return;
        }

        // Initialize tables when modal is shown
        const modal = $('#participants-modal');
        modal.modal('show');
        
        // Initialize tables if not already initialized
        if (!$.fn.DataTable.isDataTable('#available-participants-table')) {
            initializeParticipantsTable();
        }
    });

    // Handle search inputs with debouncing
    let participantSearchTimeout;
    $('#participant-search').on('keyup', function() {
        clearTimeout(participantSearchTimeout);
        participantSearchTimeout = setTimeout(() => {
            $('#available-participants-table').DataTable().ajax.reload();
        }, 500);
    });

    let trainingTypeTimeout;
    $('input[name="training_type"]').on('change', function() {
        clearTimeout(trainingTypeTimeout);
        trainingTypeTimeout = setTimeout(() => {
            $('#available-participants-table').DataTable().ajax.reload();
        }, 500);
    });

    // Handle participant assignment
    $(document).on('click', '.assign-participant', function() {
        const participantId = $(this).data('id');
        const trainingCode = $('#training-code').text();
        const trainingType = $('input[name="training_type"]:checked').val();

        $.ajax({
            url: `/admin/trainings/${trainingCode}/assign-participant`,
            method: 'POST',
            data: {
                participant_id: participantId,
                training_type: trainingType,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Participant assigned successfully'
                });
                const newParticipant = {
                    ...response.participant,
                    role: response.role
                };
                participantsTable.row.add(newParticipant).draw();
                $('#participants-modal').modal('hide');
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to assign participant'
                });
            }
        });
    });

    // Handle participant removal
    $(document).on('click', '.remove-participant', function() {
        const participantId = $(this).data('id');
        const trainingCode = $('#training-code').text();

        Swal.fire({
            title: 'Are you sure?',
            text: "This will remove the participant from the training.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, remove'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/trainings/${trainingCode}/remove-participant`,
                    method: 'POST',
                    data: {
                        participant_id: participantId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Participant removed successfully'
                        });
                        const row = participantsTable.rows().eq(0).filter(function(index, data, node) {
                            return data[0] === participantId;
                        });
                        participantsTable.row(row).remove().draw();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Failed to remove participant'
                        });
                    }
                });
            }
        });
    });

    // Add phase modal open event
    $('#addPhaseBtn').on('click', function() {
        // Get current training data from the page
        const trainingCode = $('#training-code').text().trim();
        const title = $('#training-title').text().trim();
        const currentPhase = parseInt($('#training-phase').text().trim());

        // Initialize Select2 for all dropdowns
        $('#phase-modal select').each(function() {
            if (!$(this).data('select2')) {
                $(this).select2({
                    width: '100%',
                    dropdownParent: $('#phase-modal')
                });
            }
        });

        // Set organization (readonly)
        const orgSelect = $('select[name="organization_id"]');
        orgSelect.val($('#training-org').data('org-id')).trigger('change').prop('disabled', true);
        
        // Load training details to get education level and subjects
        $.ajax({
            url: `/admin/trainings/${trainingCode}`,
            type: 'GET',
            success: function(response) {
                if (!response.training) {
                    Swal.fire('Error', 'Failed to load training details', 'error');
                    return;
                }

                // Set education level (readonly)
                const educSelect = $('#education_level');
                educSelect.val(response.training.education_level).prop('disabled', true);
                
                // Load subjects based on education level
                $.ajax({
                    url: `/admin/subjects/${response.training.education_level}`,
                    type: 'GET',
                    success: function(subjectResponse) {
                        const subjectsSelect = $('select[name="subjects[]"]');
                        subjectsSelect.empty();
                        
                        if (subjectResponse.subjects && subjectResponse.subjects.length > 0) {
                            subjectResponse.subjects.forEach(function(subject) {
                                const option = new Option(subject.subject_name, subject.subject_id, false, false);
                                subjectsSelect.append(option);
                            });
                            subjectsSelect.prop('disabled', false).trigger('change');
                        } else {
                            console.error('No subjects found for education level:', response.training.education_level);
                            Swal.fire('Warning', 'No subjects found for this education level', 'warning');
                        }
                    },
                    error: function(xhr) {
                        console.error('Subject loading error:', xhr);
                        Swal.fire('Error', 'Failed to load subjects. Please try again.', 'error');
                    }
                });
            },
            error: function(xhr) {
                console.error('Training loading error:', xhr);
                Swal.fire('Error', 'Failed to load training details. Please try again.', 'error');
            }
        });
        
        // Set title (readonly)
        $('input[name="title"]').val(title + ' - Phase ' + (currentPhase + 1)).prop('disabled', true);
        
        // Set next phase as default (can be changed)
        const phaseSelect = $('select[name="training_phase"]');
        if (currentPhase < 10) {
            phaseSelect.val(currentPhase + 1).trigger('change');
        }
            
        // Add hidden input for parent training code
        if (!$('input[name="parent_training_code"]').length) {
            $('<input>').attr({
                type: 'hidden',
                name: 'parent_training_code',
                value: trainingCode
            }).appendTo('#phase-form');
        }

        // Show the modal
        $('#phase-modal').modal('show');
    });

    // Calculate duration when dates change
    function calculateDuration() {
        const startDate = $('input[name="start_date"]').val();
        const endDate = $('input[name="end_date"]').val();
        const durationInput = $('input[name="duration_days"]');
        
        if (startDate && endDate) {
            const start = moment(startDate);
            const end = moment(endDate);
            
            if (end.isBefore(start)) {
                $('input[name="end_date"]').addClass('is-invalid');
                $('input[name="end_date"]').next('.invalid-feedback').text('End date cannot be before start date');
                durationInput.val('');
            } else {
                $('input[name="end_date"]').removeClass('is-invalid');
                $('input[name="end_date"]').next('.invalid-feedback').text('');
                // Add 1 to include both start and end dates
                const duration = end.diff(start, 'days') + 1;
                durationInput.val(duration);
            }
        }
    }

    // Attach date change handlers
    $('input[name="start_date"], input[name="end_date"]').on('change', function() {
        calculateDuration();
    });

    // Reset form when modal is closed
    $('#phase-modal').on('hidden.bs.modal', function() {
        const form = $('#phase-form');
        form.trigger('reset');
        form.find('.is-invalid').removeClass('is-invalid');
        
        // Reset and enable all selects except organization and education level
        const selects = $('select[name="training_phase"], select[name="subjects[]"]');
        selects.prop('disabled', false);
        
        // Keep organization and education level disabled
        $('select[name="organization_id"], select[name="education_level"]').prop('disabled', true);
        
        // Destroy and reinitialize Select2
        selects.each(function() {
            if ($(this).data('select2')) {
                $(this).select2('destroy');
            }
        });
        
        // Reinitialize Select2
        selects.select2({
            width: '100%',
            dropdownParent: $('#phase-modal')
        });
    });

    // Handle phase form submission
    $('#phase-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const formData = new FormData(form[0]);
        
        // Validate required fields
        let isValid = true;
        form.find('[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                $(this).next('.invalid-feedback').text('This field is required');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            Swal.fire('Error', 'Please fill in all required fields', 'error');
            return;
        }
        
        // Show loading state
        Swal.fire({
            title: 'Creating New Phase',
            text: 'Please wait...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Submit form
        $.ajax({
            url: '/admin/trainings/phases',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'New training phase has been created successfully',
                    showConfirmButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload();
                    }
                });
            },
            error: function(xhr) {
                let errorMessage = 'Failed to create new phase. ';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage += xhr.responseJSON.message;
                }
                Swal.fire('Error', errorMessage, 'error');
            }
        });
    });

    // Reset modal on close
    $('#phase-modal').on('hidden.bs.modal', function() {
        // Reset all Select2 dropdowns in the modal
        $(this).find('.select2').val(null).trigger('change');
        
        // Reset the form
        $('#phase-form')[0].reset();
        
        // Disable cascading selects
        $('#district, #ward').prop('disabled', true);
        
        // Clear validation states
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').empty();
    });
});
