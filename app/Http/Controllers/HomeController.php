<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\Review;
use App\Models\Service;
use App\Services\BookingScheduleService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected BookingScheduleService $scheduleService;

    public function __construct(BookingScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function index()
    {
        $services = Service::active()->get();
        $barbers = Barber::active()->get();
        $reviews = Review::with('barber')->where('is_approved', true)->latest()->take(3)->get();

        return view('home', compact('services', 'barbers', 'reviews'));
    }

    public function map()
    {
        return view('map');
    }

    public function about()
    {
        return view('about');
    }
}
