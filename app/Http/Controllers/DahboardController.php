<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Client;
use App\Models\Freelancer;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil Data Statistik (Cards)
        $totalProjects = Project::count();
        $activeProjects = Project::where('status', 'In Progress')->count();
        $totalClients = Client::count();
        $activeFreelancers = Freelancer::where('status', 'Active')->count();

        // Kita format array $stats agar strukturnya sama dengan yang dibutuhkan UI
        $stats = [
            [
                'title' => 'Active Projects', 
                'value' => $activeProjects, 
                'change' => "From $totalProjects total", 
                'icon' => 'blue'
            ],
            [
                'title' => 'Total Clients', 
                'value' => $totalClients, 
                'change' => 'Lifetime', 
                'icon' => 'indigo'
            ],
            [
                'title' => 'Freelancers', 
                'value' => $activeFreelancers, 
                'change' => 'Ready to work', 
                'icon' => 'green'
            ],
            [
                'title' => 'Revenue', 
                'value' => 'Rp 120jt', // Bisa diganti dengan sum column revenue project
                'change' => 'This Month', 
                'icon' => 'yellow'
            ],
        ];

        // 2. Ambil Data Project Terbaru untuk Tabel
        // Menggunakan 'with' agar query lebih ringan (Eager Loading) relasi client
        $projects = Project::with('client') 
                    ->latest() // Urutkan dari yang terbaru
                    ->take(5)  // Ambil 5 saja
                    ->get();

        // 3. Ambil Freelancer untuk Sidebar
        $freelancers = Freelancer::where('status', 'Active')
                       ->take(3)
                       ->get();

        // 4. Kirim semua variable ke View
        return view('dashboard', compact('stats', 'projects', 'freelancers'));
    }
}