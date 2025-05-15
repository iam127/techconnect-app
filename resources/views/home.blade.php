@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-3">Conecta con profesionales técnicos de confianza</h1>
                <p class="lead mb-4">TechConnect facilita la conexión entre técnicos informáticos y clientes que necesitan servicios de reparación, mantenimiento o asesoría técnica.</p>
                <div class="d-flex flex-column flex-sm-row">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 me-sm-3 mb-3 mb-sm-0">Registrarse</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">Iniciar Sesión</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg px-4">Ir al Dashboard</a>
                    @endguest
                </div>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('img/hero-image.svg') }}" alt="TechConnect Hero" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</div>

<div class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">¿Cómo funciona?</h2>
            <p class="lead text-muted">Conectamos a clientes con problemas técnicos y a profesionales cualificados</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-file-earmark-text text-primary fs-1"></i>
                        </div>
                        <h4>1. Publica tu solicitud</h4>
                        <p class="text-muted">Describe el problema técnico que necesitas resolver y compártelo en la plataforma.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-chat-dots text-primary fs-1"></i>
                        </div>
                        <h4>2. Recibe ofertas</h4>
                        <p class="text-muted">Los técnicos enviarán sus propuestas con presupuestos y tiempos estimados.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-check-circle text-primary fs-1"></i>
                        </div>
                        <h4>3. Elige y resuelve</h4>
                        <p class="text-muted">Selecciona al técnico que más te convenga y coordina el servicio por la plataforma.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-light py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Características principales</h2>
            <p class="lead text-muted">Todo lo que necesitas para gestionar tus servicios informáticos</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0">
                    <div class="card-body">
                        <i class="bi bi-shield-check text-primary fs-1 mb-3"></i>
                        <h5>Técnicos verificados</h5>
                        <p class="text-muted">Todos los técnicos pasan por un proceso de verificación para garantizar su profesionalidad.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0">
                    <div class="card-body">
                        <i class="bi bi-chat-dots text-primary fs-1 mb-3"></i>
                        <h5>Chat integrado</h5>
                        <p class="text-muted">Comunícate directamente con los técnicos o clientes a través de nuestro sistema de mensajería.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0">
                    <div class="card-body">
                        <i class="bi bi-calendar-check text-primary fs-1 mb-3"></i>
                        <h5>Agenda integrada</h5>
                        <p class="text-muted">Organiza tus citas y servicios con un calendario interactivo y notificaciones.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0">
                    <div class="card-body">
                        <i class="bi bi-credit-card text-primary fs-1 mb-3"></i>
                        <h5>Pagos seguros</h5>
                        <p class="text-muted">Realiza pagos a través de nuestra plataforma con total seguridad y transparencia.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Lo que dicen nuestros usuarios</h2>
            <p class="lead text-muted">Experiencias reales de clientes y técnicos</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-warning me-2">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <span class="text-muted">(5.0)</span>
                        </div>
                        <p class="mb-3">"Increíble servicio. Publicé mi problema y en menos de una hora ya tenía varias ofertas. El técnico que elegí fue muy profesional y resolvió mi problema rápidamente."</p>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('img/avatar-1.jpg') }}" alt="Cliente" class="rounded-circle me-3" width="50">
                            <div>
                                <h6 class="mb-0">María López</h6>
                                <small class="text-muted">Cliente</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-warning me-2">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-half"></i>
                            </div>
                            <span class="text-muted">(4.5)</span>
                        </div>
                        <p class="mb-3">"Como técnico, esta plataforma me ha ayudado a conseguir nuevos clientes y gestionar mis servicios de forma profesional. La agenda y el sistema de pagos funcionan perfectamente."</p>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('img/avatar-2.jpg') }}" alt="Técnico" class="rounded-circle me-3" width="50">
                            <div>
                                <h6 class="mb-0">Carlos Rodríguez</h6>
                                <small class="text-muted">Técnico</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-warning me-2">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star"></i>
                            </div>
                            <span class="text-muted">(4.0)</span>
                        </div>
                        <p class="mb-3">"La plataforma es muy intuitiva y fácil de usar. He solucionado varios problemas informáticos gracias a los técnicos que he encontrado aquí. Lo recomiendo totalmente."</p>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('img/avatar-3.jpg') }}" alt="Cliente" class="rounded-circle me-3" width="50">
                            <div>
                                <h6 class="mb-0">Ana Martínez</h6>
                                <small class="text-muted">Cliente</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <h2 class="fw-bold">¿Listo para comenzar?</h2>
                <p class="lead mb-0">Únete a nuestra comunidad de técnicos y clientes hoy mismo.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4">Registrarse ahora</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg px-4">Ir al Dashboard</a>
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection