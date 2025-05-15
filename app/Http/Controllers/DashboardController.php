<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceRequest;
use App\Models\Service;
use App\Models\Offer;
use App\Models\Appointment;
use App\Models\Activity;
use App\Models\Rating;

class DashboardController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->role == 'client') {
            $data['activeRequests'] = ServiceRequest::where('client_id', $user->id)
                ->whereIn('status', ['pending', 'in_progress'])
                ->count();

            $data['completedServices'] = ServiceRequest::where('client_id', $user->id)
                ->where('status', 'completed')
                ->count();

            $data['upcomingAppointments'] = Appointment::where('client_id', $user->id)
                ->where('date', '>=', now()->format('Y-m-d'))
                ->count();

            $data['recentActivities'] = Activity::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            $data['appointments'] = Appointment::where('client_id', $user->id)
                ->where('date', '>=', now()->format('Y-m-d'))
                ->orderBy('date')
                ->orderBy('time')
                ->take(3)
                ->get();
        } else {
            $data['activeServices'] = Service::where('technician_id', $user->id)
                ->whereHas('request', function ($query) {
                    $query->whereIn('status', ['in_progress']);
                })
                ->count();

            $data['sentOffers'] = Offer::where('technician_id', $user->id)
                ->count();

            $data['averageRating'] = Rating::where('technician_id', $user->id)
                ->avg('rating') ?? 0;

            $data['recentActivities'] = Activity::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            $data['appointments'] = Appointment::where('technician_id', $user->id)
                ->where('date', '>=', now()->format('Y-m-d'))
                ->orderBy('date')
                ->orderBy('time')
                ->take(3)
                ->get();
        }

        return view('requests.dashboard', $data);

    }
}