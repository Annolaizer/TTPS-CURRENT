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
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="{{ route('cpd_facilitator.dashboard') }}" class="navbar-brand">
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
                        <a href="{{ route('cpd_facilitator.dashboard') }}"><i class="fas fa-home text-prime"></i>Home</a>
                        <a href="#"><i class="fas fa-certificate text-prime"></i>Training</a>
                        <a href="{{ route('cpd_facilitator.profile') }}"><i class="fas fa-cog text-prime"></i>Account settings</a>
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
    <div class="main-content">
        <div class="main_content">
            <div class="row mt-5">
                <div class="col-custom">
                    <img src="{{ asset('asset/images/launch.svg') }}" alt="Launch illustration">
                </div>
                <div class="col-custom" style="width: 400px;">
                    <div class="welcome-text">
                        <div class="name">Hello {{ Auth::user()->name }},</div>
                        <div class="portal">WELCOME TO THE TANZANIA TEACHER PORTALPLATFORM</div>
                    </div>
                    <p>Get started by setting up or updating your profile, after which you can start using the platform.</p>
                    <a href="{{ route('cpd_facilitator.profile.setup') }}" class="setup-btn">
                        <i class="fas fa-user-cog"></i> Set Up CPD Facilitator Profile
                    </a>
                </div>
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
