@extends('admin.master_layout.index')

@section('title', 'TTP - Institutions')

@section('content')
@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.2/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .filter-input {
            border-radius: 4px;
            border: 1px solid #ced4da;
        }
        .status-badge {
            width: 70px;
            text-align: center;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.7em;
        }
        .card {
            border: none;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .dataTables_wrapper {
            padding: 1rem 0;
        }
        .dataTables_length {
            margin-bottom: 1rem;
        }
        .dataTables_filter {
            margin-bottom: 1rem;
        }
        .dataTables_info {
            font-size: 0.875rem;
            color: #6c757d;
        }
        .dataTables_paginate {
            margin-top: 1rem;
        }
        .paginate_button {
            padding: 0.375rem 0.75rem;
            margin: 0 0.125rem;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            color: #007bff;
            cursor: pointer;
        }
        .paginate_button.current {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }
        .dt-buttons {
            margin-bottom: 1rem;
            gap: 0.5rem;
            display: inline-flex;
        }
        .dt-button {
            padding: 0.375rem 0.75rem !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 0.25rem !important;
            color: #6c757d !important;
            background: white !important;
            font-size: 0.875rem !important;
        }
        .dt-button:hover {
            color: #007bff !important;
            border-color: #007bff !important;
            background: #f8f9fa !important;
        }
        .table-loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 1000;
            display: none;
            justify-content: center;
            align-items: center;
        }
        .loader {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-radius: 50%;
            border-top: 4px solid #3498db;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .loading-text {
            display: block;
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }
        /* Table Styles */
        .table {
            font-size: 0.875rem;
        }
        .table th {
            font-weight: 600;
            font-size: 0.8125rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background-color: #f8f9fa;
            vertical-align: middle;
        }
        .table td {
            vertical-align: middle;
            padding: 0.5rem;
        }
        .table .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        .badge {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }
        
        /* Badge Styles */
        .stats-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(45deg, #36b9cc, #1a8997);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            box-shadow: 0 2px 4px rgba(54, 185, 204, 0.2);
            min-width: 180px;
        }
        .stats-badge .icon-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            flex-shrink: 0;
        }
        .stats-badge .badge-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-grow: 1;
        }
        .stats-badge .badge-label {
            font-weight: 500;
        }
        .stats-badge .badge-value {
            font-weight: 600;
            background-color: rgba(255, 255, 255, 0.2);
            padding: 0.2rem 0.6rem;
            border-radius: 15px;
        }
        .document-preview {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            background-color: #f8f9fa;
        }
        .document-preview iframe {
            width: 100%;
            height: 600px;
            border: none;
            border-radius: 4px;
        }
        .document-actions {
            margin-top: 15px;
            padding: 10px;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        .document-actions .btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .approval-letter-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }
    </style>
@endpush

<main class="main-content">
    <div class="container-fluid">
        <!-- Filters Section -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="row g-3 filters-section">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control filter-input" id="search-filter" placeholder="Search by Name or Registration">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select filter-input" id="type-filter">
                            <option value="">All Types</option>
                            <option value="Government">Government</option>
                            <option value="NGO">NGO</option>
                            <option value="Private">Private</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <select class="form-select filter-input" id="status-filter">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="ms-3 flex-grow-1">
                            <div class="stats-badge">
                                <div class="icon-circle">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="badge-content">
                                    <span class="badge-label">Total Institutions</span>
                                    <span class="badge-value" style="margin-left: 5px;">{{ $institutions->total() ?? count($institutions) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Institutions Table Section -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="institutions-table" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Registration No.</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($institutions as $institution)
                            <tr>
                                <td>{{ $institution->registration_number }}</td>
                                <td>{{ $institution->name }}</td>
                                <td>{{ $institution->type }}</td>
                                <td>{{ $institution->email }}</td>
                                <td>{{ $institution->phone }}</td>
                                <td>
                                    <span class="badge status-badge bg-{{ $institution->status === 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($institution->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button type="button" 
                                           class="btn btn-sm btn-info text-white"
                                           onclick="viewInstitution('{{ $institution->organization_id }}')"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" 
                                           class="btn btn-sm btn-warning text-white"
                                           onclick="editInstitution('{{ $institution->organization_id }}')"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm {{ $institution->status === 'active' ? 'btn-danger' : 'btn-success' }}" 
                                                onclick="toggleStatus('{{ $institution->organization_id }}', '{{ $institution->status }}', this)"
                                                title="{{ $institution->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas {{ $institution->status === 'active' ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="mb-0">Institution Details</h4>
                <button type="button" class="btn-close" onclick="closeModal('viewModal')" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewModalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal('editModal')">&times;</span>
            <h4 class="mb-4">Edit Institution</h4>
            <div id="editModalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
    <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="//cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.2/dist/sweetalert2.all.min.js"></script>

    <script>
    $(document).ready(function() {
        // Initialize DataTable
        let table = $('#institutions-table').DataTable({
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            order: [[0, "desc"]],
            processing: true,
            language: {
                processing: '<div class="loader"></div><span class="loading-text">Loading data...</span>'
            },
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

        // Filter handling
        $('#search-filter').on('keyup', function() {
            table.search(this.value).draw();
        });

        $('#type-filter, #status-filter').on('change', function() {
            let typeVal = $('#type-filter').val();
            let statusVal = $('#status-filter').val();

            // Clear all filters first
            table.columns().search('').draw();

            // Apply filters if values exist
            if (typeVal) table.column(1).search(typeVal);
            if (statusVal) table.column(5).search(statusVal);

            table.draw();
        });
    });

    // Function to handle status toggle with SweetAlert
    function toggleStatus(institutionId, currentStatus, button) {
        const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
        const actionText = currentStatus === 'active' ? 'deactivate' : 'activate';

        Swal.fire({
            title: 'Confirm Status Change',
            html: `Are you sure you want to <strong>${actionText}</strong> this institution?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we update the status',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Make the AJAX request
                $.ajax({
                    url: `/admin/institutions/${institutionId}/toggle-status`,
                    type: 'POST',
                    success: function(response) {
                        if (response.success) {
                            let newStatus = response.newStatus;
                            let badgeClass = newStatus === 'active' ? 'success' : 'danger';
                            
                            // Update the status badge
                            $(button).closest('tr').find('.status-badge')
                                  .removeClass('bg-success bg-danger')
                                  .addClass(`bg-${badgeClass}`)
                                  .text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));

                            // Update the button
                            $(button)
                                .removeClass('btn-success btn-danger')
                                .addClass(newStatus === 'active' ? 'btn-danger' : 'btn-success')
                                .find('i')
                                .removeClass('fa-ban fa-check')
                                .addClass(newStatus === 'active' ? 'fa-ban' : 'fa-check');

                            // Update button title
                            $(button).attr('title', newStatus === 'active' ? 'Deactivate' : 'Activate');
                            
                            // Update onclick handler with new status
                            $(button).attr('onclick', `toggleStatus('${institutionId}', '${newStatus}', this)`);

                            Swal.fire({
                                title: 'Success!',
                                text: 'Institution status has been updated successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            throw new Error('Failed to update status');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Failed to update institution status',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    }

    // Modal functions
    function viewInstitution(id) {
        const modal = document.getElementById('viewModal');
        const content = document.getElementById('viewModalContent');
        
        content.innerHTML = `
            <div class="text-center">
                <i class="fas fa-spinner fa-spin fa-2x"></i>
                <p class="mt-2">Loading...</p>
            </div>
        `;
        
        modal.style.display = 'block';
        
        fetch(`/admin/institutions/${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (!data.success || !data.institution) {
                    throw new Error('Invalid response data');
                }
                
                const institution = data.institution;
                content.innerHTML = `
                    <div class="row">
                        <!-- Institution Details Column -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Basic Information</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="fw-bold">Registration Number:</label>
                                            <p>${institution.registration_number || 'N/A'}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold">Name:</label>
                                            <p>${institution.name}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold">Type:</label>
                                            <p>${institution.type}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold">Status:</label>
                                            <p>
                                                <span class="badge bg-${institution.status === 'active' ? 'success' : 'danger'}">
                                                    ${institution.status.charAt(0).toUpperCase() + institution.status.slice(1)}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Contact Information</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="fw-bold">Email:</label>
                                            <p>${institution.email}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold">Phone:</label>
                                            <p>${institution.phone || 'N/A'}</p>
                                        </div>
                                        <div class="col-12">
                                            <label class="fw-bold">Address:</label>
                                            <p>${institution.address || 'N/A'}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Approval Letter Column -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">
                                        <i class="fas fa-file-pdf text-danger me-2"></i>
                                        Approval Letter
                                    </h5>
                                    <div class="document-preview">
                                        ${institution.approval_letter_path ? 
                                            getDocumentPreview(`/storage/${institution.approval_letter_path}`) :
                                            `<div class="text-center text-muted py-5">
                                                <i class="fas fa-file-pdf fa-3x mb-3 text-danger"></i>
                                                <p>No approval letter uploaded</p>
                                            </div>`
                                        }
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            })
            .catch(error => {
                content.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Error loading institution details. Please try again.
                    </div>
                `;
                console.error('Error:', error);
            });
    }

    // Helper function to determine document preview
    function getDocumentPreview(path) {
        return `
            <div class="pdf-container">
                <iframe
                    src="${path}#toolbar=0"
                    type="application/pdf"
                    title="Approval Letter PDF"
                ></iframe>
                <div class="document-actions">
                    <button class="btn btn-outline-secondary btn-sm" onclick="printPDF('${path}')">
                        <i class="fas fa-print"></i>
                        Print
                    </button>
                    <a href="${path}" 
                       class="btn btn-primary btn-sm" 
                       download="approval_letter.pdf"
                       target="_blank">
                        <i class="fas fa-download"></i>
                        Download
                    </a>
                </div>
            </div>
        `;
    }

    // Function to handle PDF printing
    function printPDF(pdfUrl) {
        // Create a hidden iframe for printing
        const printFrame = document.createElement('iframe');
        printFrame.style.display = 'none';
        printFrame.src = pdfUrl;
        
        document.body.appendChild(printFrame);
        
        printFrame.onload = function() {
            try {
                printFrame.contentWindow.print();
            } catch (e) {
                // If direct printing fails, open in new tab
                window.open(pdfUrl, '_blank');
            }
            
            // Remove the iframe after printing
            setTimeout(() => {
                document.body.removeChild(printFrame);
            }, 1000);
        };
    }

    function editInstitution(id) {
        const modal = document.getElementById('editModal');
        const content = document.getElementById('editModalContent');
        
        content.innerHTML = `
            <div class="text-center">
                <i class="fas fa-spinner fa-spin fa-2x"></i>
                <p class="mt-2">Loading...</p>
            </div>
        `;
        
        modal.style.display = 'block';
        
        fetch(`/admin/institutions/${id}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (!data.success || !data.institution) {
                    throw new Error('Invalid response data');
                }

                const institution = data.institution;
                content.innerHTML = `
                    <form id="editInstitutionForm" onsubmit="updateInstitution(event, '${id}')" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Registration Number</label>
                                <input type="text" class="form-control" name="registration_number" value="${institution.registration_number || ''}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" value="${institution.name}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Type</label>
                                <select class="form-select" name="type" required>
                                    <option value="Government" ${institution.type === 'Government' ? 'selected' : ''}>Government</option>
                                    <option value="NGO" ${institution.type === 'NGO' ? 'selected' : ''}>NGO</option>
                                    <option value="Private" ${institution.type === 'Private' ? 'selected' : ''}>Private</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="${institution.email}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone" value="${institution.phone || ''}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="3">${institution.address || ''}</textarea>
                            </div>

                            <!-- Approval Letter Section -->
                            <div class="col-12">
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-file-pdf text-danger me-2"></i>
                                            Approval Letter
                                        </h6>
                                        
                                        ${institution.approval_letter_path ? `
                                            <div class="current-document mb-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <i class="fas fa-file-pdf text-danger me-2"></i>
                                                        <span>Current Approval Letter</span>
                                                    </div>
                                                    <div class="btn-group">
                                                        <a href="/storage/${institution.approval_letter_path}" 
                                                           class="btn btn-sm btn-outline-primary" 
                                                           target="_blank"
                                                           rel="noopener noreferrer">
                                                            <i class="fas fa-eye me-1"></i> View
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="deleteApprovalLetter('${id}')">
                                                            <i class="fas fa-trash me-1"></i> Delete
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        ` : ''}
                                        
                                        <div class="upload-new">
                                            <label class="form-label">
                                                ${institution.approval_letter_path ? 'Replace' : 'Upload'} Approval Letter
                                            </label>
                                            <input type="file" 
                                                   class="form-control" 
                                                   name="approval_letter" 
                                                   accept="application/pdf"
                                                   onchange="validatePdfFile(this)">
                                            <div class="form-text">
                                                Only PDF files are allowed (max 10MB)
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Cancel</button>
                            </div>
                        </div>
                    </form>
                `;
            })
            .catch(error => {
                console.error('Error:', error);
                content.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Error loading institution details: ${error.message}
                    </div>
                `;
            });
    }

    // Function to validate PDF file
    function validatePdfFile(input) {
        const file = input.files[0];
        const maxSize = 10 * 1024 * 1024; // 10MB

        if (file) {
            if (file.type !== 'application/pdf') {
                Swal.fire({
                    title: 'Invalid File Type',
                    text: 'Please upload only PDF files',
                    icon: 'error'
                });
                input.value = '';
                return false;
            }

            if (file.size > maxSize) {
                Swal.fire({
                    title: 'File Too Large',
                    text: 'File size should not exceed 10MB',
                    icon: 'error'
                });
                input.value = '';
                return false;
            }
        }
        return true;
    }

    // Function to delete approval letter
    function deleteApprovalLetter(institutionId) {
        Swal.fire({
            title: 'Delete Approval Letter?',
            text: 'Are you sure you want to delete the current approval letter? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/institutions/${institutionId}/approval-letter`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Approval letter has been deleted.',
                            icon: 'success'
                        }).then(() => {
                            // Refresh the modal content
                            editInstitution(institutionId);
                        });
                    } else {
                        throw new Error(data.message || 'Failed to delete approval letter');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        text: error.message || 'Failed to delete approval letter',
                        icon: 'error'
                    });
                });
            }
        });
    }

    // Function to update institution with file upload
    function updateInstitution(event, id) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        
        // Add _method field to simulate PUT request
        formData.append('_method', 'PUT');
        
        // Validate PDF file if one is selected
        const fileInput = form.querySelector('input[type="file"]');
        if (fileInput.files.length > 0 && !validatePdfFile(fileInput)) {
            return;
        }

        Swal.fire({
            title: 'Confirm Update',
            text: 'Are you sure you want to update this institution?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!',
            cancelButtonText: 'Cancel',
            customClass: {
                container: 'my-swal'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Updating...',
                    text: 'Please wait while we update the institution',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    customClass: {
                        container: 'my-swal'
                    },
                    didOpen: () => {
                        Swal.showLoading();
                        
                        fetch(`/admin/institutions/${id}`, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.text().then(text => {
                                    console.error('Response text:', text);
                                    throw new Error(`HTTP error! status: ${response.status}\n${text}`);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Update response:', data);
                            if (data.success) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: data.message || 'Institution updated successfully',
                                    icon: 'success',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        container: 'my-swal'
                                    }
                                }).then(() => {
                                    closeModal('editModal');
                                    location.reload();
                                });
                            } else {
                                throw new Error(data.message || 'Update failed');
                            }
                        })
                        .catch(error => {
                            console.error('Update error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: error.message || 'Failed to update institution',
                                icon: 'error',
                                confirmButtonText: 'OK',
                                customClass: {
                                    container: 'my-swal'
                                }
                            });
                        });
                    }
                });
            }
        });
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }
    </script>
@endpush
