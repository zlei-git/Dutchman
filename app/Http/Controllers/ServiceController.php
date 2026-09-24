<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::active()->get();
        return view('services.index', compact('services'));
    }

    public function show($slug)
    {
        $service = Service::active()->where('slug', $slug)->firstOrFail();
        $otherServices = Service::active()->where('id', '!=', $service->id)->take(3)->get();

        return view('services.show', compact('service', 'otherServices'));
    }
}
