<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - CV Rangga Soly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --font-family: 'Inter', sans-serif;
            --brand-primary: #0d6efd;
            --brand-light: #f1f5f9;
            --text-primary: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --sidebar-bg: #0f172a;
            --sidebar-text: #cbd5e1;
            --sidebar-text-hover: #ffffff;
            --sidebar-text-active: #ffffff;
            --sidebar-bg-active: var(--brand-primary);
            --sidebar-width: 260px;
            --sidebar-width-collapsed: 80px;
        }

        body {
            background-color: var(--brand-light);
            color: var(--text-primary);
            font-family: var(--font-family);
        }

        /*
        =================================
        SIDEBAR (DARK MODE)
        =================================
        */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-bg);
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: width 0.3s ease-in-out;
            overflow-x: hidden;
        }

        .sidebar .logo {
            font-weight: 700;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #ffffff;
            margin-bottom: 0.25rem;
            padding-left: 0.5rem;
            white-space: nowrap;
        }

        .sidebar .subtitle {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
            padding-left: 0.5rem;
            white-space: nowrap;
        }

        .sidebar .nav-heading {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--text-muted);
            padding: 0.5rem 1rem;
            margin-top: 1rem;
            white-space: nowrap;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: .75rem 1rem;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: .5rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
            transition: background 0.2s, color 0.2s;
            font-size: 0.95rem;
            white-space: nowrap;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: var(--sidebar-text-hover);
        }

        .sidebar .nav-link.active {
            background-color: var(--sidebar-bg-active);
            color: var(--sidebar-text-active);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            transition: all 0.3s;
        }

        /*
        =================================
        ADMIN DROPDOWN
        =================================
        */
        .admin-dropdown .dropdown-toggle {
            color: var(--sidebar-text);
            text-decoration: none;
            padding: 0.5rem;
            border-radius: .5rem;
            transition: background 0.2s;
            white-space: nowrap;
        }

        .admin-dropdown .dropdown-toggle:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .admin-dropdown .dropdown-toggle::after {
            display: none;
        }

        .admin-dropdown-icon {
            transition: opacity 0.2s, transform 0.3s ease;
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .admin-dropdown .dropdown-toggle[aria-expanded="true"] .admin-dropdown-icon {
            transform: rotate(180deg);
        }

        .admin-dropdown .admin-details {
            transition: opacity 0.2s;
        }

        .admin-dropdown .dropdown-menu {
            background: #1e293b;
            border-color: #334155;
            width: 100%;
        }

        .admin-dropdown .dropdown-item {
            color: var(--sidebar-text);
        }

        .admin-dropdown .dropdown-item:hover {
            background: var(--brand-primary);
            color: #fff;
        }

        .admin-dropdown .dropdown-item.text-danger:hover {
            background: #ef4444;
            color: #fff;
        }


        /*
        =================================
        MAIN CONTENT & NAVBAR
        =================================
        */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease-in-out;
        }

        .navbar-top {
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 10;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: 1.5rem;
        }

        .sidebar-toggle {
            color: var(--text-primary);
            text-decoration: none;
            margin-right: 0.5rem;
        }

        .sidebar-toggle:hover {
            color: var(--brand-primary);
        }

        .navbar-top .page-title {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--text-primary);
            margin-bottom: 0;
        }

        .navbar-top .search-box {
            width: 100%;
            max-width: 350px;
            margin: 0 auto;
        }

        .navbar-top .search-box input {
            border-radius: .5rem;
            border: 1px solid var(--border-color);
            padding: 0.5rem 2.5rem 0.5rem 1rem;
            background: var(--brand-light);
        }

        .navbar-top .search-box .bi-search {
            color: var(--text-muted);
        }

        .navbar-top .navbar-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            justify-content: flex-end;
        }

        .card {
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-radius: 0.75rem;
            background-color: #ffffff;
        }

        main {
            padding: 1.5rem 2rem;
        }

        /*
        =================================
        EFEK SIDEBAR DITUTUP
        =================================
        */
        body.sidebar-collapsed .sidebar {
            width: var(--sidebar-width-collapsed);
        }

        body.sidebar-collapsed .main-content {
            margin-left: var(--sidebar-width-collapsed);
        }

        body.sidebar-collapsed .sidebar .nav-link {
            justify-content: center;
        }

        body.sidebar-collapsed .sidebar .nav-link span {
            display: none;
        }

        body.sidebar-collapsed .sidebar .logo span,
        body.sidebar-collapsed .sidebar .logo .bi,
        body.sidebar-collapsed .sidebar .subtitle,
        body.sidebar-collapsed .sidebar .nav-heading {
            display: none;
        }

        body.sidebar-collapsed .admin-dropdown .admin-details,
        body.sidebar-collapsed .admin-dropdown .admin-dropdown-icon {
            display: none;
        }

        body.sidebar-collapsed .admin-dropdown .dropdown-toggle {
            justify-content: center;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="sidebar">
        <div class="logo">
            <i class="bi bi-building-fill"></i>
            <span>CV Rangga Soly</span>
        </div>
        <div class="subtitle">Sistem Penggajian</div>

        <div class="flex-grow-1">
            <div class="nav-heading">Main</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> <span>Dashboard</span>
            </a>

            <div class="nav-heading">Manajemen</div>
            <a href="{{ route('employees.index') }}"
                class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> <span>Data Karyawan</span>
            </a>
            <a href="{{ route('attendances.index') }}"
                class="nav-link {{ request()->routeIs('attendances.*', 'leaves.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check-fill"></i> <span>Data Absensi</span>
            </a>
            <a href="{{ route('salary.index') }}"
                class="nav-link {{ request()->routeIs('salary.*') ? 'active' : '' }}"><i class="bi bi-cash-stack"></i>
                <span>Penggajian</span></a>

            <div class="nav-heading">Lainnya</div>
            <a href="{{ route('report.index') }}" class="nav-link {{ request()->routeIs('report.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-data-fill"></i> <span>Laporan</span>
            </a>
        </div>
        <div class="mt-auto border-top pt-3 admin-dropdown" style="border-color: #334155 !important;">
            <div class="dropdown">
                <a href="#" class="dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <div class="admin-avatar rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 38px; height: 38px; overflow: hidden;">
                        <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=0f172a&color=cbd5e1' }}"
                            alt="Avatar" width="38" height="38" style="object-fit: cover;">
                    </div>
                    <div class="admin-details">
                        <div class="name fw-semibold" style="font-size: 0.9rem; color: var(--sidebar-text-hover);">
                            {{ Auth::user()->name }}
                        </div>
                        <div class="role" style="font-size: 0.8rem; color: var(--text-muted);">Administrator</div>
                    </div>
                    <i class="bi bi-chevron-up ms-auto admin-dropdown-icon"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark shadow">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person-circle me-2"></i> Akun Saya
                        </a></li>
                    <li>
                        <hr class="dropdown-divider" style="border-color: #334155;">
                    </li>
                    <li>
                        <a class="dropdown-item text-danger" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i> Keluar
                        </a>
                    </li>
                </ul>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="navbar-top">

            <div class="d-flex align-items-center">
                <a class="sidebar-toggle" href="#" id="sidebarToggle">
                    <i class="bi bi-list fs-2"></i>
                </a>
                <h4 class="page-title mb-0">
                    @yield('title', 'Dashboard')
                </h4>
            </div>

            <div class="search-box position-relative d-none d-md-block">
                <input type:="text" class="form-control" id="employee-search" placeholder="Cari karyawan..."
                    autocomplete="off">
                <i class="bi bi-search position-absolute"
                    style="right: 1rem; top: 50%; transform: translateY(-50%);"></i>
                <ul id="search-results" class="list-group position-absolute w-100 shadow"
                    style="top: 110%; z-index: 9999; display:none; max-height:300px; overflow-y:auto;">
                </ul>
            </div>

            <div class="navbar-right">
                <div class="dropdown">
                    <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                        style="color: var(--text-muted);">
                        <div class="notif position-relative">
                            <i class="bi bi-bell-fill fs-5"></i>
                            @if ($recent_logs->count() > 0)
                                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle"
                                    style="font-size: 0.65rem; padding: 2px 5px; border-radius: 50%;">
                                    {{ $recent_logs->count() }}
                                </span>
                            @endif
                        </div>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="width: 350px;">
                        <li class="dropdown-header">
                            <h6 class="fw-bold mb-0">Aktivitas Terbaru</h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        @forelse($recent_logs as $log)
                            <li class="px-3 py-2">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($log->employee->name ?? 'S') }}&background=0f172a&color=cbd5e1&size=40"
                                            alt="Avatar" class="rounded-circle">
                                    </div>
                                    <div style="line-height: 1.4;">
                                        <small class="fw-bold d-block">{{ $log->employee->name ?? 'Sistem' }}</small>
                                        <small class="text-muted d-block">{{ $log->activity }}</small>
                                        <small class="text-muted" style="font-size: 0.75rem;">
                                            <i class="bi bi-clock-fill"></i> {{ $log->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="px-3 py-2 text-center text-muted">
                                <small>Tidak ada aktivitas terbaru.</small>
                            </li>
                        @endforelse

                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item text-center text-primary fw-semibold"
                                href="{{ route('activity.index') }}" style="font-size: 0.9rem;">
                                Lihat Semua Aktivitas
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <main>
            @yield('content')
        </main>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Cek jika ada sesi 'success'
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 2500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif

        // Cek jika ada sesi 'error'
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}'
            });
        @endif
    </script>

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- SKRIP UNTUK TOGGLE SIDEBAR ---
            const toggleBtn = document.getElementById('sidebarToggle');
            const body = document.body;

            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                body.classList.add('sidebar-collapsed');
            }

            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                body.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', body.classList.contains('sidebar-collapsed'));
            });


            // --- SKRIP PENCARIAN ---
            const searchInput = document.getElementById('employee-search');
            const resultsBox = document.getElementById('search-results');
            let timeout = null;
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const query = this.value.trim();
                    clearTimeout(timeout);
                    if (query.length < 2) {
                        resultsBox.style.display = 'none';
                        return;
                    }
                    timeout = setTimeout(() => {
                        fetch(`{{ route('search.live') }}?query=${encodeURIComponent(query)}`)
                            .then(res => res.json())
                            .then(data => {
                                resultsBox.innerHTML = '';
                                if (data.length === 0) {
                                    resultsBox.innerHTML =
                                        '<li class="list-group-item text-muted">Tidak ada hasil</li>';
                                } else {
                                    data.forEach(emp => {
                                        const li = document.createElement('li');
                                        li.className =
                                            'list-group-item list-group-item-action';
                                        li.innerHTML =
                                            `<div class="fw-semibold">${emp.name}</div><small class="text-muted">NIK: ${emp.nomor_pegawai}</small>`;
                                        li.style.cursor = 'pointer';
                                        li.onclick = () => window.location.href =
                                            `{{ url('karyawan') }}/${emp.id}`;
                                        resultsBox.appendChild(li);
                                    });
                                }
                                resultsBox.style.display = 'block';
                            })
                            .catch(err => console.error(err));
                    }, 300);
                });
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
                        resultsBox.style.display = 'none';
                    }
                });
            }
        });
    </script>

</body>

</html>
