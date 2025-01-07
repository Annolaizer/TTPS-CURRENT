<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('asset/images/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>TTPS - Organisation Dashboard</title>
    <style>
        :root {
            --primary-color: #009c95;
            --secondary-color: #cc3344;
            --hover-color: #007c77;
            --hover-dark: #006c68;
            --light-bg: #f8f9fa;
            --navbar-height: 70px;
            --container-max-width: 1200px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-decoration: none;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            padding-top: var(--navbar-height);
            margin: 0;
            color: #333;
        }

        .navbar {
            background-color: var(--primary-color);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1020;
            min-height: var(--navbar-height);
            padding: 0.625rem 0;
            margin: 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #logo {
            height: 40px;
            width: auto;
        }

        .portal-name {
            color: white;
            font-size: 1.2rem;
            font-weight: 500;
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
            padding: 0;
        }

        .profile-trigger:hover {
            background-color: var(--hover-color);
            border-color: var(--hover-color);
        }

        .profile-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 300px;
            display: none;
            z-index: 1000;
            overflow: hidden;
        }

        .profile-dropdown.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-info {
            padding: 1rem;
            background: #1e40af;  
            color: white;
            border-radius: 12px 12px 0 0;
        }

        .profile-info .welcome-text {
            font-size: 0.75rem;
            opacity: 0.8;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        .profile-info .user-details {
            margin-bottom: 0.5rem;
        }

        .profile-info .user-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.125rem;
            color: white;
            letter-spacing: 0.01em;
        }

        .profile-info .user-email {
            font-size: 0.8rem;
            opacity: 0.8;
            margin-bottom: 0.5rem;
        }

        .profile-info .org-section {
            padding-top: 0.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .profile-info .org-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            opacity: 0.7;
            margin-bottom: 0.125rem;
        }

        .profile-info .org-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: white;
        }

        .profile-avatar {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
        }

        .profile-avatar i {
            font-size: 1.2rem;
            color: white;
        }

        .profile-actions {
            padding: 1.5rem;
        }

        .profile-actions a {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #666;
            text-decoration: none;
            padding: 8px 0;
            transition: color 0.3s ease;
        }

        .profile-actions a:hover {
            color: var(--primary-color);
        }

        .dashboard-stats {
            padding: 2rem 0;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            background: var(--primary-color);
            color: white;
        }

        .stat-title {
            font-size: 0.875rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .stat-description {
            font-size: 0.875rem;
            color: #888;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 0.5rem 0;
            }

            .portal-name {
                font-size: 1rem;
            }

            .stat-card {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <a href="#" class="navbar-brand">
                <img src="{{ asset('asset/images/logo.png') }}" alt="Logo" id="logo">
                <span class="portal-name">Tanzania Teacher Portal</span>
            </a>
            <div class="user-profile">
                <button class="profile-trigger">
                    <i class="fas fa-user"></i>
                </button>
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-info">
                        <div class="profile-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-details">
                            <p class="welcome-text">Welcome back!</p>
                            <h3 class="user-name">{{ Auth::user()->name }}</h3>
                            <p class="user-email">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="org-section">
                            <p class="org-label">Organization</p>
                            <p class="org-name">{{ $organization->name }}</p>
                        </div>
                    </div>
                    <div class="profile-actions">
                        <a href="{{ route('organization.trainings') }}">
                            <i class="fas fa-chalkboard-teacher"></i>
                            Trainings
                        </a>
                        <a href="{{ route('organization.profile') }}">
                            <i class="fas fa-user-circle"></i>
                            Organisation Profile
                        </a>
                        <a href="{{ route('organization.profile.setup') }}">
                            <i class="fas fa-cog"></i>
                            Settings
                        </a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="container dashboard-stats">
        <div class="row">
            <!-- Total Trainings -->
            <div class="col-md-3 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-title">Total Trainings</div>
                        <div class="stat-value">24</div>
                        <div class="stat-description">Active training sessions</div>
                    </div>
                </div>
            </div>

            <!-- Participants -->
            <div class="col-md-3 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-title">Participants</div>
                        <div class="stat-value">156</div>
                        <div class="stat-description">Enrolled in trainings</div>
                    </div>
                </div>
            </div>

            <!-- Completed Trainings -->
            <div class="col-md-3 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-title">Completed</div>
                        <div class="stat-value">18</div>
                        <div class="stat-description">Successfully completed</div>
                    </div>
                </div>
            </div>

            <!-- Success Rate -->
            <div class="col-md-3 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-title">Success Rate</div>
                        <div class="stat-value">75%</div>
                        <div class="stat-description">Average completion rate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileTrigger = document.querySelector('.profile-trigger');
            const profileDropdown = document.getElementById('profileDropdown');
            let isDropdownOpen = false;

            // Toggle dropdown on profile trigger click
            profileTrigger.addEventListener('click', function(event) {
                event.stopPropagation();
                isDropdownOpen = !isDropdownOpen;
                profileDropdown.classList.toggle('show', isDropdownOpen);
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (isDropdownOpen && !profileDropdown.contains(event.target)) {
                    profileDropdown.classList.remove('show');
                    isDropdownOpen = false;
                }
            });

            // Prevent dropdown from closing when clicking inside it
            profileDropdown.addEventListener('click', function(event) {
                event.stopPropagation();
            });

            // Close dropdown when pressing escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && isDropdownOpen) {
                    profileDropdown.classList.remove('show');
                    isDropdownOpen = false;
                }
            });
        });
    </script>
</body>
</html>