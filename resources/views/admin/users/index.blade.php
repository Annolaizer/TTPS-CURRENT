@extends('admin.master_layout.index')

@section('title', 'User Management - TTP Admin')

@push('styles')
<style>
    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    .user-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 15px;
        transition: transform 0.2s;
    }
    .user-card:hover {
        transform: translateY(-2px);
    }
    .status-badge {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 500;
    }
    .status-active {
        background-color: #e7f7ee;
        color: #1d804f;
    }
    .status-inactive {
        background-color: #fee7e7;
        color: #dc3545;
    }
</style>
@endpush

@section('content')
<!-- Main Content -->
<main class="main-content">
    <div class="container-fluid">
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">User Management</h4>
                <button class="btn btn-primary" onclick="openCreateModal()">
                    <i class="fas fa-plus"></i> Add New User
                </button>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Search</label>
                            <input type="text" class="form-control" id="searchInput" placeholder="Search users...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Role</label>
                            <select class="form-control" id="roleFilter">
                                <option value="">All Roles</option>
                                <option value="admin">Admin</option>
                                <option value="super_administrator">Super Administrator</option>
                                <option value="teacher">Teacher</option>
                                <option value="organization">Organization</option>
                                <option value="cpd_facilitator">CPD Facilitator</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Sort By</label>
                            <select class="form-control" id="sortBy">
                                <option value="created_at">Date Created</option>
                                <option value="name">Name</option>
                                <option value="email">Email</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users List -->
            <div id="usersList">
                <!-- Users will be loaded here -->
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="showing-text">
                    Showing <span id="showingStart">0</span> to <span id="showingEnd">0</span> of <span id="totalUsers">0</span> users
                </div>
                <div class="pagination-container">
                    <!-- Pagination will be rendered here -->
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Create/Edit User Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Full Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Password</label>
                                <input type="password" class="form-control" name="password">
                                <small class="text-muted">Leave empty to keep current password when editing</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Role</label>
                                <select class="form-control" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="super_administrator">Super Administrator</option>
                                    <option value="teacher">Teacher</option>
                                    <option value="organization">Organization</option>
                                    <option value="cpd_facilitator">CPD Facilitator</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label>Title</label>
                                <select class="form-control" name="title" required>
                                    <option value="">Select Title</option>
                                    <option value="Mr">Mr</option>
                                    <option value="Mrs">Mrs</option>
                                    <option value="Ms">Ms</option>
                                    <option value="Dr">Dr</option>
                                    <option value="Prof">Prof</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label>First Name</label>
                                <input type="text" class="form-control" name="first_name" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label>Last Name</label>
                                <input type="text" class="form-control" name="last_name" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label>Phone Number</label>
                                <input type="text" class="form-control" name="phone_number" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label>Gender</label>
                                <select class="form-control" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label>Date of Birth</label>
                                <input type="date" class="form-control" name="date_of_birth" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveUser()">Save User</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentPage = 1;
let currentUserId = null;
let users = []; // Store users data

// Setup CSRF token for all AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Load users on page load
$(document).ready(function() {
    loadUsers();
    
    // Set up event listeners for filters
    $('#searchInput, #roleFilter, #statusFilter, #sortBy').on('change', function() {
        currentPage = 1;
        loadUsers();
    });
    
    $('#searchInput').on('keyup', debounce(function() {
        currentPage = 1;
        loadUsers();
    }, 300));
});

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function loadUsers() {
    $.ajax({
        url: '{{ route("admin.users.data") }}',
        method: 'GET',
        data: {
            page: currentPage,
            search: $('#searchInput').val(),
            role: $('#roleFilter').val(),
            status: $('#statusFilter').val(),
            sort_by: $('#sortBy').val(),
            sort_order: 'desc',
            per_page: 10
        },
        success: function(response) {
            users = response.data; // Store users data
            renderUsers(response);
            updatePagination(response);
            updateShowingText(response);
        },
        error: function(xhr) {
            showError('Failed to load users');
            console.error('Load users error:', xhr);
        }
    });
}

function renderUsers(response) {
    const users = response.data;
    let html = '';
    
    users.forEach(user => {
        html += `
        <div class="user-card p-3">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <h6 class="mb-1">${user.name}</h6>
                    <small class="text-muted">${user.email}</small>
                </div>
                <div class="col-md-2">
                    <span class="badge bg-secondary">${formatRole(user.role)}</span>
                </div>
                <div class="col-md-2">
                    <span class="status-badge ${user.status === 'active' ? 'status-active' : 'status-inactive'}">
                        ${user.status.charAt(0).toUpperCase() + user.status.slice(1)}
                    </span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted">Created: ${formatDate(user.created_at)}</small>
                </div>
                <div class="col-md-2 text-end">
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary" onclick="editUser('${user.user_id}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-${user.status === 'active' ? 'warning' : 'success'}" 
                                onclick="toggleUserStatus('${user.user_id}')">
                            <i class="fas fa-${user.status === 'active' ? 'ban' : 'check'}"></i>
                        </button>
                        ${user.role !== 'super_administrator' ? `
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteUser('${user.user_id}')">
                            <i class="fas fa-trash"></i>
                        </button>
                        ` : ''}
                    </div>
                </div>
            </div>
        </div>`;
    });
    
    $('#usersList').html(html);
}

function formatRole(role) {
    return role.split('_').map(word => 
        word.charAt(0).toUpperCase() + word.slice(1)
    ).join(' ');
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function updatePagination(response) {
    const totalPages = Math.ceil(response.total / response.per_page);
    let html = `<nav><ul class="pagination mb-0">`;
    
    // Previous button
    html += `
        <li class="page-item ${response.current_page === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${response.current_page - 1})">Previous</a>
        </li>`;
    
    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= response.current_page - 1 && i <= response.current_page + 1)) {
            html += `
                <li class="page-item ${response.current_page === i ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                </li>`;
        } else if (i === response.current_page - 2 || i === response.current_page + 2) {
            html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
    }
    
    // Next button
    html += `
        <li class="page-item ${response.current_page === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${response.current_page + 1})">Next</a>
        </li>`;
    
    html += `</ul></nav>`;
    $('.pagination-container').html(html);
}

function updateShowingText(response) {
    const start = (response.current_page - 1) * response.per_page + 1;
    const end = Math.min(start + response.per_page - 1, response.total);
    $('#showingStart').text(start);
    $('#showingEnd').text(end);
    $('#totalUsers').text(response.total);
}

function changePage(page) {
    currentPage = page;
    loadUsers();
}

function openCreateModal() {
    currentUserId = null;
    $('#modalTitle').text('Add New User');
    $('#userForm')[0].reset();
    $('#userModal').modal('show');
}

function editUser(userId) {
    currentUserId = userId;
    $('#modalTitle').text('Edit User');
    
    $.ajax({
        url: `/admin/users/${userId}/edit`,
        method: 'GET',
        success: function(response) {
            const user = response.user;
            const form = $('#userForm');
            
            // Fill form fields
            form.find('[name="name"]').val(user.name);
            form.find('[name="email"]').val(user.email);
            form.find('[name="role"]').val(user.role);
            form.find('[name="title"]').val(user.personal_info.title);
            form.find('[name="first_name"]').val(user.personal_info.first_name);
            form.find('[name="last_name"]').val(user.personal_info.last_name);
            form.find('[name="phone_number"]').val(user.personal_info.phone_number);
            form.find('[name="gender"]').val(user.personal_info.gender);
            form.find('[name="date_of_birth"]').val(user.personal_info.date_of_birth);
            
            $('#userModal').modal('show');
        },
        error: function(xhr) {
            showError('Failed to load user data');
        }
    });
}

function saveUser() {
    const formData = new FormData($('#userForm')[0]);
    const url = currentUserId ? `/admin/users/${currentUserId}` : '/admin/users';
    const method = currentUserId ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                showSuccess(response.message);
                loadUsers();
            } else {
                showError(response.message || 'Failed to save user');
            }
        },
        error: function(xhr) {
            const message = xhr.responseJSON?.message || 'Failed to save user';
            showError(message);
            console.error('Save user error:', xhr);
        }
    });
}

function toggleUserStatus(userId) {
    const user = users.find(u => u.user_id === userId);
    if (!user) return;

    const newStatus = user.status === 'active' ? 'inactive' : 'active';
    const message = `Are you sure you want to ${user.status === 'active' ? 'deactivate' : 'activate'} this user?`;

    Swal.fire({
        title: 'Confirm Status Change',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, change it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/users/${userId}/toggle-status`,
                method: 'POST',
                success: function(response) {
                    if (response.success) {
                        showSuccess(response.message);
                        loadUsers();
                    } else {
                        showError(response.message || 'Failed to update user status');
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Failed to update user status';
                    showError(message);
                    console.error('Status toggle error:', xhr);
                }
            });
        }
    });
}

function deleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user?')) return;
    
    $.ajax({
        url: `/admin/users/${userId}`,
        method: 'DELETE',
        success: function(response) {
            if (response.success) {
                showSuccess(response.message);
                loadUsers();
            } else {
                showError(response.message || 'Failed to delete user');
            }
        },
        error: function(xhr) {
            const message = xhr.responseJSON?.message || 'Failed to delete user';
            showError(message);
            console.error('Delete user error:', xhr);
        }
    });
}

function showSuccess(message) {
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: message,
        timer: 2000,
        showConfirmButton: false
    });
}

function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message
    });
}
</script>
@endpush
