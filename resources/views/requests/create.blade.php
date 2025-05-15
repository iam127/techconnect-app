@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Nueva Solicitud de Servicio</h5>
                        <a href="{{ route('requests.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('requests.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="card mb-4">
                            <div class="card-header">Información básica</div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Título de la solicitud <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Descripción detallada <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="priority" class="form-label">Prioridad <span class="text-danger">*</span></label>
                                        <select class="form-select @error('priority') is-invalid @enderror" 
                                                id="priority" name="priority" required>
                                            <option value="">Seleccionar prioridad</option>
                                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Baja</option>
                                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Media</option>
                                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Alta</option>
                                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgente</option>
                                        </select>
                                        @error('priority')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="service_type" class="form-label">Tipo de servicio <span class="text-danger">*</span></label>
                                        <select class="form-select @error('service_type') is-invalid @enderror" 
                                                id="service_type" name="service_type" required>
                                            <option value="">Seleccionar tipo</option>
                                            <option value="installation" {{ old('service_type') == 'installation' ? 'selected' : '' }}>Instalación</option>
                                            <option value="repair" {{ old('service_type') == 'repair' ? 'selected' : '' }}>Reparación</option>
                                            <option value="maintenance" {{ old('service_type') == 'maintenance' ? 'selected' : '' }}>Mantenimiento</option>
                                            <option value="consultation" {{ old('service_type') == 'consultation' ? 'selected' : '' }}>Consultoría</option>
                                            <option value="other" {{ old('service_type') == 'other' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                        @error('service_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-header">Información del cliente</div>
                            <div class="card-body">
                                @if(auth()->user()->role === 'admin')
                                <div class="mb-3">
                                    <label for="client_id" class="form-label">Cliente <span class="text-danger">*</span></label>
                                    <select class="form-select @error('client_id') is-invalid @enderror" 
                                            id="client_id" name="client_id" required>
                                        <option value="">Seleccionar cliente</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                                {{ $client->name }} ({{ $client->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @else
                                    <input type="hidden" name="client_id" value="{{ auth()->user()->client->id }}">
                                    <p class="mb-0">Solicitud creada como: <strong>{{ auth()->user()->name }}</strong></p>
                                @endif
                                
                                <div class="mb-3">
                                    <label for="contact_name" class="form-label">Nombre de contacto</label>
                                    <input type="text" class="form-control @error('contact_name') is-invalid @enderror" 
                                           id="contact_name" name="contact_name" value="{{ old('contact_name') }}">
                                    @error('contact_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="contact_phone" class="form-label">Teléfono de contacto</label>
                                    <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                           id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}">
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="contact_email" class="form-label">Email de contacto</label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                           id="contact_email" name="contact_email" value="{{ old('contact_email') }}">
                                    @error('contact_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-header">Programación del servicio</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="preferred_date" class="form-label">Fecha preferida</label>
                                        <input type="date" class="form-control @error('preferred_date') is-invalid @enderror" 
                                               id="preferred_date" name="preferred_date" value="{{ old('preferred_date') }}">
                                        @error('preferred_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="preferred_time" class="form-label">Hora preferida</label>
                                        <input type="time" class="form-control @error('preferred_time') is-invalid @enderror" 
                                               id="preferred_time" name="preferred_time" value="{{ old('preferred_time') }}">
                                        @error('preferred_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="location" class="form-label">Ubicación del servicio</label>
                                    <textarea class="form-control @error('location') is-invalid @enderror" 
                                              id="location" name="location" rows="2">{{ old('location') }}</textarea>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="additional_notes" class="form-label">Notas adicionales</label>
                                    <textarea class="form-control @error('additional_notes') is-invalid @enderror" 
                                              id="additional_notes" name="additional_notes" rows="3">{{ old('additional_notes') }}</textarea>
                                    @error('additional_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="attachments" class="form-label">Archivos adjuntos (opcional)</label>
                                    <input type="file" class="form-control @error('attachments') is-invalid @enderror" 
                                           id="attachments" name="attachments[]" multiple>
                                    <div class="form-text">Puede adjuntar imágenes, documentos u otros archivos relevantes (máximo 5 archivos, 2MB cada uno)</div>
                                    @error('attachments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" onclick="window.history.back();">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Crear Solicitud</button>
                        </div>
                    </form>
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
