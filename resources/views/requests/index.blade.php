@extends('layouts.app')

@section('title', 'Mis Solicitudes')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('img/default-avatar.png') }}" alt="Profile" class="rounded-circle me-2" width="50">
                        <div>
                            <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                            <span class="text-muted small">Cliente</span>
                        </div>
                    </div>
                    <hr>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('requests.index') }}" class="nav-link active">
                                <i class="bi bi-file-earmark-text me-2"></i> Mis Solicitudes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('requests.create') }}" class="nav-link">
                                <i class="bi bi-plus-circle me-2"></i> Nueva Solicitud
                            </a>
                        </li>
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Mis Solicitudes</h2>
                <a href="{{ route('requests.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i> Nueva Solicitud
                </a>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form action="{{ route('requests.index') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="status" class="form-label">Estado</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Todos</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En progreso</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="sort" class="form-label">Ordenar por</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Fecha de creación</option>
                                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Título</option>
                                <option value="urgency" {{ request('sort') == 'urgency' ? 'selected' : '' }}>Urgencia</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="direction" class="form-label">Dirección</label>
                            <select class="form-select" id="direction" name="direction">
                                <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Descendente</option>
                                <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Ascendente</option>
                            </select>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                            <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                        </div>
                    </form>
                </div>
            </div>
            
            @if(count($requests) > 0)
                @foreach($requests as $request)
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title mb-0">
                                    <a href="{{ route('requests.show', $request->id) }}" class="text-decoration-none">{{ $request->title }}</a>
                                </h5>
                                <span class="badge bg-{{ $request->status_color }}">{{ $request->status_label }}</span>
                            </div>
                            <p class="card-text text-muted mb-3">{{ Str::limit($request->description, 150) }}</p>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i> Creado: {{ $request->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="bi bi-exclamation-circle me-1"></i> Urgencia: 
                                        <span class="text-{{ $request->urgency_color }}">{{ $request->urgency_label }}</span>
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted me-3">
                                        <i class="bi bi-geo-alt me-1"></i> {{ $request->location }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="bi bi-tag me-1"></i> {{ $request->category->name }}
                                    </small>
                                </div>
                                <div>
                                    @if($request->offers_count > 0)
                                        <span class="badge bg-info me-2">{{ $request->offers_count }} ofertas</span>
                                    @endif
                                    <a href="{{ route('requests.show', $request->id) }}" class="btn btn-sm btn-outline-primary">Ver detalles</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $requests->appends(request()->query())->links() }}
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-body text-center p-5">
                        <i class="bi bi-file-earmark-x text-muted display-1 mb-3"></i>
                        <h4>No tienes solicitudes</h4>
                        <p class="text-muted">Crea una nueva solicitud para conectarte con técnicos profesionales.</p>
                        <a href="{{ route('requests.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i> Nueva Solicitud
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection