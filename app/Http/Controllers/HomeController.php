<?php

namespace App\Http\Controllers;

use App\Models\FootballField;
use App\Models\TimeSlot;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredFields = FootballField::query()
            ->active()
            ->with('mainImage')
            ->latest()
            ->limit(3)
            ->get();

        $timeSlots = TimeSlot::query()->active()->orderBy('start_time')->limit(8)->get();

        return view('home', compact('featuredFields', 'timeSlots'));
    }
}
