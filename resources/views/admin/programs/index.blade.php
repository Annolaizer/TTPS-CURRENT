@extends('admin.master_layout.index')

@section('title', 'TTP - Programs')

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/select/1.3.3/css/select.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
<style>
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
    #programs-table th, #programs-table td {
        vertical-align: middle;
    }
    /* Default Select2 width */
    .select2-container {
        width: 100% !important;
    }
    /* Filter table Select2 */
    body > .select2-container {
        z-index: 1060 !important;
    }
    /* Modal Select2 */
    .modal .select2-container {
        z-index: 1 !important;
    }
    .bulk-actions {
        display: none;
    }
</style>
@endpush

@section('content')
<main class="main-content">
    <div class="container-fluid py-4">
        <div class="container mt-4">
            <!-- Filters Section -->
            <div class="card mb-4 filters-section">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="search-filter" placeholder="Search programs...">
                        </div>
                        <div class="col-md-3" id="subject-filter-container">
                            <select class="form-select select2" id="subject-filter">
                                <option value="">All Subjects</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button id="clear-filters" class="btn btn-secondary">Clear Filters</button>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-custom-primary" data-bs-toggle="modal" data-bs-target="#bulkAddProgramModal">
                                <i class="fas fa-file-import"></i> Bulk Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div class="bulk-actions mb-3">
                <button class="btn btn-danger" id="bulk-delete">
                    <i class="fas fa-trash"></i> Delete Selected
                </button>
            </div>

            <!-- Table Section -->
            <div class="card">
                <div class="card-body">
                    <table id="programs-table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="30">
                                    <input type="checkbox" class="form-check-input" id="select-all">
                                </th>
                                <th width="50">#</th>
                                <th>Program Name</th>
                                <th>Subject</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- Bulk Add Program Modal -->
        <div class="modal fade" id="bulkAddProgramModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Bulk Add Programs</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="bulk-add-program-form">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Subject <span class="text-danger">*</span></label>
                                <select name="subject_id" class="form-select select2" required>
                                    <option value="">Select Subject</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Programs (One per line) <span class="text-danger">*</span></label>
                                <textarea name="programs" class="form-control" rows="10" required 
                                    placeholder="Enter program names, one per line. Example:
Program 1
Program 2
Program 3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-custom-primary">Add Programs</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Program Modal -->
        <div class="modal fade" id="editProgramModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Program</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="edit-program-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit-program-id" name="program_id">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Subject <span class="text-danger">*</span></label>
                                <select name="subject_id" class="form-select select2" required>
                                    <option value="">Select Subject</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Program Name <span class="text-danger">*</span></label>
                                <input type="text" id="edit-program-name" name="program_name" class="form-control" required>
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
<script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.bootstrap5.min.js"></script>
<script src="{{ asset('asset/js/admin/program.js') }}"></script>
@endpush
