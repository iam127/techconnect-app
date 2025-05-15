<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class ServiceBrowseController extends Controller
{
    public function index()
    {
        $services = Service::latest()->paginate(10); 
        return view('services.browse', compact('services'));
    }
}
