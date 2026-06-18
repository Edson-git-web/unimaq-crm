<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UNIMAQ CRM') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    @guest
        @yield('content')
    @else
        <div id="app-wrapper">
            <!-- Sidebar -->
            <nav id="sidebar">
                <div class="sidebar-header">
                    <img src="{{ asset('images/crm.png') }}" alt="UNIMAQ Logo" class="img-fluid">
                </div>
                <div class="d-flex flex-column mt-3">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-house"></i> Inicio
                    </a>
                    <a class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}" href="{{ route('clientes.index') }}">
                        <i class="bi bi-people"></i> Clientes
                    </a>
                    <a class="nav-link {{ request()->routeIs('cotizaciones.*') ? 'active' : '' }}" href="{{ route('cotizaciones.index') }}">
                        <i class="bi bi-file-text"></i> Cotizaciones
                    </a>
                    <a class="nav-link {{ request()->routeIs('ventas.*') ? 'active' : '' }}" href="{{ route('ventas.index') }}">
                        <i class="bi bi-cart"></i> Ventas
                    </a>
                    <a class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}" href="{{ route('reportes.index') }}">
                        <i class="bi bi-graph-up"></i> Reportes
                    </a>
                    @if(optional(Auth::user()->rol)->nombre_rol === 'Administrador')
                        <hr class="border-secondary my-2">
                        <small class="text-uppercase text-muted px-3 pb-2 d-block" style="font-size: 0.75rem;">Administración</small>
                        <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}">
                            <i class="bi bi-person-badge"></i> Usuarios
                        </a>
                        <a class="nav-link {{ request()->routeIs('auditoria.*') ? 'active' : '' }}" href="{{ route('auditoria.index') }}">
                            <i class="bi bi-list-check"></i> Auditoría
                        </a>
                    @endif
                </div>
            </nav>

            <!-- Page Content -->
            <div id="page-content">
                <!-- Topbar -->
                <div class="topbar d-flex justify-content-between align-items-center">
                    <div>
                        <button class="btn btn-outline-secondary d-md-none me-2" type="button" id="sidebarToggle">
                            <i class="bi bi-list"></i>
                        </button>
                        <span class="fs-5 fw-bold d-none d-md-inline" style="color: var(--secondary-color);">{{ config('app.name', 'UNIMAQ CRM') }}</span>
                    </div>

                    <div class="dropdown">
                        <a class="text-decoration-none dropdown-toggle d-flex align-items-center text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px;">
                                {{ substr(Auth::user()->nombre ?? 'U', 0, 1) }}
                            </div>
                            <span class="d-none d-sm-inline">{{ Auth::user()->nombre ?? 'Usuario' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                            <li>
                                <a class="dropdown-item" href="{{ route('perfil.edit') }}">
                                    <i class="bi bi-person me-2"></i> Mi Perfil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Main Content -->
                <main class="py-4">
                    <div class="container-fluid px-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>

        <script>
            document.getElementById('sidebarToggle')?.addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('show');
            });
        </script>
    @endguest
</body>
</html>
