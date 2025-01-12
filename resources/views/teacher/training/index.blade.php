<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('asset/images/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>TTP - Teacher Trainings</title>
    <style>
         :root {
            --primary-color: #009c95;
            --secondary-color: #cc3344;
            --hover-color: #007c77;
            --hover-dark: #006c68;
            --light-bg: #f8f8f8;
            --navbar-height: 70px;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-decoration: none;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: var(--light-bg);
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background-color: var(--primary-color);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--navbar-height);
            z-index: 1000;
            padding: 10px 0;
            display: flex;
            align-items: center;
        }
        .main-content {
            padding-top: var(--navbar-height);
            flex: 1;
        }
        @media (max-width: 767.98px) {
            .navbar {
                height: var(--navbar-height);
            }
            .main-content {
                padding-top: var(--navbar-height);
            }
        }
        .navbar .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding-right: 0;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            height: 100%;
            padding: 5px 0;
        }
        .logo {
            height: 45px;
            width: 45px;
            object-fit: contain;
            margin-right: 10px;
            vertical-align: middle;
        }
        .portal-name {
            color: white;
            font-size: 1.2rem;
            font-weight: 500;
            margin-left: 10px;
        }
        .user-profile {
            position: relative;
            margin-left: auto;
        }
        .profile-trigger {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: white;
            height: 40px;
            width: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 0;
            margin-right: 0 !important;
        }
        .profile-trigger:hover {
            background-color: var(--hover-color);
            border-color: var(--hover-color);
        }
        .profile-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 2px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 300px;
            display: none;
            z-index: 1000;
        }
        .profile-dropdown.show {
            display: block;
        }
        .profile-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }
        .profile-header i.fa-user {
            font-size: 3rem;
            color: var(--primary-color);
            background: #f5f5f5;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
        }
        .profile-name {
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
        .profile-email {
            color: #666;
            font-size: 0.9rem;
        }
        .profile-menu {
            padding: 10px 0;
        }
        .profile-dropdown .dropdown-item {
            padding: 10px 15px;
            margin: 5px 10px;
            border: 2px solid #eee;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        .profile-dropdown .dropdown-item:hover {
            background-color: var(--light-bg);
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        .profile-dropdown .dropdown-divider {
            margin: 8px 0;
        }
        .profile-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .profile-menu a:hover {
            background-color: #f5f5f5;
            color: var(--primary-color);
        }
        .profile-menu i {
            width: 20px;
            margin-right: 10px;
            color: var(--primary-color);
        }
        .divider {
            height: 1px;
            background-color: #eee;
            margin: 5px 0;
        }
        .nav-link {
            color: white !important;
            padding: 8px 15px !important;
            margin: 0 5px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            border-color: var(--hover-color);
            background-color: var(--hover-color);
            transform: translateY(-2px);
        }
        .nav-link.active {
            border-color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        @media (max-width: 991.98px) {
            .nav-link {
                margin: 5px 0;
                border-color: rgba(255, 255, 255, 0.15);
            }
        }
        .main_content {
            width: 90%;
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .main_content .row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 30px;
        }
        .main_content .col-custom {
            width: 250px;
            flex: 0 0 auto;
        }
        .main_content img {
            max-width: 100%;
            height: auto;
            width: 250px;
            flex-shrink: 0;
        }
        .welcome-text .name {
            color: var(--primary-color);
            margin: 0;
            font-size: 1.2rem;
            font-weight: 500;
        }
        .welcome-text .portal {
            color: #000;
            margin: 5px 0;
            font-size: 1.2rem;
            font-weight: 500;
        }
        .setup-btn {
            display: block;
            width: 100%;
            background: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            transition: background 0.3s ease;
            text-align: center;
        }
        .setup-btn:hover {
            background: var(--hover-color);
            color: white;
            text-decoration: none;
        }
        @media (max-width: 768px) {
            .portal-name {
                display: none;
            }
            .navbar > .container {
                padding: 0 15px;
                justify-content: space-between;
            }
            .user-profile {
                margin-left: 0;
            }
            .profile-trigger {
                position: relative;
                right: 0;
                margin-right: 0 !important;
                background-color: rgba(255, 255, 255, 0.1);
            }
            .profile-dropdown {
                right: 0;
                width: 280px;
            }
            .main_content {
                margin-top: 0;
                padding: 0 15px;
            }
            .main_content .row {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
            .welcome-text {
                font-size: 1rem;
            }
            .welcome-text .name,
            .welcome-text .portal {
                font-size: 1.1rem;
            }
        }
        @media (max-width: 480px) {
            #logo {
                height: 35px;
            }
            .profile-dropdown {
                width: calc(100vw - 30px);
                right: -15px;
            }
            .main_content {
                width: 100%;
                padding: 0 15px;
            }
        }
        .profile-menu a i {
            color: var(--primary-color);
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .profile-menu a:hover {
            background-color: var(--light-bg);
        }
        .profile-menu a:hover i {
            color: var(--hover-color);
        }

        .header {
            background: #00897b;
            color: #fff;
            padding: 15px 20px;
            border-radius: 8px 8px 0 0;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
            color: #00897b;
        }

        .icon-container {
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            color: white;
            margin-right: 15px;
            font-size: 24px;
        }

        /* Background colors for each card icon */
        .bg-primary {
            background-color: #007bff;
        }

        .bg-success {
            background-color: #28a745;
        }

        .bg-info {
            background-color: #17a2b8;
        }

        .bg-warning {
            background-color: #ffc107;
        }

        /* Card content styling */
        .stat-content {
            flex-grow: 1;
        }

        .stat-title {
            font-size: 16px;
            font-weight: 600;
            color: #6c757d;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #343a40;
            margin-top: 5px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .col-md-6 {
                flex: 0 0 100%;
            }
        }

        /* Enhanced responsive design for CPD Trainings */
        .card-statistics {
            height: 100%;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 0.5rem;
        }

        .card-statistics .card-body {
            padding: 1.25rem;
        }

        @media (max-width: 991.98px) {
            .col-lg-3 {
                margin-bottom: 1.5rem;
            }
            .col-lg-3:last-child {
                margin-bottom: 0;
            }
        }

        @media (max-width: 767.98px) {
            .card-statistics {
                margin-bottom: 1rem;
            }
            .container-fluid {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }

        @media screen and (max-width: 767px) {
            #numberOfRequests{
                display: none;
            }
            #templateCards{
                margin-top: 25px;
            }
        }

        /* Table Responsive Styles */
        .table-responsive {
            margin: 0;
            padding: 0;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
        }

        .table th {
            white-space: nowrap;
            background: #f8f9fa;
            padding: 1rem;
            font-weight: 600;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
        }

        /* Mobile Table Styles */
        @media (max-width: 767.98px) {
            .table-responsive {
                border: 0;
            }

            .table {
                display: block;
                width: 100%;
            }

            .table thead {
                display: none;
            }

            .table tbody,
            .table tr,
            .table td {
                display: block;
                width: 100%;
            }

            .table tr {
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
                border-radius: 0.5rem;
                background: white;
            }

            .table td {
                text-align: left;
                padding: 0.75rem;
                position: relative;
                padding-left: 50%;
                border: none;
                border-bottom: 1px solid #eee;
            }

            .table td:last-child {
                border-bottom: none;
            }

            .table td::before {
                content: attr(data-label);
                position: absolute;
                left: 0.75rem;
                width: 45%;
                font-weight: 600;
                text-align: left;
            }

            .table td:last-child {
                text-align: center;
                padding-left: 0.75rem;
            }

            .table td:last-child::before {
                display: none;
            }

            .btn-sm {
                padding: 0.5rem 0.75rem;
                margin: 0.25rem;
            }
        }

        /* Status Badge Styles */
        .badge {
            padding: 0.5rem 0.75rem;
            font-weight: 500;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="container">
            <a href="{{ route('teacher.dashboard') }}" class="navbar-brand">
                <img src="{{ asset('asset/images/logo.png') }}" alt="Logo" class="logo">
                <span class="portal-name">Tanzania Teacher Portal</span>
            </a>
            <div class="user-profile">
                <button class="profile-trigger" onclick="toggleDropdown()" style="margin-right: -40px;">
                    <i class="fas fa-user"></i>
                </button>

                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-header">
                        <i class="fas fa-user"></i>
                        <div class="profile-name">{{ Auth::user()->name }}</div>
                        <div class="profile-email">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="profile-menu">
                        <a href="{{ route('teacher.dashboard') }}"><i class="fas fa-home text-prime"></i>Home</a>
                        <a href="{{ route('teacher.training') }}"><i class="fas fa-certificate text-prime"></i>Training</a>
                        <a href="{{ route('teacher.settings') }}"><i class="fas fa-cog text-prime"></i>Account settings</a>
                        <div class="divider"></div>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt text-prime"></i>Logout
                            </a>
                        </form>
                    </div>
                </div>
            </div>
    </div>
</nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="row mt-4">
            <div class="col-lg-3 col-md-6" id="templateCards">
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-container bg-primary">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="stat-content">
                                <h5 class="stat-title">Total Sessions</h5>
                                <p class="stat-value">1000</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-container bg-success">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="stat-content">
                                <h5 class="stat-title">Attended Sessions</h5>
                                <p class="stat-value">200</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="col-lg-3 col-md-6">
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-container bg-info">
                                <i class="fas fa-chalkboard"></i>
                            </div>
                            <div class="stat-content">
                                <h5 class="stat-title">Pending Sessions</h5>
                                <p class="stat-value">100</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="col-lg-3 col-md-6">
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-container bg-warning">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="stat-content">
                                <h5 class="stat-title">Rejected Sessions</h5>
                                <p class="stat-value">0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header" style="background-color: #009c95; color: white;">
                        <h5 class="mb-0">Teacher Training Sessions</h5>
                    </div>
                    <div class="card-body">
                        <!-- Filters Section -->
                        <div class="row mb-4">
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <select class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Date Range</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Region</label>
                                <select class="form-select">
                                    <option value="">All Regions</option>
                                    <option value="arusha">Arusha</option>
                                    <option value="dar">Dar es Salaam</option>
                                    <option value="dodoma">Dodoma</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Training Type</label>
                                <select class="form-select">
                                    <option value="">All Types</option>
                                    <option value="workshop">Workshop</option>
                                    <option value="seminar">Seminar</option>
                                    <option value="course">Course</option>
                                </select>
                            </div>
                        </div>

                        <!-- Search and Actions Row -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search requests...">
                                    <button class="btn" style="background-color: #009c95; color: white;" type="button">
                                        <i class="fas fa-search me-1"></i> Search
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <button class="btn" style="background-color: #007c77; color: white;" id="numberOfRequests">
                                     0 Requests
                                </button>
                            </div>
                        </div>

                        <!-- Table Section -->
                        <div class="table-scroll-container">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead style="background-color: #f8f9fa;">
                                        <tr>
                                            <th>Training Title</th>
                                            <th>Requested By</th>
                                            <th>Date Requested</th>
                                            <th>Training Date</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td data-label="Training Title">Effective Teaching Methods</td>
                                            <td data-label="Requested By">John Doe</td>
                                            <td data-label="Date Requested">2024-01-15</td>
                                            <td data-label="Training Date">2024-02-01</td>
                                            <td data-label="Location">Arusha</td>
                                            <td data-label="Status">
                                                <span class="badge bg-warning">Pending</span>
                                            </td>
                                            <td data-label="Actions">
                                                <button class="btn btn-sm" style="background-color: #009c95; color: white;" title="View Details" onclick="viewTraining(1)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success" title="Accept" onclick="acceptTraining(1)">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" title="Reject" onclick="showRejectModal(1)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info text-white" title="Reporting" onclick="redirectToReportingPage()">
                                                    <i class="fa fa-comment"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td data-label="Training Title">Effective immunology</td>
                                            <td data-label="Requested By">Anola Godfrey</td>
                                            <td data-label="Date Requested">2024-01-15</td>
                                            <td data-label="Training Date">2024-02-01</td>
                                            <td data-label="Location">Arusha</td>
                                            <td data-label="Status">
                                                <span class="badge bg-success">Approved</span>
                                            </td>
                                            <td data-label="Actions">
                                                <button class="btn btn-sm" style="background-color: #009c95; color: white;" title="View Details" onclick="viewTraining(1)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success" title="Accept" onclick="acceptTraining(1)">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" title="Reject" onclick="showRejectModal(1)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info text-white" title="Reporting" onclick="redirectToReportingPage()">
                                                    <i class="fa fa-comment"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td data-label="Training Title"> Teaching Methodology</td>
                                            <td data-label="Requested By">Kasano junior</td>
                                            <td data-label="Date Requested">2024-01-15</td>
                                            <td data-label="Training Date">2024-02-01</td>
                                            <td data-label="Location">Arusha</td>
                                            <td data-label="Status">
                                                <span class="badge bg-danger">Rejected</span>
                                            </td>
                                            <td data-label="Actions">
                                                <button class="btn btn-sm" style="background-color: #009c95; color: white;" title="View Details" onclick="viewTraining(1)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success" title="Accept" onclick="acceptTraining(1)">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" title="Reject" onclick="showRejectModal(1)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info text-white" title="Reporting" onclick="redirectToReportingPage()">
                                                    <i class="fa fa-comment"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #009c95; color: white;">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Training Request</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="rejectForm">
                        <input type="hidden" id="trainingId" name="trainingId">
                        <div class="mb-3">
                            <label for="rejectReason" class="form-label fw-bold">Reason for Rejection</label>
                            <textarea class="form-control" id="rejectReason" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="rejectTraining()">Submit Rejection</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Training Modal -->
    <div class="modal fade" id="viewTrainingModal" tabindex="-1" aria-labelledby="viewTrainingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #009c95; color: white;">
                    <h5 class="modal-title" id="viewTrainingModalLabel">Training Request Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Training Title:</label>
                            <p id="viewTitle">Effective Teaching Methods</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Requested By:</label>
                            <p id="viewRequestedBy">John Doe</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Date Requested:</label>
                            <p id="viewDateRequested">2024-01-15</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Training Date:</label>
                            <p id="viewTrainingDate">2024-02-01</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Location:</label>
                            <p id="viewLocation">Arusha</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Status:</label>
                            <p id="viewStatus"><span class="badge" style="background-color: #007c77;">Pending</span></p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="fw-bold">Description:</label>
                            <p id="viewDescription">This training focuses on modern teaching methodologies and classroom management techniques.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" style="background-color: #006c68; color: white;" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function redirectToReportingPage() {
            // Redirects to the reporting page
            window.location.href = 'reporting.html'; // Replace with your reporting page's actual URL
        }
    </script>
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const profile = document.querySelector('.user-profile');
                if (!profile.contains(event.target)) {
                    dropdown.classList.remove('show');
                }
            });
        }
    </script>
    <!-- Include SweetAlert2 Library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="asset/js/CPD/cpd_training.js"></script>
    <script src="assets/js/main.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scrollContainer = document.querySelector('.table-scroll-container');
            const tableResponsive = document.querySelector('.table-responsive');

            function checkScroll() {
                if (tableResponsive.scrollWidth > tableResponsive.clientWidth) {
                    scrollContainer.classList.add('has-scroll');
                } else {
                    scrollContainer.classList.remove('has-scroll');
                }
            }

            // Check on load and resize
            checkScroll();
            window.addEventListener('resize', checkScroll);

            // Check when content changes
            const observer = new MutationObserver(checkScroll);
            observer.observe(tableResponsive, { childList: true, subtree: true });
        });
    </script>
</body>
</html>