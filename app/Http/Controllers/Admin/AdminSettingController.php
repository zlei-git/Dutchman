<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        $shop = [
            'name' => 'Dutchman Barbershop',
            'tagline' => 'Reservasi online tanpa antre!',
            'address' => 'Jl. Rungkut Madya No.55A, Rungkut Kidul, Kec. Rungkut, Surabaya, Jawa Timur 60293',
            'phone' => '+62 821-1000-9744',
            'instagram' => '@dutchmanbarbershop',
            'opening_hours' => 'Senin – Jumat: 11:00 – 21:00 WIB, Sabtu – Minggu: 11:00 – 22:00 WIB',
            'slot_interval' => '30 Menit',
        ];

        return view('admin.settings.index', compact('shop'));
    }
}
