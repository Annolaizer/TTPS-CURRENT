<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('asset/images/logo.png') }}" type="image/x-icon">
    <title>TTP - Teacher Profile</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        /* Modern Toastr Styling */
        #toast-container > div {
            opacity: 1;
            border-radius: 12px;
            padding: 15px 15px 15px 50px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            color: #fff;
            font-weight: 500;
            font-size: 0.95rem;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .toast-close-button {
            font-size: 1.25rem;
            font-weight: 400;
            padding: 5px;
            text-shadow: none;
            opacity: 0.5;
            transition: all 0.3s ease;
        }

        .toast-close-button:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        .toast-message {
            padding: 3px 0;
        }

        .toast-info {
            background: linear-gradient(135deg, rgba(47,128,237,0.95) 0%, rgba(86,204,242,0.95) 100%) !important;
        }

        .toast-error {
            background: linear-gradient(135deg, rgba(220,53,69,0.95) 0%, rgba(245,96,109,0.95) 100%) !important;
        }

        .toast-success {
            background: linear-gradient(135deg, rgba(40,167,69,0.95) 0%, rgba(72,187,120,0.95) 100%) !important;
        }

        .toast-warning {
            background: linear-gradient(135deg, rgba(255,193,7,0.95) 0%, rgba(255,213,79,0.95) 100%) !important;
        }

        #toast-container > div:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            transform: translateY(-2px);
            opacity: 1;
        }
        :root {
            --primary-color: #009c95;
            --secondary-color: #cc3344;
            --hover-color: #007c77;
            --hover-dark: #006c68;
            --light-bg: #f8f8f8;
            --navbar-height: 70px;
            --box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: var(--light-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--hover-color) 100%);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--navbar-height);
            z-index: 1000;
            padding: 10px 0;
            display: flex;
            align-items: center;
            box-shadow: var(--box-shadow);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            color: white !important;
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .navbar-brand:hover {
            transform: translateY(-1px);
        }
        
        .logo {
            height: 45px;
            width: auto;
            object-fit: contain;
            margin-right: 15px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
            transition: var(--transition);
        }
        
        .logo:hover {
            transform: scale(1.05);
        }
        
        .portal-name {
            color: white;
            font-size: 1.3rem;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            letter-spacing: 0.5px;
        }

        .user-profile {
            position: relative;
            margin-left: auto;
        }

        .profile-trigger {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: white;
            height: 42px;
            width: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            padding: 0;
            margin-right: 0 !important;
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        .profile-trigger:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        .profile-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 2px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 300px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: var(--transition);
            z-index: 1000;
        }

        .profile-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .profile-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        .profile-header i.fa-user {
            font-size: 2.5rem;
            color: var(--primary-color);
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: var(--transition);
        }

        .profile-header i.fa-user:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
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

        .profile-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            transition: var(--transition);
            border-radius: 8px;
            margin: 4px 8px;
        }

        .profile-menu a:hover {
            background-color: var(--light-bg);
            color: var(--primary-color);
            transform: translateX(5px);
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

        .main-content {
            padding-top: calc(var(--navbar-height) + 20px);
            flex: 1;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid #eee;
            padding: 1rem;
            font-weight: bold;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--hover-color);
            border-color: var(--hover-color);
        }

        @media (max-width: 768px) {
            .navbar-brand .portal-name {
                display: none;
            }
            
            .profile-dropdown {
                width: calc(100vw - 30px);
                right: 15px;
            }
        }
    </style>

    @yield('styles')
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
                        <a href="{{ route('teacher.training.index') }}"><i class="fas fa-certificate"></i>Training</a>
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
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileTrigger = document.querySelector('.profile-trigger');
            const profileDropdown = document.getElementById('profileDropdown');
            let isDropdownOpen = false;

            function toggleDropdown(event) {
                event.stopPropagation();
                isDropdownOpen = !isDropdownOpen;
                profileDropdown.classList.toggle('show');
                
                if (isDropdownOpen) {
                    profileTrigger.style.transform = 'scale(0.95)';
                    profileTrigger.style.backgroundColor = 'var(--hover-color)';
                } else {
                    profileTrigger.style.transform = '';
                    profileTrigger.style.backgroundColor = '';
                }
            }

            profileTrigger.addEventListener('click', toggleDropdown);

            document.addEventListener('click', function(event) {
                if (isDropdownOpen && !profileDropdown.contains(event.target) && !profileTrigger.contains(event.target)) {
                    isDropdownOpen = false;
                    profileDropdown.classList.remove('show');
                    profileTrigger.style.transform = '';
                    profileTrigger.style.backgroundColor = '';
                }
            });

            // Add hover effect to profile trigger
            profileTrigger.addEventListener('mouseenter', function() {
                if (!isDropdownOpen) {
                    this.style.transform = 'scale(1.05)';
                    this.style.backgroundColor = 'rgba(255, 255, 255, 0.2)';
                }
            });

            profileTrigger.addEventListener('mouseleave', function() {
                if (!isDropdownOpen) {
                    this.style.transform = '';
                    this.style.backgroundColor = '';
                }
            });
        });

        // Configure Toastr
        toastr.options = {
            closeButton: true,
            debug: false,
            newestOnTop: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            preventDuplicates: true,
            onclick: null,
            showDuration: '300',
            hideDuration: '1000',
            timeOut: '5000',
            extendedTimeOut: '1000',
            showEasing: 'swing',
            hideEasing: 'linear',
            showMethod: 'fadeIn',
            hideMethod: 'fadeOut',
            tapToDismiss: false
        };

        // Display Laravel flash messages
        @if(Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif

        @if(Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @endif

        @if(Session::has('info'))
            toastr.info("{{ Session::get('info') }}");
        @endif

        @if(Session::has('warning'))
            toastr.warning("{{ Session::get('warning') }}");
        @endif
    </script>

    @yield('scripts')
</body>
</html>
