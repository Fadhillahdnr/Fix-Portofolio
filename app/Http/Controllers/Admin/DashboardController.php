<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\Service;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', ['stats' => ['Proyek' => Project::count(), 'Draft' => Project::where('status', 'draft')->count(), 'Pesan baru' => ContactMessage::where('status', 'new')->count(), 'Layanan' => Service::where('is_active', true)->count()], 'recent' => ContactMessage::latest()->take(5)->get()]);
    }
}
