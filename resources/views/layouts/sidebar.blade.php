<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-wifi"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Billing System</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Paket -->
    <li class="nav-item {{ request()->routeIs('packages.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('packages.index') }}">
            <i class="fas fa-fw fa-box"></i>
            <span>Paket</span>
        </a>
    </li>

    <!-- Nav Item - Pelanggan -->
    <li class="nav-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('customers.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Pelanggan</span>
        </a>
    </li>

    <!-- Nav Item - Tagihan -->
    <li class="nav-item {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('invoices.index') }}">
            <i class="fas fa-fw fa-file-invoice-dollar"></i>
            <span>Tagihan</span>
        </a>
    </li>

    <!-- Nav Item - Laporan -->
    <li class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('reports.index') }}">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Laporan</span>
        </a>
    </li>

    <!-- Nav Item - Pindai QR -->
    <!-- <li class="nav-item {{ request()->routeIs('pindaiqr.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('pindaiqr.index') }}">
            <i class="fas fa-fw fa-qrcode"></i>
            <span>Pindai QR</span>
        </a>
    </li> -->

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>