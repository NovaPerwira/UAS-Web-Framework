<?php

namespace App\Http\Controllers; // <--- BARIS INI WAJIB ADA DAN BENAR

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Client;
use App\Models\Freelancer;

class DashboardController extends Controller // <--- NAMA CLASS HARUS SAMA DENGAN NAMA FILE
{
    /**
     * Menampilkan halaman dashboard dengan data statistik.
     */
    public function index()
    {
        // ==========================
        // 1. MENGHITUNG STATISTIK (Cards)
        // ==========================

        // Card 1: Project yang sedang berjalan (Ongoing)
        $ongoingProjectsCount = Project::where('status', 'ongoing')->count();
        $totalProjects = Project::count();

        // Card 2: Total Client
        $totalClients = Client::count();
        // Hitung client baru bulan ini
        $newClientsThisMonth = Client::whereMonth('created_at', date('m'))->count();

        // Card 3: Total Freelancer
        $totalFreelancers = Freelancer::count();

        // Card 4: Estimasi Revenue (Total Budget dari project ongoing & completed)
        $totalRevenue = Project::whereIn('status', ['ongoing', 'completed'])->sum('budget');


        // Menyusun array $stats agar sesuai dengan format looping di View (Blade)
        $stats = [
            [
                'title' => 'Ongoing Projects',
                'value' => $ongoingProjectsCount,
                'change' => "From $totalProjects total projects",
                'icon' => 'blue'
            ],
            [
                'title' => 'Total Clients',
                'value' => $totalClients,
                'change' => "+$newClientsThisMonth new this month",
                'icon' => 'indigo'
            ],
            [
                'title' => 'Freelancers',
                'value' => $totalFreelancers,
                'change' => 'Active Team Members',
                'icon' => 'green'
            ],
            [
                'title' => 'Project Value',
                'value' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
                'change' => 'Ongoing & Completed',
                'icon' => 'yellow'
            ],
        ];

        // ==========================
        // 2. MENGAMBIL DATA TABEL (Recent Projects)
        // ==========================

        // Ambil 5 project terakhir
        $projects = Project::with('client')
            ->latest()
            ->take(5)
            ->get();

        // ==========================
        // 3. MENGAMBIL DATA SIDEBAR (Top Freelancers)
        // ==========================

        // Ambil 5 freelancer terbaru
        $freelancers = Freelancer::latest()
            ->take(5)
            ->get();

        // ==========================
        // 4. KIRIM SEMUA KE VIEW
        // ==========================
        return view('dashboard', compact('stats', 'projects', 'freelancers', 'totalRevenue'));
    }
}