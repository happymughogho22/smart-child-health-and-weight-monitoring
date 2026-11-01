<?php

namespace App\Http\Controllers;

use App\Models\Child;

class DashboardController extends Controller
{
    public function index()
    {
        $totalChildren = Child::count();

        $maleChildren = Child::where('sex', 'Male')->count();

        $femaleChildren = Child::where('sex', 'Female')->count();

        $recentChildren = Child::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalChildren',
            'maleChildren',
            'femaleChildren',
            'recentChildren'
        ));
    }
}