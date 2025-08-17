<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Penagihan Internet')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    :root {
        --primary-color: #4c51bf;
        --secondary-color: #667eea;
    }

    body {
        overflow-x: hidden;
    }

    .sidebar {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        min-height: 100vh;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        padding: 1rem;
        transition: all 0.3s ease;
    }

    /* --- PERUBAHAN DI SINI --- */
    .sidebar .nav-link {
        color: rgba(255, 255, 255, 0.8);
        transition: all 0.3s ease;
        border-radius: 8px;
        margin: 5px 0;
        /* Menambah jarak antar menu */
        padding: 12px 16px;
        /* Menambah ruang di dalam item menu */
        font-size: 1.1rem;
        /* Memperbesar ukuran font */
        font-weight: 500;
        /* Menambahkan bobot font */
    }

    /* --- AKHIR PERUBAHAN --- */

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        color: white;
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateX(5px);
    }

    .card {
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .btn-primary {
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        border: none;
        border-radius: 25px;
        padding: 8px 20px;
    }

    .table th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
    }

    .badge {
        border-radius: 20px;
        padding: 5px 12px;
    }

    /* Mobile Sidebar */
    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            height: 100%;
            z-index: 1050;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.2);
        }

        .sidebar.active {
            left: 0;
        }

        .toggle-sidebar {
            display: inline-block;
        }

        /* overlay saat sidebar muncul */
        #sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            display: none;
        }

        #sidebar-overlay.active {
            display: block;
        }
    }

    @media (min-width: 769px) {
        .toggle-sidebar {
            display: none;
        }
    }
    </style>
</head>

<body>
    <div id="sidebar-overlay" onclick="toggleSidebar()"></div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar" id="sidebar">
                    <h4 class="text-white mb-4">
                        <i class="fas fa-wifi"></i> Billing System
                    </h4>

                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('packages.*') ? 'active' : '' }}"
                            href="{{ route('packages.index') }}">
                            <i class="fas fa-box me-2"></i> Paket
                        </a>


                        <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}"
                            href="{{ route('customers.index') }}">
                            <i class="fas fa-users me-2"></i> Pelanggan
                        </a>

                        <a class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}"
                            href="{{ route('invoices.index') }}">
                            <i class="fas fa-file-invoice-dollar me-2"></i> Tagihan
                        </a>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reports.index') }}">
                                <i class="fas fa-chart-line"></i> <span>Laporan</span>
                            </a>
                        </li>
                                                <li class="nav-item">
    <a href="{{ route('pindaiqr.index') }}" class="nav-link">
        <i class="fas fa-qrcode"></i>
        <p>Pindai QR</p>
    </a>
</li>

                    </nav>

                    <button class="btn btn-light d-md-none mt-3 w-100" onclick="toggleSidebar()">
                        <i class="fas fa-times me-2"></i> Tutup
                    </button>
                </div>
            </div>

            <div class="col-md-9 col-lg-10">
                <div class="p-4">
                    <button class="btn btn-primary d-md-none mb-3 toggle-sidebar" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i> Menu
                    </button>

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    }
    </script>
    @stack('scripts')
</body>

</html>