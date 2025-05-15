@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('img/default-avatar.png') }}" alt="Profile" class="rounded-circle me-2" width="50">
                        <div>
                            <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                            <span class="text-muted small">
                                @if(Auth::user()->role == 'client')
                                    Cliente
                                @else
                                    Técnico
                                @endif
                            </span>
                        </div>
                    </div>
                    <hr>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link active">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        @if(Auth::user()->role == 'client')
                            <li class="nav-item">
                                <a href="{{ route('requests.index') }}" class="nav-link">
                                    <i class="bi bi-file-earmark-text me-2"></i> Mis Solicitudes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('requests.create') }}" class="nav-link">
                                    <i class="bi bi-plus-circle me-2"></i> Nueva Solicitud
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('services.index') }}" class="nav-link">
                                    <i class="bi bi-tools me-2"></i> Mis Servicios
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('offers.index') }}" class="nav-link">
                                    <i class="bi bi-cash-stack me-2"></i> Mis Ofertas
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{ route('appointments.index') }}" class="nav-link">
                                <i class="bi bi-calendar-week me-2"></i> Agenda
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('messages.index') }}" class="nav-link">
                                <i class="bi bi-chat-dots me-2"></i> Mensajes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('profile.edit') }}" class="nav-link">
                                <i class="bi bi-person me-2"></i> Mi Perfil
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <!-- Main Content -->
            <h2 class="mb-4">Panel de Control</h2>
            
            <!-- Stats Overview -->
            <div class="row mb-4">
                @if(Auth::user()->role == 'client')
                    <div class="col-md-4">
                        <div class="card bg-primary text-white shadow-sm mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Solicitudes Activas</h5>
                                <h2 class="display-6">{{ $activeRequests ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white shadow-sm mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Servicios Completados</h5>
                                <h2 class="display-6">{{ $completedServices ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white shadow-sm mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Próximas Citas</h5>
                                <h2 class="display-6">{{ $upcomingAppointments ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-md-4">
                        <div class="card bg-primary text-white shadow-sm mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Servicios Activos</h5>
                                <h2 class="display-6">{{ $activeServices ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white shadow-sm mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Ofertas Enviadas</h5>
                                <h2 class="display-6">{{ $sentOffers ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white shadow-sm mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Calificación Promedio</h5>
                                <h2 class="display-6">{{ $averageRating ?? '4.5' }}<small>/5</small></h2>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Recent Activity -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Actividad Reciente</h5>
                </div>
                <div class="card-body">
                    @if(isset($recentActivities) && count($recentActivities) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($recentActivities as $activity)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi {{ $activity->icon }} me-2 text-primary"></i>
                                        {{ $activity->description }}
                                        <br>
                                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted text-center my-3">No hay actividad reciente para mostrar.</p>
                        
                        <!-- Dummy data for preview -->
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-check-circle-fill me-2 text-success"></i>
                                    Se completó el servicio de reparación de laptop
                                    <br>
                                    <small class="text-muted">Hace 2 horas</small>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-chat-dots-fill me-2 text-primary"></i>
                                    Nuevo mensaje de Juan Pérez
                                    <br>
                                    <small class="text-muted">Hace 5 horas</small>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-calendar-check-fill me-2 text-info"></i>
                                    Nueva cita programada para mañana a las 15:00
                                    <br>
                                    <small class="text-muted">Ayer</small>
                                </div>
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
            
            <!-- Upcoming Appointments -->
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Próximas Citas</h5>
                    <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
                <div class="card-body">
                    @if(isset($appointments) && count($appointments) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>{{ Auth::user()->role == 'client' ? 'Técnico' : 'Cliente' }}</th>
                                        <th>Servicio</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $appointment)
                                        <tr>
                                            <td>{{ $appointment->date }}</td>
                                            <td>{{ $appointment->time }}</td>
                                            <td>{{ $appointment->otherParty->name }}</td>
                                            <td>{{ $appointment->service_title }}</td>
                                            <td><span class="badge bg-{{ $appointment->status_color }}">{{ $appointment->status }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center my-3">No hay citas programadas próximamente.</p>
                        
                        <!-- Dummy data for preview -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>{{ Auth::user()->role == 'client' ? 'Técnico' : 'Cliente' }}</th>
                                        <th>Servicio</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>16/05/2025</td>
                                        <td>10:00 AM</td>
                                        <td>Ana García</td>
                                        <td>Mantenimiento PC</td>
                                        <td><span class="badge bg-success">Confirmado</span></td>
                                    </tr>
                                    <tr>
                                        <td>18/05/2025</td>
                                        <td>15:30 PM</td>
                                        <td>Carlos Ruiz</td>
                                        <td>Instalación de Software</td>
                                        <td><span class="badge bg-warning">Pendiente</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
