{{-- resources/views/admin/documentation/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dokumentasi Sistem Admin Furniture Store')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-book text-primary me-2"></i>Dokumentasi Sistem
            </h1>
            <p class="text-muted">Panduan lengkap penggunaan dan pengembangan sistem admin furniture store</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Dashboard
            </a>
            <a href="{{ route('admin.chatbot.index') }}" class="btn btn-primary">
                <i class="fas fa-robot me-1"></i> AI Assistant
            </a>
        </div>
    </div>

    <!-- Development Notice -->
    <div class="alert alert-primary border-left-primary shadow-sm mb-4">
        <div class="d-flex">
            <div class="flex-shrink-0">
                <i class="fas fa-lightbulb fa-2x text-primary"></i>
            </div>
            <div class="flex-grow-1 ms-3">
                <h6 class="alert-heading mb-1">Konsep Dasar & Potensi Pengembangan</h6>
                <p class="mb-0">Sistem ini dirancang sebagai <strong>landasan dasar</strong> yang dapat dikembangkan menjadi platform e-commerce profesional dengan fitur-fitur kompleks seperti marketplace, multi-vendor, payment gateway, dan sistem rekomendasi AI.</p>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white shadow-sm py-2 px-3 rounded">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">Dokumentasi</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h6 class="mb-0"><i class="fas fa-bars me-2"></i>Navigasi Dokumentasi</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush rounded">
                        <a href="#overview" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            <span>Gambaran Umum</span>
                        </a>
                        <a href="#architecture" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="fas fa-sitemap text-success me-2"></i>
                            <span>Arsitektur Sistem</span>
                        </a>
                        <a href="#controllers" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="fas fa-cogs text-warning me-2"></i>
                            <span>Kontroller & Fitur</span>
                        </a>
                        <a href="#database" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="fas fa-database text-info me-2"></i>
                            <span>Struktur Database</span>
                        </a>
                        <a href="#chatbot" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="fas fa-robot text-danger me-2"></i>
                            <span>AI Chatbot System</span>
                        </a>
                        <a href="#scalability" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="fas fa-expand-arrows-alt text-purple me-2"></i>
                            <span>Potensi Pengembangan</span>
                        </a>
                        <a href="#security" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="fas fa-shield-alt text-dark me-2"></i>
                            <span>Keamanan Sistem</span>
                        </a>
                        <a href="#usage" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="fas fa-user-tie text-secondary me-2"></i>
                            <span>Panduan Penggunaan</span>
                        </a>
                        <a href="#troubleshooting" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="fas fa-wrench text-orange me-2"></i>
                            <span>Troubleshooting</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0"><i class="fas fa-chart-line text-success me-2"></i>Statistik Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Versi Sistem</span>
                        <span class="badge bg-primary">v1.0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Status</span>
                        <span class="badge bg-success">Production Ready</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Terakhir Update</span>
                        <span class="text-muted">{{ date('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Overview Section -->
            <div class="card border-0 shadow-sm mb-4" id="overview">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle fa-lg text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0">Gambaran Umum Sistem</h5>
                            <p class="text-muted mb-0">Furniture Store Admin System v1.0</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p class="lead">Sistem manajemen toko furnitur yang komprehensif dengan fitur-fitur modern untuk mengelola produk, stok, pembelian, dan analisis data.</p>
                    
                    <!-- Tech Stack -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3"><i class="fas fa-layer-group me-1"></i> Stack Teknologi</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-primary px-3 py-2">
                                <i class="fab fa-laravel me-1"></i> Laravel 10+
                            </span>
                            <span class="badge bg-success px-3 py-2">
                                <i class="fas fa-database me-1"></i> MySQL 8.0
                            </span>
                            <span class="badge bg-info px-3 py-2">
                                <i class="fab fa-php me-1"></i> PHP 8.2
                            </span>
                            <span class="badge bg-warning px-3 py-2">
                                <i class="fab fa-bootstrap me-1"></i> Bootstrap 5
                            </span>
                            <span class="badge bg-danger px-3 py-2">
                                <i class="fas fa-robot me-1"></i> DeepSeek AI
                            </span>
                        </div>
                    </div>
                    
                    <!-- Key Features -->
                    <h6 class="text-primary mb-3"><i class="fas fa-star me-1"></i> Fitur Utama</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="card h-100 border-left-primary shadow-sm">
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <i class="fas fa-boxes fa-2x text-primary"></i>
                                    </div>
                                    <h6 class="text-center">Manajemen Produk</h6>
                                    <p class="text-muted text-center small">Kelola katalog produk dengan gambar, kategori, dan harga secara lengkap</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-left-success shadow-sm">
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <i class="fas fa-shopping-cart fa-2x text-success"></i>
                                    </div>
                                    <h6 class="text-center">Sistem Pembelian</h6>
                                    <p class="text-muted text-center small">Transaksi lengkap dengan invoice otomatis dan manajemen stok real-time</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-left-info shadow-sm">
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <i class="fas fa-brain fa-2x text-info"></i>
                                    </div>
                                    <h6 class="text-center">AI Assistant</h6>
                                    <p class="text-muted text-center small">Chatbot cerdas untuk analisis data dan query dalam bahasa natural</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Architecture Summary -->
                    <div class="bg-light p-3 rounded">
                        <h6 class="text-primary mb-2"><i class="fas fa-code-branch me-1"></i> Arsitektur Modular</h6>
                        <p class="mb-0">Sistem dibangun dengan arsitektur modular MVC yang memungkinkan pengembangan lebih lanjut dengan mudah. Setiap komponen terpisah dan dapat dikembangkan secara independen.</p>
                    </div>
                </div>
            </div>

            <!-- Architecture Section -->
            <div class="card border-0 shadow-sm mb-4" id="architecture">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-sitemap fa-lg text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0">Arsitektur Sistem</h5>
                            <p class="text-muted mb-0">Struktur MVC dan Scalability Pattern</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Architecture Diagram Placeholder -->
                    <div class="text-center mb-4">
                        <div class="bg-light p-4 rounded border">
                            <i class="fas fa-project-diagram fa-4x text-muted mb-3"></i>
                            <h6 class="text-muted">Architecture Diagram</h6>
                            <p class="text-muted small">MVC Pattern dengan Separation of Concerns</p>
                        </div>
                    </div>
                    
                    <!-- Architecture Components -->
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-primary"><i class="fas fa-cube me-2"></i> Model Layer</h6>
                                    <ul class="mb-0">
                                        <li>Eloquent ORM Models</li>
                                        <li>Database Relationships</li>
                                        <li>Business Logic</li>
                                        <li>Validation Rules</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-success"><i class="fas fa-sliders-h me-2"></i> Controller Layer</h6>
                                    <ul class="mb-0">
                                        <li>Request Handling</li>
                                        <li>Business Rules</li>
                                        <li>API Endpoints</li>
                                        <li>Middleware Integration</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-info"><i class="fas fa-eye me-2"></i> View Layer</h6>
                                    <ul class="mb-0">
                                        <li>Blade Templates</li>
                                        <li>Component-based UI</li>
                                        <li>Responsive Design</li>
                                        <li>Client-side Logic</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Scalability Note -->
                    <div class="alert alert-success mt-4">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-expand-alt fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="alert-heading">Arsitektur yang Dapat Diskala</h6>
                                <p class="mb-0">Struktur ini mendukung pengembangan menjadi sistem yang lebih kompleks seperti marketplace multi-vendor, platform e-commerce dengan payment gateway, atau sistem inventory enterprise.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Controllers Section -->
            <div class="card border-0 shadow-sm mb-4" id="controllers">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-cogs fa-lg text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0">Kontroller & Fitur Utama</h5>
                            <p class="text-muted mb-0">Modul dan Fungsi Inti Sistem</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Controllers Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Kontroller</th>
                                    <th>Deskripsi</th>
                                    <th>Method Utama</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>DashboardController</code></td>
                                    <td>Statistik real-time dan overview sistem</td>
                                    <td><code>index()</code></td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td><code>ProductController</code></td>
                                    <td>Manajemen produk dan stok</td>
                                    <td><code>CRUD</code>, <code>updateStock()</code></td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td><code>PurchaseController</code></td>
                                    <td>Transaksi dan invoice</td>
                                    <td><code>store()</code>, <code>cancel()</code></td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td><code>ChatbotController</code></td>
                                    <td>AI Assistant untuk analisis</td>
                                    <td><code>chat()</code>, <code>status()</code></td>
                                    <td><span class="badge bg-info">AI-Powered</span></td>
                                </tr>
                                <tr>
                                    <td><code>AuthController</code></td>
                                    <td>Autentikasi admin</td>
                                    <td><code>login()</code>, <code>logout()</code></td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Key Features Grid -->
                    <h6 class="text-primary mt-4 mb-3"><i class="fas fa-bolt me-1"></i> Fitur Unggulan</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card border-left-primary">
                                <div class="card-body">
                                    <h6><i class="fas fa-chart-pie text-primary me-2"></i>Analytics Real-time</h6>
                                    <ul class="mb-0">
                                        <li>Dashboard statistik lengkap</li>
                                        <li>Monitoring stok otomatis</li>
                                        <li>Low stock alerts</li>
                                        <li>Performance tracking</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-left-info">
                                <div class="card-body">
                                    <h6><i class="fas fa-robot text-info me-2"></i>Integrasi AI</h6>
                                    <ul class="mb-0">
                                        <li>Chatbot dengan DeepSeek API</li>
                                        <li>Data analysis natural language</li>
                                        <li>Rule-based fallback system</li>
                                        <li>Real-time data queries</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Database Section -->
            <div class="card border-0 shadow-sm mb-4" id="database">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-database fa-lg text-info"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0">Struktur Database</h5>
                            <p class="text-muted mb-0">Entity Relationship & Optimasi</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Database Schema -->
                    <div class="text-center mb-4">
                        <div class="bg-light p-4 rounded border">
                            <i class="fas fa-project-diagram fa-4x text-muted mb-3"></i>
                            <h6 class="text-muted">Database Schema</h6>
                            <p class="text-muted small">6 Tabel Utama dengan Relasi yang Optimal</p>
                        </div>
                    </div>
                    
                    <!-- Tables Overview -->
                    <h6 class="text-primary mb-3"><i class="fas fa-table me-1"></i> Tabel Inti</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Tabel</th>
                                    <th>Primary Key</th>
                                    <th>Relationships</th>
                                    <th>Records</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>products</code></td>
                                    <td><span class="badge bg-primary">id</span></td>
                                    <td>→ stock, purchase_items</td>
                                    <td>{{ \App\Models\Product::count() }}</td>
                                </tr>
                                <tr>
                                    <td><code>stocks</code></td>
                                    <td><span class="badge bg-success">product_id</span></td>
                                    <td>← products, → stock_history</td>
                                    <td>{{ \App\Models\Stock::count() }}</td>
                                </tr>
                                <tr>
                                    <td><code>purchases</code></td>
                                    <td><span class="badge bg-info">id</span></td>
                                    <td>→ purchase_items, ← admin</td>
                                    <td>{{ \App\Models\Purchase::count() }}</td>
                                </tr>
                                <tr>
                                    <td><code>purchase_items</code></td>
                                    <td><span class="badge bg-warning">id</span></td>
                                    <td>← purchases, → products</td>
                                    <td>{{ \App\Models\PurchaseItem::count() }}</td>
                                </tr>
                                <tr>
                                    <td><code>admins</code></td>
                                    <td><span class="badge bg-danger">id</span></td>
                                    <td>→ purchases, → stock_history</td>
                                    <td>{{ \App\Models\Admin::count() }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Optimization Tips -->
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6><i class="fas fa-tachometer-alt text-success me-2"></i>Optimasi Database</h6>
                                    <ul class="mb-0">
                                        <li>Indexing pada foreign keys</li>
                                        <li>Query optimization dengan Eloquent</li>
                                        <li>Database transactions untuk integrity</li>
                                        <li>Eager loading prevention</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6><i class="fas fa-shield-alt text-warning me-2"></i>Database Security</h6>
                                    <ul class="mb-0">
                                        <li>SQL injection prevention</li>
                                        <li>Parameterized queries</li>
                                        <li>Access control levels</li>
                                        <li>Regular backup system</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chatbot System Section -->
            <div class="card border-0 shadow-sm mb-4" id="chatbot">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-robot fa-lg text-danger"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0">AI Chatbot System</h5>
                            <p class="text-muted mb-0">Intelligent Assistant untuk Data Analysis</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-brain fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="alert-heading">Fitur AI-Powered Assistant</h6>
                                <p class="mb-0">Sistem chatbot cerdas yang dapat menjawab pertanyaan tentang data toko dalam bahasa natural dengan integrasi DeepSeek API.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Architecture -->
                    <h6 class="text-primary mb-3"><i class="fas fa-network-wired me-1"></i> Arsitektur Sistem</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="card text-center h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <i class="fas fa-plug fa-2x text-primary"></i>
                                    </div>
                                    <h6>API Integration</h6>
                                    <p class="small text-muted">DeepSeek API untuk natural language processing</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <i class="fas fa-sync-alt fa-2x text-success"></i>
                                    </div>
                                    <h6>Real-time Data</h6>
                                    <p class="small text-muted">Live store statistics sebagai konteks</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <i class="fas fa-code-branch fa-2x text-warning"></i>
                                    </div>
                                    <h6>Fallback System</h6>
                                    <p class="small text-muted">Rule-based response jika API down</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Available Data -->
                    <h6 class="text-primary mb-3"><i class="fas fa-chart-bar me-1"></i> Data yang Tersedia</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Total Produk
                                    <span class="badge bg-primary rounded-pill">{{ \App\Models\Product::count() }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Total Pembelian
                                    <span class="badge bg-success rounded-pill">{{ \App\Models\Purchase::count() }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Revenue Total
                                    <span class="badge bg-info rounded-pill">Rp {{ number_format(\App\Models\Purchase::where('status', 'completed')->sum('total_amount'), 0, ',', '.') }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Stok Rendah (≤5)
                                    <span class="badge bg-warning rounded-pill">{{ \App\Models\Product::whereHas('stock', function($q) { $q->where('quantity', '<=', 5); })->count() }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Pembelian Hari Ini
                                    <span class="badge bg-danger rounded-pill">{{ \App\Models\Purchase::whereDate('purchase_date', today())->where('status', 'completed')->count() }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Success Rate
                                    <span class="badge bg-success rounded-pill">
                                        @php
                                            $total = \App\Models\Purchase::count();
                                            $completed = \App\Models\Purchase::where('status', 'completed')->count();
                                            $rate = $total > 0 ? round(($completed / $total) * 100, 1) : 0;
                                        @endphp
                                        {{ $rate }}%
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Example Queries -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="text-primary"><i class="fas fa-comments me-1"></i> Contoh Query</h6>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <div class="bg-dark text-white p-2 rounded">
                                    <small>"Berapa total revenue hari ini?"</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-dark text-white p-2 rounded">
                                    <small>"Produk apa yang stoknya rendah?"</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-dark text-white p-2 rounded">
                                    <small>"Berapa success rate pembelian?"</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scalability Section -->
            <div class="card border-0 shadow-sm mb-4" id="scalability">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-expand-arrows-alt fa-lg text-purple"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0">Potensi Pengembangan & Skalabilitas</h5>
                            <p class="text-muted mb-0">Roadmap dari Starter ke Enterprise</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Development Roadmap -->
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="text-primary">Fase 1: Sistem Dasar (Current)</h6>
                                <ul class="mb-0">
                                    <li>Admin dashboard dengan statistik</li>
                                    <li>Manajemen produk dan stok</li>
                                    <li>Sistem pembelian sederhana</li>
                                    <li>AI chatbot basic</li>
                                </ul>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="text-success">Fase 2: Marketplace Basic</h6>
                                <ul class="mb-0">
                                    <li>Multi-vendor system</li>
                                    <li>User roles & permissions</li>
                                    <li>Commission management</li>
                                    <li>Product reviews & ratings</li>
                                </ul>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="text-warning">Fase 3: E-commerce Professional</h6>
                                <ul class="mb-0">
                                    <li>Payment gateway integration</li>
                                    <li>Shipping & logistics system</li>
                                    <li>CRM & customer loyalty</li>
                                    <li>Advanced analytics & reporting</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Technical Scalability -->
                    <h6 class="text-primary mt-4 mb-3"><i class="fas fa-server me-1"></i> Teknologi Skalabilitas</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Komponen</th>
                                    <th>Status Saat Ini</th>
                                    <th>Potensi Pengembangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Authentication</strong></td>
                                    <td><span class="badge bg-primary">Multi-guard</span></td>
                                    <td>OAuth2, Social login, 2FA</td>
                                </tr>
                                <tr>
                                    <td><strong>Cache System</strong></td>
                                    <td><span class="badge bg-secondary">Database</span></td>
                                    <td>Redis, Memcached, CDN</td>
                                </tr>
                                <tr>
                                    <td><strong>API Structure</strong></td>
                                    <td><span class="badge bg-info">Monolithic</span></td>
                                    <td>RESTful API, GraphQL, Microservices</td>
                                </tr>
                                <tr>
                                    <td><strong>Search Engine</strong></td>
                                    <td><span class="badge bg-warning">Basic LIKE</span></td>
                                    <td>Elasticsearch, Algolia</td>
                                </tr>
                                <tr>
                                    <td><strong>AI/ML Capabilities</strong></td>
                                    <td><span class="badge bg-danger">Basic Chatbot</span></td>
                                    <td>Recommendation engine, Predictive analytics</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Security Section -->
            <div class="card border-0 shadow-sm mb-4" id="security">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-shield-alt fa-lg text-dark"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0">Keamanan & Best Practices</h5>
                            <p class="text-muted mb-0">Security Implementation & Guidelines</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Security Features -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card h-100 border-left-success">
                                <div class="card-body">
                                    <h6><i class="fas fa-user-lock text-success me-2"></i>Authentication Security</h6>
                                    <ul class="mb-0">
                                        <li>Password hashing dengan bcrypt</li>
                                        <li>Session management dengan timeout</li>
                                        <li>Multi-guard authentication</li>
                                        <li>CSRF token protection</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-left-warning">
                                <div class="card-body">
                                    <h6><i class="fas fa-database text-warning me-2"></i>Database Security</h6>
                                    <ul class="mb-0">
                                        <li>SQL injection prevention dengan Eloquent</li>
                                        <li>Parameterized queries</li>
                                        <li>Input validation & sanitization</li>
                                        <li>XSS protection</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Security Checklist -->
                    <h6 class="text-primary mb-3"><i class="fas fa-clipboard-check me-1"></i> Security Checklist</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>Area Keamanan</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Password Security</td>
                                    <td><span class="badge bg-success">✓ Implemented</span></td>
                                    <td>Bcrypt hashing dengan cost 12</td>
                                </tr>
                                <tr>
                                    <td>SQL Injection</td>
                                    <td><span class="badge bg-success">✓ Protected</span></td>
                                    <td>Eloquent ORM dengan parameter binding</td>
                                </tr>
                                <tr>
                                    <td>XSS Protection</td>
                                    <td><span class="badge bg-success">✓ Enabled</span></td>
                                    <td>Blade template escaping</td>
                                </tr>
                                <tr>
                                    <td>CSRF Protection</td>
                                    <td><span class="badge bg-success">✓ Active</span></td>
                                    <td>Laravel CSRF tokens untuk semua form</td>
                                </tr>
                                <tr>
                                    <td>File Upload Security</td>
                                    <td><span class="badge bg-success">✓ Secure</span></td>
                                    <td>File type validation & secure storage</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Usage Guide Section -->
            <div class="card border-0 shadow-sm mb-4" id="usage">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-tie fa-lg text-secondary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0">Panduan Penggunaan</h5>
                            <p class="text-muted mb-0">Step-by-step Guide untuk Administrator</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Quick Start -->
                    <h6 class="text-primary mb-3"><i class="fas fa-play-circle me-1"></i> Quick Start</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6><i class="fas fa-box me-2 text-primary"></i>Menambah Produk Baru</h6>
                                    <ol class="mb-0">
                                        <li>Login sebagai admin</li>
                                        <li>Buka menu <strong>Products → Product List</strong></li>
                                        <li>Klik tombol <strong>Add Product</strong></li>
                                        <li>Isi detail produk dan upload gambar</li>
                                        <li>Set initial stock quantity</li>
                                        <li>Klik <strong>Save Product</strong></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6><i class="fas fa-shopping-cart me-2 text-success"></i>Membuat Pembelian</h6>
                                    <ol class="mb-0">
                                        <li>Buka menu <strong>Purchases → New Purchase</strong></li>
                                        <li>Pilih produk dari available stock</li>
                                        <li>Tentukan quantity untuk setiap produk</li>
                                        <li>Isi data customer jika diperlukan</li>
                                        <li>Review total amount</li>
                                        <li>Klik <strong>Create Purchase</strong></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- AI Chatbot Usage -->
                    <h6 class="text-primary mb-3"><i class="fas fa-robot me-1"></i> Menggunakan AI Assistant</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card border-left-info">
                                <div class="card-body">
                                    <h6 class="text-info"><i class="fas fa-comment me-2"></i>Cara Bertanya</h6>
                                    <ul class="mb-0">
                                        <li>Buka menu <strong>AI Assistant</strong></li>
                                        <li>Gunakan bahasa natural (Bahasa Indonesia)</li>
                                        <li>Ajukan pertanyaan spesifik tentang data</li>
                                        <li>Tunggu respons dalam beberapa detik</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-left-success">
                                <div class="card-body">
                                    <h6 class="text-success"><i class="fas fa-lightbulb me-2"></i>Contoh Pertanyaan Efektif</h6>
                                    <ul class="mb-0">
                                        <li>"Berapa total revenue bulan ini?"</li>
                                        <li>"Produk apa yang paling laris?"</li>
                                        <li>"Berapa persentase pembelian cancelled?"</li>
                                        <li>"Stok produk apa yang rendah?"</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Troubleshooting Section -->
            <div class="card border-0 shadow-sm" id="troubleshooting">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-wrench fa-lg text-orange"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0">Troubleshooting & Support</h5>
                            <p class="text-muted mb-0">Common Issues & Solutions</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- FAQ Accordion -->
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    <i class="fas fa-robot text-danger me-2"></i>AI Chatbot tidak merespon
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show">
                                <div class="accordion-body">
                                    <p><strong>Kemungkinan Penyebab:</strong></p>
                                    <ul>
                                        <li>API key tidak terkonfigurasi di .env</li>
                                        <li>API limit tercapai</li>
                                        <li>Koneksi internet bermasalah</li>
                                    </ul>
                                    <p><strong>Solusi:</strong></p>
                                    <ol>
                                        <li>Pastikan <code>DEEPSEEK_API_KEY</code> ada di file .env</li>
                                        <li>Test koneksi di menu Chatbot → Test API</li>
                                        <li>Sistem akan fallback ke rule-based mode jika API down</li>
                                        <li>Cek credit API di dashboard DeepSeek</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    <i class="fas fa-shopping-cart text-warning me-2"></i>Error saat membuat purchase
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    <p><strong>Kemungkinan Penyebab:</strong></p>
                                    <ul>
                                        <li>Stok produk tidak mencukupi</li>
                                        <li>Data input tidak valid</li>
                                        <li>Database connection error</li>
                                    </ul>
                                    <p><strong>Solusi:</strong></p>
                                    <ol>
                                        <li>Cek available stock di product list</li>
                                        <li>Pastikan quantity tidak melebihi stok</li>
                                        <li>Refresh halaman dan coba lagi</li>
                                        <li>Validasi semua field input</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    <i class="fas fa-database text-info me-2"></i>Produk tidak muncul di stock management
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    <p><strong>Kemungkinan Penyebab:</strong></p>
                                    <ul>
                                        <li>Stock record belum dibuat untuk produk</li>
                                        <li>Produk tidak aktif (is_active = 0)</li>
                                        <li>Relasi database bermasalah</li>
                                    </ul>
                                    <p><strong>Solusi:</strong></p>
                                    <ol>
                                        <li>Buat stock record manual melalui database</li>
                                        <li>Pastikan product memiliki <code>is_active = 1</code></li>
                                        <li>Reload halaman untuk refresh cache</li>
                                        <li>Cek relasi di phpMyAdmin</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Support Section -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="text-primary mb-3"><i class="fas fa-life-ring me-1"></i> Support Channels</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6><i class="fas fa-book me-2 text-primary"></i>Dokumentasi</h6>
                                        <p class="small mb-0">Referensi utama adalah halaman ini. Update terbaru dapat dilihat di changelog system.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6><i class="fas fa-code me-2 text-success"></i>Developer Support</h6>
                                        <p class="small mb-0">Untuk bug reports atau feature requests, buka issue di repository GitHub project.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-4 text-center">
                <p class="text-muted small">
                    <i class="fas fa-code"></i> Furniture Store Admin System v1.0 | 
                    <i class="fas fa-copyright"></i> {{ date('Y') }} | 
                    Document Version: 1.3 | 
                    Last Updated: {{ date('d F Y') }}
                </p>
                <div class="text-muted">
                    <small>
                        Sistem ini merupakan landasan dasar yang dapat dikembangkan menjadi platform e-commerce profesional skala besar.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
@section('scripts')
<script>
// Smooth scroll untuk anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        if(targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if(targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 100,
                behavior: 'smooth'
            });
        }
    });
});

// Highlight active section in sidebar
window.addEventListener('scroll', function() {
    const sections = document.querySelectorAll('div[id]');
    const navLinks = document.querySelectorAll('.list-group-item');
    
    let current = '';
    sections.forEach(section => {
        const sectionTop = section.offsetTop - 150;
        if(window.scrollY >= sectionTop) {
            current = section.getAttribute('id');
        }
    });
    
    navLinks.forEach(link => {
        link.classList.remove('active');
        if(link.getAttribute('href') === `#${current}`) {
            link.classList.add('active');
        }
    });
});

// Test API function
function testApi() {
    alert('Testing API connection...');
    // Implementation for testing API would go here
}
</script>

<style>
/* Custom Styles */
.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.list-group-item.active {
    background-color: #4e73df;
    border-color: #4e73df;
    color: white;
}

.border-left-primary {
    border-left: 4px solid #4e73df !important;
}

.border-left-success {
    border-left: 4px solid #1cc88a !important;
}

.border-left-info {
    border-left: 4px solid #36b9cc !important;
}

.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}

.border-left-danger {
    border-left: 4px solid #e74a3b !important;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
}

/* Timeline styling */
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-content {
    padding-bottom: 15px;
    border-bottom: 1px solid #e3e6f0;
}

.timeline-item:last-child .timeline-content {
    border-bottom: none;
}

/* Accordion styling */
.accordion-button {
    background-color: #f8f9fc;
    color: #5a5c69;
    font-weight: 500;
}

.accordion-button:not(.collapsed) {
    background-color: #e3e6f0;
    color: #4e73df;
}

/* Quick stats */
.badge {
    font-weight: 500;
}

/* Z-index fix - mencegah card mengambang di atas navbar */
.sticky-top {
    position: sticky;
    top: 1rem;
    z-index: 1020;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card-header .d-flex {
        flex-direction: column;
        text-align: center;
    }
    
    .card-header .ms-3 {
        margin-left: 0 !important;
        margin-top: 10px;
    }
}
</style>
@endsection
@endsection