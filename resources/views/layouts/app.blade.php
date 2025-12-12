<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Furniture Store Admin')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --furniture-brown: #8B4513;
            --furniture-tan: #D2B48C;
            --furniture-cream: #F5F5DC;
            --sidebar-width: 250px;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: linear-gradient(180deg, var(--furniture-brown) 0%, #5D2906 100%);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-brand {
            padding: 20px 15px;
            color: white;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand .logo-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        
        .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.2);
            font-weight: 600;
        }
        
        .nav-link i {
            width: 24px;
            margin-right: 10px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
        }
        
        .top-navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 15px 20px;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .content-wrapper {
            padding: 20px;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #eee;
            padding: 15px 20px;
            border-radius: 12px 12px 0 0 !important;
        }
        
        /* Buttons */
        .btn-furniture {
            background: linear-gradient(135deg, var(--furniture-brown) 0%, var(--furniture-tan) 100%);
            border: none;
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .btn-furniture:hover {
            background: linear-gradient(135deg, var(--furniture-tan) 0%, var(--furniture-brown) 100%);
            color: white;
        }
        
        /* Stats Cards */
        .stat-card {
            background: white;
            border-left: 4px solid var(--furniture-brown);
        }
        
        .furniture-bg {
            background: linear-gradient(135deg, #fff 0%, var(--furniture-cream) 100%);
        }
        
        /* Badges */
        .badge-category {
            background: var(--furniture-tan);
            color: var(--furniture-brown);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            
            .sidebar.active {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-brand">
            <div class="logo-icon">
                <i class="fas fa-couch"></i>
            </div>
            <h5 class="mb-0">Furniture Store</h5>
            <small class="opacity-75">Admin Dashboard</small>
        </div>
        
        <div class="sidebar-menu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.products.index') || request()->routeIs('admin.products.edit') || request()->routeIs('admin.products.create') ? 'active' : '' }}" 
                       href="{{ route('admin.products.index') }}">
                        <i class="fas fa-boxes"></i>
                        <span>Products</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.purchases.*') ? 'active' : '' }}" 
                       href="{{ route('admin.purchases.index') }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Purchases</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.products.stock') ? 'active' : '' }}" 
                       href="{{ route('admin.products.stock') }}">
                        <i class="fas fa-warehouse"></i>
                        <span>Stock Management</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.chatbot.*') ? 'active' : '' }}" 
                       href="{{ route('admin.chatbot.index') }}">
                        <i class="fas fa-robot"></i>
                        <span>AI Assistant</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.documentation.*') ? 'active' : '' }}" 
                    href="{{ route('admin.documentation.index') }}">
                        <i class="fas fa-book"></i>
                        <span>Dokumentasi</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="mt-auto p-3">
            <form action="{{ route('admin.logout') }}" method="POST" class="d-grid">
                @csrf
                <button type="submit" class="btn btn-outline-light">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </button>
            </form>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="btn btn-light me-3 d-lg-none" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h4 class="mb-0">@yield('title')</h4>
                </div>
                
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>
                            {{ Auth::guard('admin')->user()->name }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('admin.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Content Wrapper -->
        <div class="content-wrapper fade-in">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Toggle Sidebar on Mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
        
        // Auto-hide sidebar on mobile when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !sidebarToggle.contains(event.target) &&
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });
        
        // Close sidebar on mobile when clicking a link
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    document.querySelector('.sidebar').classList.remove('active');
                }
            });
        });
        
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
        
        // Format currency input
        document.querySelectorAll('input[type="number"][step="1000"]').forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value) {
                    this.value = Math.round(this.value / 1000) * 1000;
                }
            });
        });
        
        // Show debug info in console
        console.log('Current route:', '{{ request()->route()->getName() }}');
    </script>
    
    @stack('scripts')
</body>
</html>