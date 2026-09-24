<?php

namespace App\Http\Controllers;

use App\Models\Branch;

class AboutController extends Controller
{
    public function index()
    {
        $branches = Branch::where('status', true)->get();
        return view('about', compact('branches'));
    }
}
