let table;
let selectedPrograms = [];

$(document).ready(function() {
    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize Select2 for all dropdowns
    $('.select2').select2();

    // Reinitialize Select2 when modals are shown
    $('#bulkAddProgramModal, #editProgramModal').on('shown.bs.modal', function() {
        $(this).find('.select2').select2();
    });

    // Destroy Select2 when modals are hidden to prevent duplicates
    $('#bulkAddProgramModal, #editProgramModal').on('hidden.bs.modal', function() {
        $(this).find('.select2').select2('destroy');
    });

    // Initialize DataTables
    table = $('#programs-table').DataTable({
        processing: true,
        serverSide: true,
        select: {
            style: 'multi',
            selector: 'td:first-child input[type="checkbox"]'
        },
        ajax: {
            url: '/admin/programs/data',
            type: 'GET',
            data: function(d) {
                d.search_filter = $('#search-filter').val();
                d.subject_filter = $('#subject-filter').val();
                return d;
            },
            error: function(xhr, error, thrown) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load programs.'
                });
            }
        },
        columns: [
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `<input type="checkbox" class="form-check-input row-checkbox" value="${data.id}">`;
                }
            },
            { 
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { 
                data: 'program_name', 
                name: 'program_name',
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return `<span class="text-primary">${data}</span>`;
                    }
                    return data;
                }
            },
            { 
                data: 'subject.subject_name', 
                name: 'subject.subject_name'
            },
            { 
                data: null,
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `
                        <div class="btn-group">
                            <button class="btn btn-sm btn-custom-primary edit-btn" data-id="${data.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${data.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [[2, 'asc']],
        drawCallback: function() {
            updateCheckboxStates();
        }
    });

    // Load subjects for filter and modals
    loadSubjects();

    // Handle select-all checkbox
    $('#select-all').on('change', function() {
        const isChecked = $(this).prop('checked');
        $('.row-checkbox').prop('checked', isChecked);
        updateBulkActions();
        
        if (isChecked) {
            selectedPrograms = $('.row-checkbox').map(function() {
                return $(this).val();
            }).get();
        } else {
            selectedPrograms = [];
        }
    });

    // Handle individual checkboxes
    $('#programs-table').on('change', '.row-checkbox', function() {
        const programId = $(this).val();
        
        if ($(this).prop('checked')) {
            if (!selectedPrograms.includes(programId)) {
                selectedPrograms.push(programId);
            }
        } else {
            const index = selectedPrograms.indexOf(programId);
            if (index > -1) {
                selectedPrograms.splice(index, 1);
            }
        }
        
        updateBulkActions();
        updateSelectAllCheckbox();
    });

    // Search filter
    $('#search-filter').on('keyup', function() {
        table.draw();
    });

    // Subject filter
    $('#subject-filter').on('change', function() {
        table.draw();
    });

    // Clear filters
    $('#clear-filters').on('click', function() {
        $('#search-filter').val('');
        $('#subject-filter').val('').trigger('change');
        table.draw();
    });

    // Add Program Form Submit
    $('#add-program-form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '/admin/programs',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (!response.error) {
                    $('#addProgramModal').modal('hide');
                    $('#add-program-form')[0].reset();
                    $('.select2').val(null).trigger('change');
                    table.draw();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                let message = 'An error occurred while creating the program.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message
                });
            }
        });
    });

    // Bulk Add Programs Form Submit
    $('#bulk-add-program-form').on('submit', function(e) {
        e.preventDefault();
        
        const subjectId = $(this).find('[name="subject_id"]').val();
        const programsText = $(this).find('[name="programs"]').val();
        const programs = programsText.split('\n').filter(line => line.trim() !== '');
        
        if (programs.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please enter at least one program name.'
            });
            return;
        }
        
        $.ajax({
            url: '/admin/programs/bulk',
            method: 'POST',
            data: {
                subject_id: subjectId,
                programs: programs
            },
            success: function(response) {
                if (!response.error) {
                    $('#bulkAddProgramModal').modal('hide');
                    $('#bulk-add-program-form')[0].reset();
                    $('.select2').val(null).trigger('change');
                    table.draw();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                let message = 'An error occurred while adding programs.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message
                });
            }
        });
    });

    // Edit Program Button Click
    $('#programs-table').on('click', '.edit-btn', function() {
        const programId = $(this).data('id');
        
        $.ajax({
            url: `/admin/programs/${programId}`,
            method: 'GET',
            success: function(response) {
                if (!response.error) {
                    const program = response.data;
                    $('#edit-program-id').val(program.id);
                    $('#edit-program-name').val(program.program_name);
                    $('#edit-description').val(program.description);
                    $('#editProgramModal select[name="subject_id"]').val(program.subject_id).trigger('change');
                    $('#editProgramModal').modal('show');
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch program details.'
                });
            }
        });
    });

    // Edit Program Form Submit
    $('#edit-program-form').on('submit', function(e) {
        e.preventDefault();
        const programId = $('#edit-program-id').val();
        
        $.ajax({
            url: `/admin/programs/${programId}`,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                if (!response.error) {
                    $('#editProgramModal').modal('hide');
                    $('#edit-program-form')[0].reset();
                    $('.select2').val(null).trigger('change');
                    table.draw();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                let message = 'An error occurred while updating the program.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message
                });
            }
        });
    });

    // Delete Program Button Click
    $('#programs-table').on('click', '.delete-btn', function() {
        const programId = $(this).data('id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                deletePrograms([programId]);
            }
        });
    });

    // Bulk Delete Button Click
    $('#bulk-delete').on('click', function() {
        if (selectedPrograms.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Selection',
                text: 'Please select at least one program to delete.'
            });
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete ${selectedPrograms.length} program(s). This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete them!'
        }).then((result) => {
            if (result.isConfirmed) {
                deletePrograms(selectedPrograms);
            }
        });
    });
});

// Load subjects for filter and modals
function loadSubjects() {
    $.ajax({
        url: '/admin/subjects/all',
        method: 'GET',
        success: function(response) {
            if (!response.error) {
                const subjects = response.data;
                let options = '<option value="">Select Subject</option>';
                
                subjects.forEach(function(subject) {
                    options += `<option value="${subject.subject_id}">${subject.subject_name}</option>`;
                });
                
                $('#subject-filter').html(options);
                $('select[name="subject_id"]').html(options);
                
                // Initialize Select2 for subject filter
                $('#subject-filter').select2();
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load subjects.'
            });
        }
    });
}

// Delete programs (single or bulk)
function deletePrograms(programIds) {
    $.ajax({
        url: '/admin/programs/' + programIds[0],
        method: 'DELETE',
        data: {
            ids: programIds
        },
        success: function(response) {
            if (!response.error) {
                selectedPrograms = selectedPrograms.filter(id => !programIds.includes(id));
                updateBulkActions();
                table.draw();
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message
                });
            }
        },
        error: function(xhr) {
            let message = 'Failed to delete the program(s).';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message
            });
        }
    });
}

// Update bulk actions visibility
function updateBulkActions() {
    if (selectedPrograms.length > 0) {
        $('.bulk-actions').show();
    } else {
        $('.bulk-actions').hide();
    }
}

// Update select-all checkbox state
function updateSelectAllCheckbox() {
    const totalCheckboxes = $('.row-checkbox').length;
    const checkedCheckboxes = $('.row-checkbox:checked').length;
    
    $('#select-all').prop({
        checked: checkedCheckboxes > 0 && checkedCheckboxes === totalCheckboxes,
        indeterminate: checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes
    });
}

// Update checkbox states after table redraw
function updateCheckboxStates() {
    $('.row-checkbox').each(function() {
        const programId = $(this).val();
        $(this).prop('checked', selectedPrograms.includes(programId));
    });
    updateSelectAllCheckbox();
    updateBulkActions();
}
