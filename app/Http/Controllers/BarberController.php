<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use Illuminate\Http\Request;

class BarberController extends Controller
{
    public function index()
    {
        $barbers = Barber::active()->withCount('reviews')->get();
        return view('barbers.index', compact('barbers'));
    }
}
