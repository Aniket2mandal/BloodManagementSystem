<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Camp;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardFrontController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

    $pastCamps = Camp::with('bloodBanks')
        ->whereDate('date', '<', $today)->where('status', 1)
        ->latest()
        ->get();

        $camps=Camp::with('bloodBanks') ->whereDate('date', '>', $today)->get();
        return view('frontend.dashboard.index',compact('camps', 'pastCamps'));
    }

    public function services()
    {
        return view('frontend.dashboard.service');
    }
}
