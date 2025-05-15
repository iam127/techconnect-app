@extends('layouts.app')

@section('title', 'Explorar Servicios')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Explorar Servicios</h1>

    @if($services->count())
        <div class="row">
            @foreach($services as $service)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $service->title }}</h5>
                            <p class="card-text">{{ Str::limit($service->description, 100) }}</p>
                        </div>
                        <div class="card-footer text-end">
                            <small class="text-muted">Precio: ${{ $service->price }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $services->links() }}
        </div>
    @else
        <p>No hay servicios disponibles por ahora.</p>
    @endif
</div>
@endsection
