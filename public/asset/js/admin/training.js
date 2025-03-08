let table;

$(document).ready(function() {
    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize DataTables
    table = $('#trainings-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/admin/trainings/data',
            type: 'GET',
            data: function(d) {
                d.search_filter = $('#search-filter').val();
                d.start_date = $('#start-date').val();
                d.organization_id = $('#organization-filter').val();
                d.ownership = $('#ownership-filter').val();
                d.status = $('#status-filter').val();
                return d;
            },
            error: function(xhr, error, thrown) {
                console.error('DataTable Error:', error);
                console.error('XHR:', xhr);
                console.error('Thrown:', thrown);
            }
        },
        columns: [
            {
                render: function(){
                    return '<td class="text-center"><i class="fas fa-caret-right caret-icon"></i></td>';
                }
            },
            { 
                data: 'training_code',
                name: 'training_code',
                render: function(data, type, row) {
                    return `<a href="/admin/trainings/${data}/assignment" class="text-primary">${data}</a>`;
                }
            },
            { data: 'title', name: 'title' },
            { data: 'organization', name: 'organization.name' },
            {
                data: 'status',
                name: 'status',
                render: function(data, type, row) {
                    const badgeClass = getBadgeClass(data);
                    const textColor = badgeClass === 'warning' || badgeClass === 'light' ? 'text-dark' : 'text-white';
                    return `<span class="badge bg-${badgeClass} ${textColor}">${data}</span>`;
                }
            },
            { 
                data: null,
                name: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    return `
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-custom-primary view-btn" data-training-code="${row.training_code}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-custom-primary edit-btn" data-training-code="${row.training_code}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-training-code="${row.training_code}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [[0, 'desc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        language: {
            lengthMenu: "_MENU_ records per page",
            search: "Search:",
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>',
            paginate: {
                first: "First",
                last: "Last",
                next: "<i class='fas fa-chevron-right'></i>",
                previous: "<i class='fas fa-chevron-left'></i>"
            }
        }
    });

    // Training session data (First training now has 3 sessions)
    let trainingSessions = {
        1: [
            { number: 1, region: "New York", district: "Manhattan", venue: "Hilton NY", start: "2024-03-15", end: "2024-03-18", status: "Active" },
            { number: 2, region: "California", district: "San Francisco", venue: "Grand Hotel", start: "2024-04-01", end: "2024-04-03", status: "Active" },
            { number: 3, region: "Texas", district: "Austin", venue: "Tech Hub", start: "2024-05-10", end: "2024-05-12", status: "Inactive" }
        ],
        2: [
            { number: 1, region: "Florida", district: "Orlando", venue: "Conference Center", start: "2024-06-05", end: "2024-06-07", status: "Active" }
        ]
    };

    // Expand/Collapse Row Functionality
    $('#trainings-table tbody').on('click', '.caret-icon', function () {
        let row = $(this).closest('tr');
        let trainingId = row.data('training-id');
        let icon = $(this);

        if (row.next().hasClass('session-details')) {
            // Collapse row
            row.next().remove();
            icon.removeClass('rotate').removeClass('fa-caret-down').addClass('fa-caret-right');
        } else {
            // Expand row
            let sessions = trainingSessions[trainingId] || [];
            let sessionHtml = `<tr class="session-details"><td colspan="6">
                <strong>Training Sessions:</strong>
                <table class="table table-sm table-bordered mt-2">
                    <thead class="table-secondary">
                        <tr>
                            <th>Session #</th>
                            <th>Region</th>
                            <th>District</th>
                            <th>Venue</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>`;

            sessions.forEach(session => {
                sessionHtml += `
                    <tr>
                        <td>${session.number}</td>
                        <td>${session.region}</td>
                        <td>${session.district}</td>
                        <td>${session.venue}</td>
                        <td>${session.start}</td>
                        <td>${session.end}</td>
                        <td><span class="badge ${session.status === 'Active' ? 'bg-success' : 'bg-danger'}">${session.status}</span></td>
                    </tr>`;
            });

            sessionHtml += `</tbody></table></td></tr>`;

            row.after(sessionHtml);
            icon.addClass('rotate').removeClass('fa-caret-right').addClass('fa-caret-down');
        }
    });

    // Initialize Select2
    $('.select2').select2({
        width: '100%',
        placeholder: 'Select an option'
    });

    // Handle filter changes
    $('.filter-input').on('change keyup', function() {
        table.ajax.reload();
    });

    // Clear filters
    $('#clear-filters').on('click', function() {
        $('.filter-input').val('').trigger('change');
        table.ajax.reload();
    });

    // Initialize Select2 for modal
    $('#training-modal .select2').select2({
        width: '100%',
        dropdownParent: $('#training-modal')
    });

    // Date validation and duration calculation
    $('input[name="start_date"], input[name="end_date"]').on('change', function() {
        const startDate = $('input[name="start_date"]').val();
        const endDate = $('input[name="end_date"]').val();
        
        if (startDate && endDate) {
            const start = moment(startDate);
            const end = moment(endDate);
            
            if (end.isBefore(start)) {
                $(this).addClass('is-invalid');
                $(this).next('.invalid-feedback').text('End date cannot be before start date');
                $('input[name="duration_days"]').val('');
            } else {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').text('');
                const duration = end.diff(start, 'days') + 1;
                $('input[name="duration_days"]').val(duration);
            }
        }
    });

    // Time validation
    $('input[name="start_time"]').on('change', function() {
        const timeRegex = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;
        const time = $(this).val();
        
        if (!timeRegex.test(time)) {
            $(this).addClass('is-invalid');
            $(this).next('.invalid-feedback').text('Please enter a valid time in HH:MM format');
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').text('');
        }
    });

    // Load training data for modal
    function loadTrainingData(trainingCode, mode = 'view') {
        $.ajax({
            url: `/admin/trainings/${trainingCode}`,
            type: 'GET',
            success: function(response) {
                if (response.success === false) {
                    Swal.fire('Error', response.message || 'Failed to load training details', 'error');
                    return;
                }
                
                if (mode === 'view') {
                    displayTrainingDetails(response);
                } else {
                    populateEditForm(response);
                }
            },
            error: function(xhr) {
                let message = 'Failed to load training details. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire('Error', message, 'error');
            }
        });
    }

    // Display training details in view modal
    function displayTrainingDetails(response) {
        if (!response.training) {
            Swal.fire('Error', 'Invalid training data received', 'error');
            return;
        }

        const training = response.training;
        
        $('#view-training-code').text(training.training_code);
        const badgeClass = getBadgeClass(training.status);
        const textColor = badgeClass === 'warning' || badgeClass === 'light' ? 'text-dark' : 'text-white';
        $('#view-status').html(`<span class="badge bg-${badgeClass} ${textColor}" style="border: none;">${training.status}</span>`);
        $('#view-title').text(training.title);
        $('#view-organization').text(training.organization ? training.organization.name : 'N/A');
        $('#view-education-level').text(training.education_level || 'N/A');
        $('#view-description').text(training.description);
        $('#view-phase').text(`Phase ${training.training_phase}`);
        $('#view-duration').text(`${training.duration_days} days`);
        $('#view-participants').text(training.max_participants);
        $('#view-dates').text(`${moment(training.start_date).format('YYYY-MM-DD')} to ${moment(training.end_date).format('YYYY-MM-DD')}`);
        $('#view-time').text(moment(training.start_time, 'HH:mm:ss').format('hh:mm A'));
        $('#view-location').text(`${training.venue_name}`);
        $('#view-subjects').text(training.subjects ? training.subjects.map(s => s.subject_name).join(', ') : 'None');
        
        // Show/hide pending actions based on status
        const pendingActions = $('.pending-actions');
        if (training.status === 'pending') {
            pendingActions.show();
        } else {
            pendingActions.hide();
        }
        
        const viewModal = new bootstrap.Modal(document.getElementById('view-modal'));
        viewModal.show();
    }

    // Handle verify training
    $(document).on('click', '.verify-training-btn', function() {
        const trainingCode = $('#view-training-code').text().trim();
        
        Swal.fire({
            title: 'Verify Training',
            text: 'Are you sure you want to verify this training?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, verify it',
            cancelButtonText: 'No, cancel',
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/trainings/${trainingCode}/verify`,
                    type: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                title: 'Success',
                                text: response.message || 'Training has been verified',
                                icon: 'success'
                            }).then(() => {
                                $('#view-modal').modal('hide');
                                table.ajax.reload(null, false); // Reload table data
                            });
                        } else {
                            Swal.fire('Error', response.message || 'Failed to verify training', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error response:', xhr.responseText); // Add debug logging
                        let message = 'Failed to verify training';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire('Error', message, 'error');
                    }
                });
            }
        });
    });

    // Handle reject button click
    $(document).on('click', '.reject-training-btn', function() {
        const trainingCode = $('#view-training-code').text().trim();
        $('#reject-training-code').val(trainingCode);
        $('#rejection_reason').val('').removeClass('is-invalid');
        
        const rejectModal = new bootstrap.Modal(document.getElementById('reject-modal'));
        rejectModal.show();
    });

    // Handle reject form submission
    $('#reject-form').on('submit', function(e) {
        e.preventDefault();
        
        const trainingCode = $('#reject-training-code').val().trim();
        const reason = $('#rejection_reason').val().trim();
        
        if (!reason) {
            $('#rejection_reason').addClass('is-invalid')
                .siblings('.invalid-feedback')
                .text('Please provide a reason for rejection');
            return;
        }
        
        $.ajax({
            url: `/admin/trainings/${trainingCode}/reject`,
            type: 'PUT',
            data: { reason: reason },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        title: 'Success',
                        text: 'Training has been rejected',
                        icon: 'success'
                    }).then(() => {
                        $('#reject-modal').modal('hide');
                        $('#view-modal').modal('hide');
                        table.ajax.reload(null, false); // Reload table data
                    });
                } else {
                    Swal.fire('Error', response.message || 'Failed to reject training', 'error');
                }
            },
            error: function(xhr) {
                let message = 'Failed to reject training';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire('Error', message, 'error');
            }
        });
    });

    // Populate edit form with training data
    async function populateEditForm(response) {
        if (!response.training) {
            Swal.fire('Error', 'Invalid training data received', 'error');
            return;
        }

        const training = response.training;
        
        // Set modal title
        $('#training-modal .modal-title').text('Edit Training');
        
        // Create hidden input for training code if it doesn't exist
        if (!$('input[name="training_code"]').length) {
            $('<input>').attr({
                type: 'hidden',
                name: 'training_code'
            }).appendTo('#training-form');
        }
        
        // Set training code
        $('input[name="training_code"]').val(training.training_code);
        
        // Basic info
        $('input[name="title"]').val(training.title);
        $('select[name="organization_id"]').val(training.organization_id).trigger('change');
        $('select[name="education_level"]').val(training.education_level).trigger('change');
        $('select[name="training_phase"]').val(training.training_phase).trigger('change');
        $('input[name="max_participants"]').val(training.max_participants);
        $('textarea[name="description"]').val(training.description);
        
        // Schedule
        $('input[name="start_date"]').val(moment(training.start_date).format('YYYY-MM-DD'));
        $('input[name="end_date"]').val(moment(training.end_date).format('YYYY-MM-DD'));
        $('input[name="start_time"]').val(moment(training.start_time, 'HH:mm:ss').format('HH:mm'));
        $('input[name="duration_days"]').val(training.duration_days);
        
        // Location
        $('#region').val(training.region_id).trigger('change');
        
        // Wait for districts to load
        await new Promise(resolve => {
            const checkDistricts = setInterval(() => {
                if (!$('#district').prop('disabled')) {
                    clearInterval(checkDistricts);
                    $('#district').val(training.district_id).trigger('change');
                    resolve();
                }
            }, 100);
        });
        
        // Wait for wards to load
        await new Promise(resolve => {
            const checkWards = setInterval(() => {
                if (!$('#ward').prop('disabled')) {
                    clearInterval(checkWards);
                    $('#ward').val(training.ward_id).trigger('change');
                    resolve();
                }
            }, 100);
        });
        
        $('input[name="venue_name"]').val(training.venue_name);
        
        // Subjects
        const subjectIds = training.subjects.map(subject => subject.subject_id);
        $('select[name="subjects[]"]').val(subjectIds).trigger('change');
        
        // Show modal
        $('#training-modal').modal('show');
    }

    // View button click
    $(document).on('click', '.view-btn', function() {
        const trainingCode = $(this).data('training-code');
        loadTrainingData(trainingCode, 'view');
    });

    // Edit button click
    $(document).on('click', '.edit-btn', function() {
        const trainingCode = $(this).data('training-code');
        loadTrainingData(trainingCode, 'edit');
    });

    // Handle delete button click
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const trainingCode = $(this).data('training-code');
        const token = $('meta[name="csrf-token"]').attr('content');
        
        if (!token) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'CSRF token not found. Please refresh the page and try again.'
            });
            return;
        }
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while we delete the training.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Perform delete operation
                $.ajax({
                    url: `/admin/trainings/${trainingCode}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message || 'Training has been deleted.',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                // Reload table data without resetting pagination
                                if (table) {
                                    table.ajax.reload();
                                } else {
                                    window.location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message || 'Failed to delete training.'
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Delete Error:', xhr);
                        let errorMessage = 'Failed to delete training. Please try again.';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage
                        });
                    }
                });
            }
        });
    });
});

// Helper function to get badge class based on status
function getBadgeClass(status) {
    switch(status.toLowerCase()) {
        case 'verified':
            return 'success';
        case 'pending':
            return 'warning';
        case 'rejected':
            return 'danger';
        case 'ongoing':
            return 'info';
        case 'completed':
            return 'warning';
        case 'expired':
            return 'secondary';
        case 'draft':
            return 'light';
        default:
            return 'light';
    }
}