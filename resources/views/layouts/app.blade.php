<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TechConnect') }} - @yield('title', 'Conectando técnicos y clientes')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="TechConnect Logo" height="30" class="d-inline-block align-top me-2">
                    {{ config('app.name', 'TechConnect') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                            </li>
                            @if(Auth::user()->role == 'client')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('requests.index') }}">{{ __('Mis Solicitudes') }}</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('services.index') }}">{{ __('Mis Servicios') }}</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('appointments.index') }}">{{ __('Agenda') }}</a>
                            </li>
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Iniciar Sesión') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Registrarse') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person me-2"></i> {{ __('Mi Perfil') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('messages.index') }}">
                                        <i class="bi bi-chat-dots me-2"></i> {{ __('Mensajes') }}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i> {{ __('Cerrar Sesión') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>

        <footer class="bg-light py-4 mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-4 mb-md-0">
                        <h5>TechConnect</h5>
                        <p class="text-muted">Conectando técnicos informáticos y clientes de manera rápida y eficiente.</p>
                    </div>
                    <div class="col-md-4 mb-4 mb-md-0">
                        <h5>Enlaces Rápidos</h5>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('home') }}" class="text-decoration-none">Inicio</a></li>
                            <li><a href="{{ route('about') }}" class="text-decoration-none">Acerca de</a></li>
                            <li><a href="{{ route('services.browse') }}" class="text-decoration-none">Servicios</a></li>
                            <li><a href="{{ route('contact') }}" class="text-decoration-none">Contacto</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5>Síguenos</h5>
                        <div class="d-flex">
                            <a href="#" class="me-3 text-decoration-none">
                                <i class="bi bi-facebook fs-4"></i>
                            </a>
                            <a href="#" class="me-3 text-decoration-none">
                                <i class="bi bi-twitter fs-4"></i>
                            </a>
                            <a href="#" class="me-3 text-decoration-none">
                                <i class="bi bi-instagram fs-4"></i>
                            </a>
                            <a href="#" class="text-decoration-none">
                                <i class="bi bi-linkedin fs-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-center text-muted">
                    <small>&copy; {{ date('Y') }} TechConnect. Todos los derechos reservados.</small>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>