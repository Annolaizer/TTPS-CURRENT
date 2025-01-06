<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('asset/images/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>TTP - Teacher Dashboard</title>
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
            text-decoration: none;
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
        
        .portal-name {
            margin-top: 5px;
            margin-left: 0.5rem;
            font-weight: 500;
        }
        #logo {
            width: 45px;
            height: 45px;
            object-fit: contain;
        }
       
        @media (max-width: 767.98px) {
            :root {
                --navbar-height: 60px;
            }
            .card-container {
                gap: 1rem;
            }
            .card {
                flex: 0 1 calc(50% - 1rem);
                height: auto;
                min-height: 200px;
            }
        }
        @media (max-width: 575.98px) {
            .card {
                flex: 0 1 100%;
            }
        }
        @media (max-width: 768px) {
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
            .top_bar p {
                margin: 0;
                padding: 0;
            }
            :root {
                --topbar-height: auto;
            }
            body {
                padding-top: calc(var(--navbar-height) + 2.5rem);
            }
            .navbar {
                position: fixed;
                top: var(--topbar-actual-height, 2.5rem);
                transform: none;
                margin: 0;
                transition: top 0.3s ease;
            }
            .nav-link {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 0.75rem 1rem;
            }
            .nav-link i {
                width: 20px;
                text-align: center;
                font-size: 1.1rem;
            }
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

        /* Enhanced responsive styles */
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
            .user-profile {
                margin-left: 0;
            }
        }

        /* Tablet specific adjustments */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .profile-dropdown {
                width: 280px;
            }
            .navbar-brand {
                font-size: 1.1rem;
            }
        }

        /* General responsive improvements */
        .navbar {
            transition: all 0.3s ease;
        }

        .profile-trigger {
            padding: 8px;
            transition: all 0.2s ease;
        }

        .profile-trigger:hover {
            background-color: var(--hover-color);
            border-radius: 4px;
        }

        /* Content area responsiveness */
        .content-wrapper {
            padding: 20px;
            max-width: var(--container-max-width);
            margin: 0 auto;
        }

        @media (max-width: 575.98px) {
            .content-wrapper {
                padding: 15px;
            }
            .top_bar {
                font-size: 0.7rem;
                padding: 8px 15px;
            }
        }

        /* Enhanced accessibility */
        @media (prefers-reduced-motion: reduce) {
            * {
                transition: none !important;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .profile-dropdown {
                background: #2d2d2d;
                color: #fff;
            }
            .profile-header {
                border-bottom-color: #404040;
            }
        }

        /* Print styles */
        @media print {
            .top_bar,
            .navbar,
            .profile-dropdown {
                display: none !important;
            }
            body {
                padding-top: 0 !important;
            }
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
            margin: 200px auto 0;
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
                margin-top: 120px;
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

    </style>
</head>
<body>
    <!-- Announcement Bar -->
    <div class="top_bar">
        GES teachers who cannot verify their Staff ID or SSNIT should send their payslip, SSNIT ID and scanned first appointment letter to it@ntc.gov.gh . The Duration for processing Letter of Professional Standing takes a maximum of 30 working days.
    </div>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <a href="{{ route('teacher.dashboard') }}" class="navbar-brand">
                <img src="{{ asset('asset/images/logo.png') }}" alt="Logo" class="logo" width="50px">
                <span class="portal-name">Teacher Portal Tanzania</span>
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
                        <a href="{{ route('teacher.profile') }}"><i class="fas fa-cog text-prime"></i>Account settings</a>
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
        <div class="row">
            <div class="col-custom">
                <img src="{{ asset('asset/images/launch.svg') }}" alt="Launch illustration">
            </div>
            <div class="col-custom" style="width: 400px;">
                <div class="welcome-text">
                    <div class="name">Hello {{ Auth::user()->name }},</div>
                    <div class="portal">WELCOME TO THE TEACHER PORTAL TANZANIA PLATFORM</div>
                </div>
                <p>Get started by setting up or updating your profile, after which you can start using the platform.</p>
                <a href="{{ route('teacher.profile.setup') }}" class="setup-btn">
                    <i class="fas fa-user-cog"></i> Set Up Teacher Profile
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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

        // Close dropdown when clicking anywhere else on the page
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const trigger = document.querySelector('.profile-trigger');
            
            if (!trigger.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });
    </script>
</body>
</html>
