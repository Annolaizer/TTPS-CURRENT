@extends('admin.master_layout.index')

@section('title', 'TTP - Subjects')

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
    .caret-icon {
        cursor: pointer;
        transition: transform 0.2s;
    }
    .caret-icon.rotate {
        transform: rotate(90deg);
    }
    .program-details {
        background-color: #f8f9fa;
        padding: 1rem;
    }
    .btn-custom-primary {
        background-color: #00b5ad;
        border-color: #00b5ad;
        color: white;
    }
    .btn-custom-primary:hover {
        background-color: #009c94;
        border-color: #009c94;
        color: white;
    }
    .text-primary {
        color: #00b5ad !important;
    }
    #subjects-table th, #subjects-table td {
        vertical-align: middle;
    }
</style>
@endpush

@section('content')
<main class="main-content">
    <div class="container-fluid py-4">
        <div class="container mt-4">
            <!-- Filters Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="search-filter" placeholder="Search subjects...">
                        </div>
                        <div class="col-md-2">
                            <button id="clear-filters" class="btn btn-secondary">Clear Filters</button>
                        </div>
                        <div class="col-md-7 text-end">
                            <button class="btn btn-custom-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                                <i class="fas fa-plus"></i> Add Subject
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="card">
                <div class="card-body">
                    <table id="subjects-table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="30"></th>
                                <th width="50">#</th>
                                <th>Subject Name</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Subject Modal -->
        <div class="modal fade" id="addSubjectModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Subject</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="add-subject-form">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Subject Name <span class="text-danger">*</span></label>
                                <input type="text" name="subject_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-custom-primary">Add Subject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Subject Modal -->
        <div class="modal fade" id="editSubjectModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Subject</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="edit-subject-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit-subject-id" name="subject_id">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Subject Name <span class="text-danger">*</span></label>
                                <input type="text" id="edit-subject-name" name="subject_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea id="edit-description" name="description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-custom-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="{{ asset('asset/js/admin/subject.js') }}"></script>
@endpush
