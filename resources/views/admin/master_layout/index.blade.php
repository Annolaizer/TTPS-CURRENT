<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TTP - Admin Dashboard')</title>
    
    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/admin/admin.css') }}">
    <link rel="shortcut icon" href="{{ asset('asset/images/logo.png') }}" type="image/x-icon">
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container-fluid px-0">
            <div class="d-flex align-items-center">
                <button class="navbar-toggler d-md-none me-2" type="button" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('asset/images/logo.png') }}" alt="Logo">
                    <span>Tanzania Teacher Portal</span>
                </a>
            </div>
            <div class="d-flex align-items-center">
                <!-- Notification Icon -->
                @php
                    $unreadCount = \App\Models\Notification::unread()->count();
                @endphp
                <div class="dropdown me-3">
                    <div class="notification-icon" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        @if($unreadCount > 0)
                            <span class="notification-badge">{{ $unreadCount }}</span>
                        @endif
                    </div>
                    <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                        <div class="dropdown-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Notifications</span>
                                @if($unreadCount > 0)
                                    <span class="badge bg-secondary">{{ $unreadCount }} New</span>
                                @endif
                            </div>
                        </div>
                        <div class="notification-list">
                            @foreach(\App\Models\Notification::latest()->take(3)->get() as $notification)
                            <a href="#" class="dropdown-item notification-item">
                                <div class="d-flex align-items-start">
                                    <div class="notification-icon-circle {{ $notification->type === 'system' ? 'bg-info' : ($notification->type === 'training' ? 'bg-primary' : 'bg-warning') }}">
                                        <i class="{{ $notification->type === 'system' ? 'fas fa-cog' : ($notification->type === 'training' ? 'fas fa-graduation-cap' : 'fas fa-bullhorn') }} text-white"></i>
                                    </div>
                                    <div class="notification-content">
                                        <p class="notification-text">{{ $notification->title }}</p>
                                        <small class="notification-time">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        <div class="dropdown-footer">
                            <a href="#" class="text-center">View All Notifications</a>
                        </div>
                    </div>
                </div>

                <!-- User Profile -->
                <div class="dropdown">
                    <div class="nav-icon" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end user-profile-dropdown">
                        <div class="dropdown-header">
                            <div class="user-info">
                                <i class="fas fa-user-circle"></i>
                                <div>
                                    <h6>{{ auth()->user()->name ?? 'Administrator' }}</h6>
                                    <small>{{ ucfirst(str_replace('_', ' ', auth()->user()->role ?? 'admin')) }}</small>
                                </div>
                            </div>
                        </div>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user"></i> My Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-content">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i> User Management
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#trainingsSubmenu" data-bs-toggle="collapse" class="nav-link {{ request()->routeIs('admin.trainings.*') ? 'active' : '' }}">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <span>Trainings</span>
                            <i class="fas fa-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.trainings.*') ? 'show' : '' }}" id="trainingsSubmenu">
                            <ul class="nav-list">
                                <li class="nav-item">
                                    <a href="{{ route('admin.trainings.index') }}" class="nav-link {{ request()->routeIs('admin.trainings.index') ? 'active' : '' }}">
                                        <i class="fas fa-list"></i>
                                        <span>All Trainings</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a href="#teachersSubmenu" class="nav-link {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}" data-bs-toggle="collapse">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <span>Teachers</span>
                            <i class="fas fa-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.teachers.*') ? 'show' : '' }}" id="teachersSubmenu">
                            <ul class="nav-list">
                                <li class="nav-item">
                                    <a href="{{ route('admin.teachers.index') }}" class="nav-link {{ request()->routeIs('admin.teachers.index') ? 'active' : '' }}">
                                        <i class="fas fa-list"></i>
                                        <span>All Teachers</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a href="#institutionsSubmenu" class="nav-link {{ request()->routeIs('admin.institutions.*') ? 'active' : '' }}" data-bs-toggle="collapse">
                            <i class="fas fa-university"></i>
                            <span>Institutions</span>
                            <i class="fas fa-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.institutions.*') ? 'show' : '' }}" id="institutionsSubmenu">
                            <ul class="nav-list">
                                <li class="nav-item">
                                    <a href="{{ route('admin.institutions.index') }}" class="nav-link {{ request()->routeIs('admin.institutions.index') ? 'active' : '' }}">
                                        <i class="fas fa-list"></i>
                                        <span>All Institutions</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Reports & Analytics</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.subjects.index') }}" class="nav-link {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}">
                            <i class="fas fa-book-open"></i>
                            <span>Subjects</span>
                        </a>
                    </li>

                    <li class="nav-item mt-auto">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="nav-link text-danger border-0 w-100 text-start">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <!-- JavaScript Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('asset/js/admin/admin.js') }}"></script>
    @stack('scripts')
</body>
</html>