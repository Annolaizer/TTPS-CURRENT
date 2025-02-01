<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('asset/images/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
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
        .navbar-brand {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
        }
        .navbar-brand:hover {
            color: rgba(255, 255, 255, 0.9);
        }
        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }
        .portal-name {
            font-size: 1.2rem;
            font-weight: bold;
        }
        .user-profile {
            position: relative;
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
        }
        .profile-trigger:hover {
            background-color: var(--hover-color);
            border-color: var(--hover-color);
        }
        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            min-width: 250px;
            display: none;
            z-index: 1000;
            margin-top: 10px;
        }
        .profile-dropdown.show {
            display: block;
        }
        .profile-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }
        .profile-header i {
            font-size: 48px;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        .profile-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .profile-email {
            color: #666;
            font-size: 0.9em;
        }
        .profile-menu {
            padding: 10px 0;
        }
        .profile-menu a {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .profile-menu a:hover {
            background-color: var(--light-bg);
            color: var(--primary-color);
        }
        .profile-menu i {
            margin-right: 10px;
            width: 20px;
            color: var(--primary-color);
        }
        .divider {
            height: 1px;
            background-color: #eee;
            margin: 5px 0;
        }
        .main-content {
            padding-top: calc(var(--navbar-height) + 20px);
            padding-left: 20px;
            padding-right: 20px;
            flex: 1;
        }
        .card-statistics {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            margin-bottom: 20px;
        }
        .card-statistics:hover {
            transform: translateY(-5px);
        }
        .icon-container {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        .icon-container i {
            color: white;
            font-size: 24px;
        }
        .stat-content {
            flex: 1;
        }
        .stat-title {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
        .stat-value {
            margin: 5px 0 0;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .table-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .table-card .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 15px 20px;
        }
        .table-card .card-body {
            padding: 20px;
        }
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 15px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            color: white !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--hover-color) !important;
            border-color: var(--hover-color) !important;
            color: white !important;
        }
        .btn-info {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        .btn-info:hover {
            background-color: var(--hover-color);
            border-color: var(--hover-color);
            color: white;
        }
        .badge {
            padding: 6px 12px;
            font-weight: 500;
            border-radius: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ route('teacher.dashboard') }}" class="navbar-brand">
                <img src="{{ asset('asset/images/logo.png') }}" alt="Logo" height="40">
                <span class="portal-name">Tanzania Teacher Portal</span>
            </a>
            <div class="user-profile">
                <button class="profile-trigger" onclick="toggleDropdown()">
                    <i class="fas fa-user"></i>
                </button>
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-header">
                        <i class="fas fa-user"></i>
                        <div class="profile-name">{{ Auth::user()->name }}</div>
                        <div class="profile-email">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="profile-menu">
                        <a href="{{ route('teacher.dashboard') }}">
                            <i class="fas fa-home"></i>Home
                        </a>
                        <a href="{{ route('teacher.training') }}">
                            <i class="fas fa-certificate"></i>Training
                        </a>
                        <a href="{{ route('teacher.settings') }}">
                            <i class="fas fa-cog"></i>Account settings
                        </a>
                        <div class="divider"></div>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>Logout
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-container bg-primary">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="stat-content">
                                <h5 class="stat-title">Total Sessions</h5>
                                <p class="stat-value">{{ $stats['total'] }}</p>
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
                                <h5 class="stat-title">Completed</h5>
                                <p class="stat-value">{{ $stats['completed'] }}</p>
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
                                <h5 class="stat-title">Ongoing</h5>
                                <p class="stat-value">{{ $stats['ongoing'] }}</p>
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
                                <h5 class="stat-title">Upcoming</h5>
                                <p class="stat-value">{{ $stats['upcoming'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card table-card">
                    <div class="card-header">
                        <h5 class="mb-0">Teacher Training Sessions</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="trainingsTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Training Title</th>
                                        <th>Organization</th>
                                        <th>Duration</th>
                                        <th>Dates</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trainings as $training)
                                    <tr>
                                        <td>{{ $training->title }}</td>
                                        <td>{{ $training->organization_name }}</td>
                                        <td>{{ $training->duration_days }} days</td>
                                        <td>
                                            @if($training->start_date && $training->end_date)
                                                {{ \Carbon\Carbon::parse($training->start_date)->format('M d') }} - 
                                                {{ \Carbon\Carbon::parse($training->end_date)->format('M d, Y') }}
                                            @else
                                                TBD
                                            @endif
                                        </td>
                                        <td>
                                            @if($training->status === 'completed')
                                                <span class="badge bg-success">Completed</span>
                                            @elseif($training->status === 'verified' && $training->start_date <= now() && $training->end_date >= now())
                                                <span class="badge bg-info">Ongoing</span>
                                            @elseif($training->status === 'verified' && $training->start_date > now())
                                                <span class="badge bg-warning">Upcoming</span>
                                            @elseif($training->status === 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($training->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" 
                                                    onclick="viewDetails('{{ $training->training_id }}')"
                                                    title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        // Add CSRF token to all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        function toggleDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');

            // Close dropdown when clicking outside
            document.addEventListener('click', function closeDropdown(e) {
                if (!e.target.closest('.user-profile')) {
                    dropdown.classList.remove('show');
                    document.removeEventListener('click', closeDropdown);
                }
            });
        }

        $(document).ready(function() {
            $('#trainingsTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[3, 'desc']], // Sort by date column descending
                columnDefs: [
                    { 
                        targets: -1,
                        orderable: false,
                        searchable: false,
                        width: '80px'
                    }
                ],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });
        });

        function viewDetails(id) {
            if (!id) return;
            window.location.href = `{{ route('teacher.training.show', ['id' => ':id']) }}`.replace(':id', id);
        }
    </script>
</body>
</html>