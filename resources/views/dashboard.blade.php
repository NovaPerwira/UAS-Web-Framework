@extends('layouts.app')

@section('content')
    {{-- Simulasi Data dari Controller --}}
    @php
        $stats = [
            ['title' => 'Active Projects', 'value' => '12', 'change' => '+2 this month', 'icon' => 'blue'],
            ['title' => 'Total Clients', 'value' => '24', 'change' => 'New client today', 'icon' => 'indigo'],
            ['title' => 'Freelancers', 'value' => '8', 'change' => 'All active', 'icon' => 'green'],
            ['title' => 'Pending Revenue', 'value' => '$4,200', 'change' => 'Invoices sent', 'icon' => 'yellow'],
        ];

        $projects = [
            (object)['name' => 'E-Commerce Redesign', 'client' => 'PT Maju Mundur', 'status' => 'In Progress', 'progress' => 75, 'team' => ['A', 'B']],
            (object)['name' => 'Company Profile AI', 'client' => 'Tech Corp', 'status' => 'Pending', 'progress' => 10, 'team' => ['C']],
            (object)['name' => 'Dashboard SaaS', 'client' => 'StartUp Kita', 'status' => 'Completed', 'progress' => 100, 'team' => ['A', 'C']],
            (object)['name' => 'Landing Page Campaign', 'client' => 'Fashion Store', 'status' => 'In Progress', 'progress' => 45, 'team' => ['B']],
        ];
    @endphp

    <div class="container mx-auto">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h3 class="text-3xl font-bold text-gray-800">Dashboard Overview</h3>
                <p class="text-gray-500 mt-1">Welcome back, here's what's happening with your projects.</p>
            </div>
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Project
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @foreach($stats as $stat)
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-{{ $stat['icon'] }}-100 text-{{ $stat['icon'] }}-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">{{ $stat['title'] }}</p>
                        <h4 class="text-2xl font-bold text-gray-800">{{ $stat['value'] }}</h4>
                        <span class="text-xs text-{{ $stat['icon'] }}-600 font-medium">{{ $stat['change'] }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h4 class="text-lg font-bold text-gray-800">Recent Projects</h4>
                        <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                                    <th class="px-6 py-3 font-semibold">Project Name</th>
                                    <th class="px-6 py-3 font-semibold">Client</th>
                                    <th class="px-6 py-3 font-semibold">Progress</th>
                                    <th class="px-6 py-3 font-semibold">Status</th>
                                    <th class="px-6 py-3 font-semibold">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($projects as $project)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-800">{{ $project->name }}</div>
                                        <div class="text-xs text-gray-500">ID: #PROJ-{{ rand(100,999) }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $project->client }}</td>
                                    <td class="px-6 py-4 w-1/4">
                                        <div class="flex items-center">
                                            <span class="text-xs font-semibold mr-2">{{ $project->progress }}%</span>
                                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $project->progress }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($project->status == 'Completed')
                                            <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Completed</span>
                                        @elseif($project->status == 'In Progress')
                                            <span class="px-2 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">In Progress</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <button class="text-gray-400 hover:text-indigo-600 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-lg font-bold text-gray-800 mb-4">Top Freelancers</h4>
                    <div class="space-y-4">
                        @php $freelancers = ['Sarah Design', 'John Dev', 'Mike DevOps']; @endphp
                        @foreach($freelancers as $freelancer)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr($freelancer, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $freelancer }}</p>
                                    <p class="text-xs text-gray-500">Available</p>
                                </div>
                            </div>
                            <button class="text-xs text-indigo-600 hover:underline">Assign</button>
                        </div>
                        @endforeach
                    </div>
                    <button class="w-full mt-6 py-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                        Manage Team
                    </button>
                </div>

                <div class="bg-indigo-900 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <h4 class="text-lg font-bold mb-1">Income Report</h4>
                        <h2 class="text-3xl font-bold mb-4">$12,450</h2>
                        <p class="text-sm text-indigo-200 mb-4">Total revenue this quarter. You are 15% above target.</p>
                        <button class="bg-white text-indigo-900 text-sm font-bold px-4 py-2 rounded-lg">View Details</button>
                    </div>
                    <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white opacity-10 rounded-full"></div>
                </div>

            </div>
        </div>
    </div>
@endsection