<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('asset/images/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Account Settings - Teacher Portal Tanzania</title>
    <style>
        :root {
            --primary-color: #009c95;
            --secondary-color: #cc3344;
            --hover-color: #007c77;
            --hover-dark: #006c68;
            --light-bg: #f8f8f8;
            --navbar-height: 70px;
            --topbar-height: 40px;
            --container-max-width: 1200px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: var(--light-bg);
            padding-top: calc(var(--navbar-height) + var(--topbar-actual-height, var(--topbar-height)));
            margin: 0;
        }

        .top_bar {
            width: 100%;
            background-color: var(--secondary-color);
            color: white;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            position: fixed;
            top: 0;
            z-index: 1030;
            min-height: var(--topbar-height);
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1.4;
            text-align: center;
        }

        .navbar {
            background-color: var(--primary-color);
            transition: all 0.3s ease;
            position: fixed;
            width: 100%;
            top: var(--topbar-actual-height, var(--topbar-height));
            z-index: 1020;
            min-height: var(--navbar-height);
            padding: 0.625rem 0;
            margin: 0;
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

        .divider {
            height: 1px;
            background-color: #eee;
            margin: 5px 0;
        }

        .main_content {
            width: 90%;
            max-width: 800px;
            margin: 100px auto 0;
            padding: 0 20px;
        }

        .back-link {
            color: var(--primary-color);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .back-link:hover {
            color: var(--hover-color);
        }

        .settings-header {
            margin-bottom: 30px;
        }

        .settings-header h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 10px;
        }

        .settings-header p {
            color: #666;
            margin-bottom: 5px;
        }

        .settings-actions {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-top: 30px;
        }

        .action-button {
            display: flex;
            align-items: center;
            padding: 20px;
            background: white;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
            border-bottom: 1px solid #eee;
        }

        .action-button:last-child {
            border-bottom: none;
        }

        .action-button:hover {
            background: #f8f8f8;
            color: var(--primary-color);
        }

        .action-icon {
            width: 40px;
            height: 40px;
            background: var(--light-bg);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary-color);
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .action-button:hover .action-icon {
            background: var(--primary-color);
            color: white;
        }

        .action-content {
            flex: 1;
        }

        .action-title {
            font-weight: 500;
            margin-bottom: 2px;
        }

        .action-description {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
        }

        .action-arrow {
            color: #ccc;
            transition: all 0.3s ease;
        }

        .action-button:hover .action-arrow {
            color: var(--primary-color);
            transform: translateX(5px);
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 10px;
            border: none;
        }

        .modal-header {
            background: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body {
            padding: 20px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 156, 149, 0.25);
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--hover-color);
            border-color: var(--hover-color);
        }

        @media (max-width: 768px) {
            .portal-name {
                display: none;
            }

            .top_bar {
                font-size: 0.75rem;
                padding: 0.5rem 20px;
                height: auto;
                text-align: justify;
                width: 100%;
                hyphens: auto;
                word-wrap: break-word;
                position: fixed;
                top: 0;
            }

            .navbar {
                position: fixed;
                top: var(--topbar-actual-height, 2.5rem);
                transform: none;
                margin: 0;
                transition: top 0.3s ease;
            }

            .main_content {
                margin-top: 150px;
                padding: 0 15px;
            }

            .settings-header h1 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 767.98px) {
            :root {
                --navbar-height: 60px;
            }
            .navbar-brand {
                font-size: 1rem;
            }
            #logo {
                height: 35px;
                width: 35px;
            }
            .portal-name {
                font-size: 1rem;
                margin-left: 5px;
            }
            .profile-dropdown {
                width: calc(100vw - 20px);
                right: 10px;
            }
            .profile-header {
                padding: 15px;
            }
            .profile-header i.fa-user {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <a href="{{ route('teacher.dashboard') }}" class="navbar-brand">
                <img src="{{ asset('asset/images/logo.png') }}" alt="Logo" class="logo" style="width: 50px;">
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
                        <a href="{{ route('teacher.training.index') }}"><i class="fas fa-certificate text-prime"></i>Training</a>
                        <a href="#"><i class="fas fa-cog text-prime"></i>Account settings</a>
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
    <div class="main_content">
        <a href="user_dashboard.html" class="back-link">
            <i class="fas fa-arrow-alt-circle-left"></i>
            <span><i>Return to teacher Portal</i></span>/
            <span class="text-prime">Account settings</span>
        </a>

        <div class="settings-header">
            <p>ANOLA Juventus MATIMBWI</p>
            <p>Account type: Teacher</p>
        </div>

        <div class="settings-actions">
            <a href="#" class="action-button" data-bs-toggle="modal" data-bs-target="#emailModal">
                <div class="action-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="action-content">
                    <div class="action-title">Change email</div>
                    <p class="action-description">Update your email address</p>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>
            <a href="#" class="action-button" data-bs-toggle="modal" data-bs-target="#passwordModal">
                <div class="action-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="action-content">
                    <div class="action-title">Update account password</div>
                    <p class="action-description">Change your account password</p>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>
        </div>
    </div>

    <!-- Email Change Modal -->
    <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailModalLabel">Change Email Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="emailForm">
                        <div class="mb-3">
                            <label for="currentEmail" class="form-label">Current Email</label>
                            <input type="email" class="form-control" id="currentEmail" value="annolamatimbwi@gmail.com" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="newEmail" class="form-label">New Email</label>
                            <input type="email" class="form-control" id="newEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmEmail" class="form-label">Confirm New Email</label>
                            <input type="email" class="form-control" id="confirmEmail" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Email</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Password Change Modal -->
    <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">Update Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="passwordForm">
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="currentPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="newPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to update topbar height
        function updateTopBarHeight() {
            const topBar = document.querySelector('.top_bar');
            if (topBar) {
                const height = topBar.offsetHeight;
                document.documentElement.style.setProperty('--topbar-actual-height', height + 'px');
            }
        }
        
        // Update on load and resize
        updateTopBarHeight();
        window.addEventListener('resize', updateTopBarHeight);
        // Update when content might change
        window.addEventListener('DOMContentLoaded', updateTopBarHeight);

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

        // Form submission handlers
        document.getElementById('emailForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const newEmail = document.getElementById('newEmail').value;
            const confirmEmail = document.getElementById('confirmEmail').value;

            if (newEmail !== confirmEmail) {
                alert('New email and confirmation email do not match');
                return;
            }

            // Add your email update logic here
            alert('Email update request submitted');
            bootstrap.Modal.getInstance(document.getElementById('emailModal')).hide();
        });

        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (newPassword !== confirmPassword) {
                alert('New password and confirmation password do not match');
                return;
            }

            // Add your password update logic here
            alert('Password update request submitted');
            bootstrap.Modal.getInstance(document.getElementById('passwordModal')).hide();
        });
    </script>
</body>
</html>
