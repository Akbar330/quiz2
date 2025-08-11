<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Quiz Website') }}</title>

    <!-- Fonts & Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #4da6ff;
            --primary-dark: #3399ff;
            --sidebar-bg: #1e2832;
            --sidebar-hover: #2c3742;
            --text-muted: #6c757d;
            --border-color: #e9ecef;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid #2c3742;
            text-align: center;
        }

        .sidebar-header .brand {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 600;
            text-decoration: none;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .sidebar-header .brand {
            opacity: 0;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .menu-item {
            display: block;
            padding: 0.75rem 1.5rem;
            color: #b8c5d1;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .menu-item:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .menu-item.active {
            background: var(--primary-color);
            color: #fff;
        }

        .menu-item i {
            width: 20px;
            margin-right: 0.75rem;
            text-align: center;
        }

        .sidebar.collapsed .menu-item {
            padding: 0.75rem 1rem;
            text-align: center;
        }

        .sidebar.collapsed .menu-item span {
            display: none;
        }

        .sidebar.collapsed .menu-item i {
            margin: 0;
        }

        /* User Profile in Sidebar */
        .user-profile {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem;
            border-top: 1px solid #2c3742;
            background: #151b23;
        }

        .user-info {
            display: flex;
            align-items: center;
            color: #b8c5d1;
            padding: 0.5rem;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            margin-right: 0.75rem;
        }

        .sidebar.collapsed .user-info span {
            display: none;
        }

        .sidebar.collapsed .user-avatar {
            margin: 0;
        }

        /* Role Badge Styles */
        .role-badge {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.7rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        .role-admin {
            background-color: #dc3545;
            color: #fff;
        }

        .role-teacher, .role-instructor {
            background-color: #fd7e14;
            color: #fff;
        }

        .role-student {
            background-color: #198754;
            color: #fff;
        }

        .role-guest {
            background-color: #6c757d;
            color: #fff;
        }

        .role-moderator {
            background-color: #6f42c1;
            color: #fff;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        .main-content.expanded {
            margin-left: 70px;
        }

        /* Top Header */
        .top-header {
            background: #fff;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .toggle-sidebar {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--text-muted);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }

        .toggle-sidebar:hover {
            background: #f8f9fa;
            color: var(--primary-color);
        }

        .page-title {
            margin: 0;
            color: #2d3748;
            font-weight: 600;
            font-size: 1.5rem;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Content Area */
        .content-wrapper {
            padding: 2rem;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            background-color: #ffffff;
            margin-bottom: 2rem;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: #fff;
            border-radius: 1rem 1rem 0 0 !important;
            padding: 1.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(77, 166, 255, 0.3);
        }

        .alert {
            border-radius: 0.75rem;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        /* Dropdown Menu Styles */
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
            color: #374151;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: #f3f4f6;
            color: var(--primary-color);
        }

        /* Loading Animation */
        .loading {
            opacity: 0.6;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        /* Scroll Styles */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--sidebar-bg);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #4a5568;
            border-radius: 2px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
    </style>
</head>

<body>
    <div id="app">
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <a href="{{ url('/') }}" class="brand">
                    <i class="fas fa-graduation-cap me-2"></i>
                    <span>{{ config('app.name', 'Quiz Website') }}</span>
                </a>
            </div>

            <!-- Sidebar Menu -->
            <div class="sidebar-menu">
                @auth
                    @php
                        $userRole = strtolower(Auth::user()->role ?? 'guest');
                    @endphp
                    
                    @if(in_array($userRole, ['admin', 'super_admin', 'administrator']))
                        <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Admin Dashboard</span>
                        </a>
                        <a href="#" class="menu-item">
                            <i class="fas fa-users"></i>
                            <span>Manage Users</span>
                        </a>
                        <a href="#" class="menu-item">
                            <i class="fas fa-question-circle"></i>
                            <span>Manage Quizzes</span>
                        </a>
                        <a href="#" class="menu-item">
                            <i class="fas fa-chart-bar"></i>
                            <span>Reports</span>
                        </a>
                        <a href="#" class="menu-item">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                        
                    @elseif(in_array($userRole, ['teacher', 'instructor', 'educator']))
                        <a href="{{ route('home') }}" class="menu-item {{ request()->routeIs('home') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="#" class="menu-item">
                            <i class="fas fa-plus-circle"></i>
                            <span>Create Quiz</span>
                        </a>
                        <a href="#" class="menu-item">
                            <i class="fas fa-list-alt"></i>
                            <span>My Quizzes</span>
                        </a>
                        <a href="{{ route('results.index') }}" class="menu-item {{ request()->routeIs('results.*') ? 'active' : '' }}">
                            <i class="fas fa-poll"></i>
                            <span>Quiz Results</span>
                        </a>
                        <a href="#" class="menu-item">
                            <i class="fas fa-users"></i>
                            <span>My Students</span>
                        </a>
                        <a href="#" class="menu-item">
                            <i class="fas fa-chart-line"></i>
                            <span>Analytics</span>
                        </a>
                        
                    @elseif(in_array($userRole, ['moderator', 'mod']))
                        <a href="{{ route('home') }}" class="menu-item {{ request()->routeIs('home') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="#" class="menu-item">
                            <i class="fas fa-flag"></i>
                            <span>Review Reports</span>
                        </a>
                        <a href="#" class="menu-item">
                            <i class="fas fa-comments"></i>
                            <span>Moderate Content</span>
                        </a>
                        <a href="{{ route('results.index') }}" class="menu-item {{ request()->routeIs('results.*') ? 'active' : '' }}">
                            <i class="fas fa-poll"></i>
                            <span>Quiz Results</span>
                        </a>
                        
                    @else
                        {{-- Default Student Role --}}
                        <a href="{{ route('home') }}" class="menu-item {{ request()->routeIs('home') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Home</span>
                        </a>
                        <a href="{{ route('results.index') }}" class="menu-item {{ request()->routeIs('results.*') ? 'active' : '' }}">
                            <i class="fas fa-poll"></i>
                            <span>Quiz Results</span>
                        </a>
                        <a href="#" class="menu-item">
                            <i class="fas fa-play-circle"></i>
                            <span>Take Quiz</span>
                        </a>
                        <a href="#" class="menu-item">
                            <i class="fas fa-history"></i>
                            <span>Quiz History</span>
                        </a>
                        <a href="#" class="menu-item">
                            <i class="fas fa-trophy"></i>
                            <span>Achievements</span>
                        </a>
                        <a href="#" class="menu-item">
                            <i class="fas fa-user-circle"></i>
                            <span>Profile</span>
                        </a>
                    @endif
                    
                    {{-- Common menu items for all authenticated users --}}
                    <div style="border-top: 1px solid #2c3742; margin: 1rem 0; padding-top: 1rem;">
                        <a href="#" class="menu-item">
                            <i class="fas fa-bell"></i>
                            <span>Notifications</span>
                        </a>
                        <a href="#" class="menu-item">
                            <i class="fas fa-question-circle"></i>
                            <span>Help & Support</span>
                        </a>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="menu-item">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                    <a href="{{ route('register') }}" class="menu-item">
                        <i class="fas fa-user-plus"></i>
                        <span>Register</span>
                    </a>
                @endauth
            </div>

            <!-- User Profile (Bottom of Sidebar) -->
            @auth
            <div class="user-profile">
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-size: 0.9rem; font-weight: 500; color: #fff;">
                            {{ Auth::user()->name }}
                        </div>
                        <div style="font-size: 0.75rem; color: #9ca3af;">
                            @php
                                $userRole = Auth::user()->role ?? 'guest';
                                $displayRole = ucfirst(str_replace('_', ' ', $userRole));
                            @endphp
                            <span class="role-badge role-{{ strtolower(str_replace(' ', '-', $userRole)) }}">
                                {{ $displayRole }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('logout') }}" class="menu-item" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       style="padding: 0.5rem; font-size: 0.875rem;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
            @endauth
        </nav>

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Top Header -->
            <header class="top-header">
                <div class="d-flex align-items-center">
                    <button class="toggle-sidebar me-3" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                </div>
                
                <div class="header-actions">
                    @auth
                        <!-- Notification Bell -->
                        <div class="dropdown">
                            <button class="btn btn-light position-relative" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-bell"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                    3
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><h6 class="dropdown-header">Notifications</h6></li>
                                <li><a class="dropdown-item" href="#">New quiz available</a></li>
                                <li><a class="dropdown-item" href="#">Results published</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="#">View all</a></li>
                            </ul>
                        </div>

                        <!-- User Menu -->
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>
                                {{ Auth::user()->name }}
                                @php
                                    $userRole = Auth::user()->role ?? 'guest';
                                @endphp
                                <span class="role-badge role-{{ strtolower(str_replace(' ', '-', $userRole)) }} ms-1">
                                    {{ ucfirst(str_replace('_', ' ', $userRole)) }}
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endauth
                </div>
            </header>

            <!-- Flash Messages -->
            <div class="content-wrapper">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('warning') }}
                        <button type="button" bin-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Main Content Area -->
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Toggle Sidebar Function
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.querySelector('.sidebar-overlay');
            
            if (window.innerWidth > 768) {
                // Desktop behavior
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            } else {
                // Mobile behavior
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
                document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : 'auto';
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                const sidebar = document.getElementById('sidebar');
                const toggleBtn = document.querySelector('.toggle-sidebar');
                const overlay = document.querySelector('.sidebar-overlay');
                
                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target) && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    document.body.style.overflow = 'auto';
                }
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.querySelector('.sidebar-overlay');
            
            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                document.body.style.overflow = 'auto';
                
                if (sidebar.classList.contains('collapsed')) {
                    mainContent.classList.add('expanded');
                } else {
                    mainContent.classList.remove('expanded');
                }
            } else {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
            }
        });

        // Set active menu item based on current URL
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const menuItems = document.querySelectorAll('.menu-item');
            
            menuItems.forEach(item => {
                const href = item.getAttribute('href');
                if (href && href !== '#' && currentPath.includes(href)) {
                    item.classList.add('active');
                }
            });
        });

        // Smooth loading animation
        document.addEventListener('DOMContentLoaded', function() {
            const mainContent = document.getElementById('mainContent');
            mainContent.style.opacity = '0';
            mainContent.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                mainContent.style.transition = 'all 0.5s ease';
                mainContent.style.opacity = '1';
                mainContent.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>

    @stack('scripts')
</body>

</html>