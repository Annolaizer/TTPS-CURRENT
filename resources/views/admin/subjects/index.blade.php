@extends('admin.master_layout.index')

@section('title', 'TTP - Subjects')

@push('styles')
<!-- Include DataTables CSS -->
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- Custom CSS for Styling -->
<style>
    /* General table styling */
    #subjectsTable {
        font-size: 13px; /* Smaller font size for table contents */
        border-collapse: collapse;
        margin: 0 auto; /* Center the table */
        width: 100%; /* Full width */
    }

    /* Table header */
    #subjectsTable thead th {
        background-color: #f8f9fa; /* Light grey */
        color: #212529; /* Dark grey for better contrast */
        font-weight: bold;
        text-align: center;
        padding: 8px;
    }

    /* Table body */
    #subjectsTable tbody td {
        padding: 6px 8px; /* Smaller padding for compact rows */
        text-align: center;
        vertical-align: middle;
        font-size: 13px; /* Slightly smaller text */
        color: #495057; /* Bootstrap grey text */
    }

    /* Row hover effect */
    #subjectsTable tbody tr:hover {
        background-color: #f1f3f5; /* Lighter grey hover effect */
        transition: background-color 0.2s ease-in-out;
    }

    /* Alternating row colors */
    #subjectsTable tbody tr:nth-child(odd) {
        background-color: #fafafa; /* Very light grey for alternating rows */
    }

    #subjectsTable tbody tr:nth-child(even) {
        background-color: #ffffff; /* White for even rows */
    }

    /* Buttons */
    .btn-warning, .btn-danger {
        padding: 4px 6px; /* Smaller button size */
        font-size: 12px; /* Smaller text size */
        border-radius: 4px; /* Slightly rounded corners */
    }

    /* Add button */
    .btn-primary {
        padding: 6px 10px; /* Compact padding for Add button */
        font-size: 14px; /* Consistent font size */
        background-color: #00b5ad;
        border: none;
    }

    .btn-primary:hover {
        background-color:rgb(9, 117, 99);
    }

    /* Card shadow for the table container */
    .card {
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.05); /* Very soft shadow */
        border-radius: 8px; /* Rounded corners */
    }
</style>
@endpush

@section('content')
<main class="main-content">
    <div class="container-fluid py-4">
        <div class="container mt-4">
            <!-- Top Section -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 style="font-size: 20px; color: #333;">Subjects</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                    <i class="fas fa-plus"></i> Add Subject
                </button>
            </div>

            <!-- Table Section -->
            <div class="card">
                <div class="card-body">
                    <table id="subjectsTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Subject Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjects as $subject)
                            <tr id="subjectRow{{ $subject->id }}">
                                <td>{{ $subject->subject_id }}</td>
                                <td style="text-align: left;">{{ $subject->subject_name }}</td>
                                <td>
                                    <!-- Edit Button -->
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSubjectModal{{ $subject->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $subject->subject_id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Edit Subject Modal -->
                            <div class="modal fade" id="editSubjectModal{{ $subject->id }}" tabindex="-1" aria-labelledby="editSubjectLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editSubjectLabel">Edit Subject</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form id="editForm{{ $subject->id }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="{{ $subject->id }}">
                                                <div class="mb-3">
                                                    <label for="subject_name" class="form-label">Subject Name</label>
                                                    <input type="text" name="subject_name" class="form-control" value="{{ $subject->subject_name }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="button" onclick="updateSubject({{ $subject->subject_id }})" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Subject Modal -->
        <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSubjectLabel">Add New Subject</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="addForm">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="subject_name" class="form-label">Subject Name</label>
                                <input type="text" name="subject_name" class="form-control" placeholder="Enter subject name" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" onclick="addSubject()" class="btn btn-primary">Add Subject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<!-- Include DataTables JS and SweetAlert -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#subjectsTable').DataTable({
            paging: true,
            responsive: true
        });
    });

    // Add Subject
    function addSubject() {
        const form = $('#addForm');
        $.ajax({
            url: "{{ route('admin.subjects.create-subject') }}",
            method: "POST",
            data: form.serialize(),
            success: function(response) {
                Swal.fire('Success', 'Subject added successfully!', 'success').then(() => location.reload());
            },
            error: function(error) {
                Swal.fire('Error', 'Failed to add subject!', 'error');
            }
        });
    }

    // Update Subject
    function updateSubject(id) {
        const form = $(`#editForm${id}`);
        $.ajax({
            url: "{{ route('admin.subjects.update-subject') }}",
            method: "PUT",
            data: form.serialize(),
            success: function(response) {
                Swal.fire('Success', 'Subject updated successfully!', 'success').then(() => location.reload());
            },
            error: function(error) {
                Swal.fire('Error', 'Failed to update subject!', 'error');
            }
        });
    }

    // Delete Subject
    function confirmDelete(id) {
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
                $.ajax({
                    url: "{{ route('admin.subjects.delete-subject') }}",
                    method: "DELETE",
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Deleted!', 'Subject has been deleted.', 'success').then(() => {
                            $(`#subjectRow${id}`).remove();
                        });
                    },
                    error: function(error) {
                        Swal.fire('Error', 'Failed to delete subject!', 'error');
                    }
                });
            }
        });
    }
</script>
@endpush
