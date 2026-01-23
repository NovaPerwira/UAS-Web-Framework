@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 space-y-4 md:space-y-0">
            <div>
                <h3 class="text-3xl font-bold text-gray-800">Dashboard</h3>
                <p class="text-gray-500 mt-1">Overview of your agency's performance.</p>
            </div>
            <a href="{{ route('projects.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg shadow-md transition flex items-center font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Project
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @foreach($stats as $stat)
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-{{ $stat['icon'] }}-100 text-{{ $stat['icon'] }}-600 mr-4">
                        @if($stat['icon'] == 'blue') 
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        @elseif($stat['icon'] == 'indigo')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        @elseif($stat['icon'] == 'green')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">{{ $stat['title'] }}</p>
                        <h4 class="text-2xl font-bold text-gray-800 mt-1">{{ $stat['value'] }}</h4>
                        <span class="text-xs text-{{ $stat['icon'] }}-600 font-medium bg-{{ $stat['icon'] }}-50 px-2 py-0.5 rounded-full inline-block mt-1">
                            {{ $stat['change'] }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h4 class="text-lg font-bold text-gray-800">Recent Projects</h4>
                        <a href="{{ route('projects.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium hover:underline">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                    <th class="px-6 py-3 font-semibold">Project Details</th>
                                    <th class="px-6 py-3 font-semibold">Client</th>
                                    <th class="px-6 py-3 font-semibold">Budget</th>
                                    <th class="px-6 py-3 font-semibold">Status</th>
                                    <th class="px-6 py-3 font-semibold text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($projects as $project)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800 text-sm">{{ $project->project_name }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">Created {{ $project->created_at->diffForHumans() }}</div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        @if($project->client)
                                            <div class="text-sm text-gray-700 font-medium">{{ $project->client->name }}</div>
                                        @else
                                            <span class="text-xs text-red-500 italic">No Client</span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-700">
                                        Rp {{ number_format($project->budget, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @php
                                            $badgeClass = match($project->status) {
                                                'completed' => 'bg-green-100 text-green-700 border-green-200',
                                                'ongoing'   => 'bg-blue-100 text-blue-700 border-blue-200',
                                                'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                                                default     => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full border {{ $badgeClass }}">
                                            {{ ucfirst($project->status) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('projects.edit', $project) }}" class="text-gray-400 hover:text-indigo-600 transition p-2 hover:bg-indigo-50 rounded-full inline-block">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500 bg-gray-50/30">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                                            <p>No active projects found.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-5">
                        <h4 class="text-lg font-bold text-gray-800">New Freelancers</h4>
                        <a href="{{ route('freelancers.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-wide">View All</a>
                    </div>
                    
                    <div class="space-y-5">
                        @forelse($freelancers as $freelancer)
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm border border-indigo-100 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-200">
                                    {{ substr($freelancer->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800 group-hover:text-indigo-600 transition">{{ $freelancer->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $freelancer->skill ?? 'Freelancer' }}</p>
                                </div>
                            </div>
                            <button class="text-gray-300 hover:text-indigo-600 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </button>
                        </div>
                        @empty
                            <p class="text-sm text-gray-400 text-center italic">No freelancers added yet.</p>
                        @endforelse
                    </div>

                    <a href="{{ route('freelancers.create') }}" class="mt-6 block w-full py-2.5 bg-gray-50 border border-dashed border-gray-300 text-gray-600 text-sm font-medium rounded-lg hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-300 transition text-center">
                        + Add Freelancer
                    </a>
                </div>

                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-slate-300 uppercase tracking-wider">Total Revenue</h4>
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h2 class="text-3xl font-bold mb-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                        <p class="text-xs text-slate-400">Calculated from ongoing & completed projects.</p>
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-indigo-500 rounded-full opacity-20 blur-xl"></div>
                    <div class="absolute top-0 right-0 w-20 h-20 bg-blue-500 rounded-full opacity-10 blur-lg"></div>
                </div>

            </div>
        </div>
    </div>
@endsection