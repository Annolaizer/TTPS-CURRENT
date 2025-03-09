let table;

$(document).ready(function() {
    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize DataTables
    table = $('#subjects-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/admin/subjects/data',
            type: 'GET',
            data: function(d) {
                d.search_filter = $('#search-filter').val();
                return d;
            }
        },
        columns: [
            {
                className: 'details-control',
                orderable: false,
                data: null,
                defaultContent: '<i class="fas fa-caret-right caret-icon"></i>'
            },
            { 
                data: null,
                render: function(data, type, row, meta) {
                    // meta.row is zero-based, so add 1 for display
                    return meta.row + 1;
                }
            },
            { 
                data: 'subject_name', 
                name: 'subject_name',
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return `<span class="text-primary">${data}</span>`;
                    }
                    return data;
                }
            },
            { 
                data: null,
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `
                        <div class="btn-group">
                            <button class="btn btn-sm btn-custom-primary edit-btn" data-id="${data.subject_id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${data.subject_id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [[2, 'asc']],
        drawCallback: function() {
            $('.caret-icon').removeClass('rotate');
        }
    });

    // Handle row expansion for programs
    $('#subjects-table tbody').on('click', 'td.details-control', function() {
        let tr = $(this).closest('tr');
        let row = table.row(tr);
        let icon = $(this).find('.caret-icon');

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
            icon.removeClass('rotate');
        } else {
            let subjectId = row.data().subject_id;
            $.get(`/admin/subjects/${subjectId}`, function(data) {
                if (data.success) {
                    let programs = data.subject.programs;
                    let html = `
                        <div class="program-details p-3">
                            <h6 class="mb-3">Programs (${programs.length})</h6>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Program Name</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                    
                    if (programs.length > 0) {
                        programs.forEach(program => {
                            html += `
                                <tr>
                                    <td>${program.program_name}</td>
                                    <td>${program.description || '-'}</td>
                                </tr>`;
                        });
                    } else {
                        html += '<tr><td colspan="2" class="text-center">No programs found</td></tr>';
                    }

                    html += `
                                </tbody>
                            </table>
                        </div>`;

                    row.child(html).show();
                    tr.addClass('shown');
                    icon.addClass('rotate');
                }
            });
        }
    });

    // Filter handling
    $('#search-filter').on('keyup', function() {
        table.ajax.reload();
    });

    $('#clear-filters').on('click', function() {
        $('#search-filter').val('');
        table.ajax.reload();
    });

    // Add Subject
    $('#add-subject-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '/admin/subjects',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#addSubjectModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                    $('#add-subject-form')[0].reset();
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to add subject'
                });
            }
        });
    });

    // Edit Subject
    $(document).on('click', '.edit-btn', function() {
        let subjectId = $(this).data('id');
        $.get(`/admin/subjects/${subjectId}`, function(response) {
            if (response.success) {
                let subject = response.subject;
                $('#edit-subject-id').val(subject.subject_id);
                $('#edit-subject-name').val(subject.subject_name);
                $('#edit-description').val(subject.description);
                $('#editSubjectModal').modal('show');
            }
        });
    });

    $('#edit-subject-form').on('submit', function(e) {
        e.preventDefault();
        let subjectId = $('#edit-subject-id').val();
        $.ajax({
            url: `/admin/subjects/${subjectId}`,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#editSubjectModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to update subject'
                });
            }
        });
    });

    // Delete Subject
    $(document).on('click', '.delete-btn', function() {
        let subjectId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the subject and all associated programs. This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/subjects/${subjectId}`,
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            table.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Failed to delete subject'
                        });
                    }
                });
            }
        });
    });
});
