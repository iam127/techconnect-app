@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Solicitudes de Servicio</div>

                <div class="card-body">
                    <div class="mb-4">
                        <a href="{{ route('requests.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nueva Solicitud
                        </a>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">Filtrar solicitudes</div>
                        <div class="card-body">
                            <form action="{{ route('requests.index') }}" method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <label for="status" class="form-label">Estado</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="">Todos</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En progreso</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completada</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="client_id" class="form-label">Cliente</label>
                                    <select name="client_id" id="client_id" class="form-select">
                                        <option value="">Todos</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                                {{ $client->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="technician_id" class="form-label">Técnico</label>
                                    <select name="technician_id" id="technician_id" class="form-select">
                                        <option value="">Todos</option>
                                        <option value="unassigned" {{ request('technician_id') == 'unassigned' ? 'selected' : '' }}>Sin asignar</option>
                                        @foreach($technicians as $technician)
                                            <option value="{{ $technician->id }}" {{ request('technician_id') == $technician->id ? 'selected' : '' }}>
                                                {{ $technician->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="date_range" class="form-label">Rango de fechas</label>
                                    <input type="text" class="form-control" id="date_range" name="date_range" 
                                           value="{{ request('date_range') }}" placeholder="DD/MM/YYYY - DD/MM/YYYY">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Filtrar</button>
                                    <a href="{{ route('requests.index') }}" class="btn btn-secondary">Limpiar</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Título</th>
                                    <th>Cliente</th>
                                    <th>Técnico</th>
                                    <th>Estado</th>
                                    <th>Fecha de creación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $request)
                                    <tr>
                                        <td>{{ $request->id }}</td>
                                        <td>{{ $request->title }}</td>
                                        <td>{{ $request->client->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($request->technician)
                                                {{ $request->technician->name }}
                                            @else
                                                <span class="badge bg-warning text-dark">Sin asignar</span>
                                            @endif
                                        </td>
                                        <td>
                                            @switch($request->status)
                                                @case('pending')
                                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                                    @break
                                                @case('in_progress')
                                                    <span class="badge bg-info">En progreso</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-success">Completada</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge bg-danger">Cancelada</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $request->status }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('requests.show', $request) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('requests.edit', $request) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal{{ $request->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>

                                            <div class="modal fade" id="deleteModal{{ $request->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $request->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel{{ $request->id }}">Confirmar eliminación</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            ¿Está seguro de que desea eliminar esta solicitud?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <form action="{{ route('requests.destroy', $request) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Eliminar</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No hay solicitudes de servicio disponibles.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
  
    });
</script>
@endsection