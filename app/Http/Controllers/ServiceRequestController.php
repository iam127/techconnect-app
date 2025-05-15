<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\Client;
use App\Models\Technician;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ServiceRequestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = ServiceRequest::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('technician_id')) {
            if ($request->technician_id === 'unassigned') {
                $query->whereNull('technician_id');
            } else {
                $query->where('technician_id', $request->technician_id);
            }
        }

        if ($request->filled('date_range')) {
            try {
                $dates = explode(' - ', $request->date_range);
                $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                $endDate = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            } catch (\Exception $e) {
            }
        }

        if (Auth::user()->role === 'client') {
            $query->where('client_id', Auth::user()->client->id);
        } elseif (Auth::user()->role === 'technician') {
            $query->where('technician_id', Auth::user()->technician->id);
        }

        $query->orderBy('created_at', 'desc');

        $requests = $query->with(['client', 'technician'])->paginate(10);

        $clients = Client::all();
        $technicians = Technician::all();

        return view('requests.index', compact('requests', 'clients', 'technicians'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();
        return view('requests.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'service_type' => 'required|in:installation,repair,maintenance,consultation,other',
            'client_id' => 'required|exists:clients,id',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'preferred_date' => 'nullable|date',
            'preferred_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string',
            'additional_notes' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:2048',
        ]);

        $serviceRequest = new ServiceRequest();
        $serviceRequest->title = $validatedData['title'];
        $serviceRequest->description = $validatedData['description'];
        $serviceRequest->priority = $validatedData['priority'];
        $serviceRequest->service_type = $validatedData['service_type'];
        $serviceRequest->client_id = $validatedData['client_id'];
        $serviceRequest->contact_name = $validatedData['contact_name'] ?? null;
        $serviceRequest->contact_phone = $validatedData['contact_phone'] ?? null;
        $serviceRequest->contact_email = $validatedData['contact_email'] ?? null;
        $serviceRequest->preferred_date = $validatedData['preferred_date'] ?? null;
        $serviceRequest->preferred_time = $validatedData['preferred_time'] ?? null;
        $serviceRequest->location = $validatedData['location'] ?? null;
        $serviceRequest->additional_notes = $validatedData['additional_notes'] ?? null;
        $serviceRequest->status = 'pending';
        $serviceRequest->created_by = Auth::id();
        $serviceRequest->save();

        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments/' . $serviceRequest->id, 'public');
                $attachments[] = [
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ];
            }
            $serviceRequest->attachments = $attachments;
            $serviceRequest->save();
        }

        return redirect()->route('requests.show', $serviceRequest)
            ->with('success', 'Solicitud de servicio creada con éxito.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ServiceRequest  $serviceRequest
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceRequest $serviceRequest)
    {
        $this->checkAccessPermission($serviceRequest);

        $serviceRequest->load(['client', 'technician', 'comments.user']);

        $statusOptions = [
            'pending' => 'Pendiente',
            'in_progress' => 'En progreso',
            'completed' => 'Completada',
            'cancelled' => 'Cancelada',
        ];

        $technicians = Technician::all();

        return view('requests.show', compact('serviceRequest', 'statusOptions', 'technicians'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServiceRequest  $serviceRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceRequest $serviceRequest)
    {
        $this->checkAccessPermission($serviceRequest);

        $clients = Client::all();
        $technicians = Technician::all();

        return view('requests.edit', compact('serviceRequest', 'clients', 'technicians'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ServiceRequest  $serviceRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        $this->checkAccessPermission($serviceRequest);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'service_type' => 'required|in:installation,repair,maintenance,consultation,other',
            'client_id' => 'required|exists:clients,id',
            'technician_id' => 'nullable|exists:technicians,id',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'preferred_date' => 'nullable|date',
            'preferred_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string',
            'additional_notes' => 'nullable|string',
            'resolution_notes' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:2048',
        ]);

        $serviceRequest->title = $validatedData['title'];
        $serviceRequest->description = $validatedData['description'];
        $serviceRequest->priority = $validatedData['priority'];
        $serviceRequest->service_type = $validatedData['service_type'];
        $serviceRequest->client_id = $validatedData['client_id'];
        $serviceRequest->technician_id = $validatedData['technician_id'] ?? null;
        $serviceRequest->status = $validatedData['status'];
        $serviceRequest->contact_name = $validatedData['contact_name'] ?? null;
        $serviceRequest->contact_phone = $validatedData['contact_phone'] ?? null;
        $serviceRequest->contact_email = $validatedData['contact_email'] ?? null;
        $serviceRequest->preferred_date = $validatedData['preferred_date'] ?? null;
        $serviceRequest->preferred_time = $validatedData['preferred_time'] ?? null;
        $serviceRequest->location = $validatedData['location'] ?? null;
        $serviceRequest->additional_notes = $validatedData['additional_notes'] ?? null;
        $serviceRequest->resolution_notes = $validatedData['resolution_notes'] ?? null;
        $serviceRequest->updated_by = Auth::id();

        if ($serviceRequest->isDirty('status') && $serviceRequest->status === 'completed') {
            $serviceRequest->completed_at = now();
        }

        $serviceRequest->save();

        if ($request->hasFile('attachments')) {
            $existingAttachments = $serviceRequest->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments/' . $serviceRequest->id, 'public');
                $existingAttachments[] = [
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ];
            }
            $serviceRequest->attachments = $existingAttachments;
            $serviceRequest->save();
        }

        return redirect()->route('requests.show', $serviceRequest)
            ->with('success', 'Solicitud de servicio actualizada con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ServiceRequest  $serviceRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceRequest $serviceRequest)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        if (!empty($serviceRequest->attachments)) {
            foreach ($serviceRequest->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $serviceRequest->delete();

        return redirect()->route('requests.index')
            ->with('success', 'Solicitud de servicio eliminada con éxito.');
    }

    /**
     * Assign a technician to the service request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ServiceRequest  $serviceRequest
     * @return \Illuminate\Http\Response
     */
    public function assignTechnician(Request $request, ServiceRequest $serviceRequest)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        $validatedData = $request->validate([
            'technician_id' => 'required|exists:technicians,id',
        ]);

        $serviceRequest->technician_id = $validatedData['technician_id'];
        if ($serviceRequest->status === 'pending') {
            $serviceRequest->status = 'in_progress';
        }
        $serviceRequest->updated_by = Auth::id();
        $serviceRequest->save();

        return redirect()->back()->with('success', 'Técnico asignado con éxito.');
    }

    /**
     * Change the status of the service request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ServiceRequest  $serviceRequest
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, ServiceRequest $serviceRequest)
    {
        // Verificar acceso según el rol del usuario
        $this->checkAccessPermission($serviceRequest);

        $validatedData = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'resolution_notes' => 'nullable|string',
        ]);

        $serviceRequest->status = $validatedData['status'];
        
        if ($validatedData['status'] === 'completed') {
            $serviceRequest->completed_at = now();
            $serviceRequest->resolution_notes = $validatedData['resolution_notes'] ?? $serviceRequest->resolution_notes;
        }
        
        $serviceRequest->updated_by = Auth::id();
        $serviceRequest->save();

        return redirect()->back()->with('success', 'Estado de la solicitud actualizado con éxito.');
    }

    /**
     * Add a comment to the service request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ServiceRequest  $serviceRequest
     * @return \Illuminate\Http\Response
     */
    public function addComment(Request $request, ServiceRequest $serviceRequest)
    {
        $this->checkAccessPermission($serviceRequest);

        $validatedData = $request->validate([
            'comment' => 'required|string',
        ]);

        $serviceRequest->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validatedData['comment'],
        ]);

        return redirect()->back()->with('success', 'Comentario añadido con éxito.');
    }

    /**
     * Download an attachment from the service request.
     *
     * @param  \App\Models\ServiceRequest  $serviceRequest
     * @param  int  $index
     * @return \Illuminate\Http\Response
     */
    public function downloadAttachment(ServiceRequest $serviceRequest, $index)
    {
        $this->checkAccessPermission($serviceRequest);

        if (!isset($serviceRequest->attachments[$index])) {
            abort(404);
        }

        $attachment = $serviceRequest->attachments[$index];
        return Storage::disk('public')->download(
            $attachment['path'],
            $attachment['original_name']
        );
    }

    /**
     * Check if the current user has permission to access the specified resource.
     *
     * @param  \App\Models\ServiceRequest  $serviceRequest
     * @return void
     */
    private function checkAccessPermission(ServiceRequest $serviceRequest)
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return;
        }
        
        if ($user->role === 'client' && $user->client->id !== $serviceRequest->client_id) {
            abort(403, 'No tienes permiso para acceder a esta solicitud.');
        }
        
        if ($user->role === 'technician' && 
            ($serviceRequest->technician_id === null || $user->technician->id !== $serviceRequest->technician_id)) {
            abort(403, 'No tienes permiso para acceder a esta solicitud.');
        }
    }
}