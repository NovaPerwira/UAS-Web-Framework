@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex flex-col md:flex-row justify-between items-end gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Master Data & Relationships</h1>
            <p class="text-gray-500">Overview of all Clients, Projects, Freelancers, Contracts, and Invoices.</p>
        </div>
        
        <!-- Search Form -->
        <form action="{{ route('relations.index') }}" method="GET" class="w-full md:w-1/3">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                    placeholder="Search Client, Project or Email..."
                    onchange="this.form.submit()">
            </div>
        </form>
    </div>

    @forelse($clients as $client)
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 mb-10 overflow-hidden">
            <!-- Client Header -->
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xl border border-indigo-200">
                        {{ substr($client->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">
                            <a href="{{ route('clients.show', $client) }}" class="hover:text-indigo-600 hover:underline">
                                {{ $client->name }}
                            </a>
                        </h2>
                        <div class="flex gap-4 text-sm text-gray-500">
                            <span class="flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> {{ $client->email }}</span>
                            <span class="font-medium text-indigo-600">{{ $client->projects->count() }} Projects</span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                   <!-- <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-bold">Active</span> -->
                </div>
            </div>

            <!-- Content Area -->
            <div class="p-6">
                
                @if($client->projects->count() > 0)
                    <div class="space-y-8">
                        @foreach($client->projects as $project)
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <!-- Project Header -->
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex flex-wrap justify-between items-center gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-white p-1.5 rounded border border-gray-200 shadow-sm">
                                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-800 text-lg">
                                                <a href="{{ route('projects.show', $project) }}" class="hover:text-blue-600 hover:underline">{{ $project->project_name }}</a>
                                            </h3>
                                            @if($project->freelancer)
                                                <p class="text-xs text-gray-500 flex items-center mt-0.5">
                                                    <span class="text-gray-400 mr-1">Freelancer:</span> 
                                                    <span class="font-medium text-gray-700 bg-gray-200 px-1.5 py-0.5 rounded">{{ $project->freelancer->name }}</span>
                                                </p>
                                            @else
                                                <p class="text-xs text-red-400 italic mt-0.5">No Freelancer Assigned</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm">
                                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $project->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($project->status) }}
                                        </span>
                                        <span class="font-mono font-medium text-gray-600">Budget: ${{ number_format($project->budget) }}</span>
                                    </div>
                                </div>

                                <!-- Relations Grid -->
                                <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-gray-200">
                                    
                                    <!-- Contracts -->
                                    <div class="p-4">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 flex items-center justify-between">
                                            <span>Contracts ({{ $project->contracts->count() }})</span>
                                            <a href="{{ route('contracts.create', ['client_id' => $client->id, 'project_id' => $project->id]) }}" class="text-blue-500 hover:text-blue-700 text-xs">+ Add</a>
                                        </h4>
                                        @if($project->contracts->count() > 0)
                                            <ul class="space-y-2">
                                                @foreach($project->contracts as $contract)
                                                    <li class="flex items-start justify-between bg-gray-50 p-2 rounded hover:bg-gray-100 transition text-sm">
                                                        <div class="truncate pr-2">
                                                            <a href="{{ route('contracts.show', $contract) }}" class="font-medium text-indigo-600 hover:underline block truncate">{{ $contract->title }}</a>
                                                            <span class="text-xs text-gray-400">{{ $contract->contract_number }}</span>
                                                        </div>
                                                        <span class="text-xs px-1.5 py-0.5 rounded bg-white border border-gray-200 text-gray-600 whitespace-nowrap">{{ ucfirst($contract->status) }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-sm text-gray-400 italic py-2">No contracts linked.</p>
                                        @endif
                                    </div>

                                    <!-- Invoices -->
                                    <div class="p-4">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 flex items-center justify-between">
                                            <span>Invoices ({{ $project->invoices->count() }})</span>
                                            <a href="{{ route('invoices.create', ['client_id' => $client->id, 'project_id' => $project->id]) }}" class="text-blue-500 hover:text-blue-700 text-xs">+ Add</a>
                                        </h4>
                                        @if($project->invoices->count() > 0)
                                            <ul class="space-y-2">
                                                @foreach($project->invoices as $invoice)
                                                    <li class="flex items-start justify-between bg-gray-50 p-2 rounded hover:bg-gray-100 transition text-sm">
                                                        <div class="truncate pr-2">
                                                            <a href="{{ route('invoices.show', $invoice) }}" class="font-medium text-indigo-600 hover:underline block truncate">{{ $invoice->invoice_number ?? 'Draft' }}</a>
                                                            <span class="text-xs text-gray-400 block">{{ $invoice->due_date->format('M d') }}</span>
                                                        </div>
                                                        <div class="text-right whitespace-nowrap">
                                                            <span class="block font-bold text-gray-700 text-xs">${{ number_format($invoice->grand_total) }}</span>
                                                            <span class="text-xs px-1.5 py-0.5 rounded {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($invoice->status) }}</span>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-sm text-gray-400 italic py-2">No invoices linked.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6 border-2 border-dashed border-gray-200 rounded-lg">
                        <p class="text-gray-400 mb-2">No projects for this client yet.</p>
                        <a href="{{ route('projects.create', ['client_id' => $client->id]) }}" class="text-indigo-600 font-medium hover:underline text-sm">Create First Project</a>
                    </div>
                @endif
                
                {{-- Direct Client Contracts/Invoices (Orphans or General) --}}
                @php 
                    $generalContracts = $client->contracts->where('project_id', null);
                    $generalInvoices = $client->invoices->where('project_id', null);
                @endphp

                @if($generalContracts->count() > 0 || $generalInvoices->count() > 0)
                    <div class="mt-8 border-t border-gray-100 pt-6">
                        <h3 class="font-bold text-gray-700 mb-4 text-sm uppercase tracking-wide">General / Non-Project Items</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($generalContracts->count() > 0)
                            <div class="bg-orange-50 rounded p-4 border border-orange-100">
                                <h4 class="font-bold text-orange-800 text-sm mb-2">General Contracts</h4>
                                <ul class="space-y-1">
                                    @foreach($generalContracts as $contract)
                                        <li class="flex justify-between text-sm">
                                            <a href="{{ route('contracts.show', $contract) }}" class="text-indigo-600 hover:underline">{{ $contract->title }}</a>
                                            <span class="text-gray-500 text-xs">{{ $contract->status }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            @if($generalInvoices->count() > 0)
                            <div class="bg-green-50 rounded p-4 border border-green-100">
                                <h4 class="font-bold text-green-800 text-sm mb-2">General Invoices</h4>
                                <ul class="space-y-1">
                                     @foreach($generalInvoices as $invoice)
                                        <li class="flex justify-between text-sm">
                                            <a href="{{ route('invoices.show', $invoice) }}" class="text-indigo-600 hover:underline">{{ $invoice->invoice_number ?? 'Draft' }}</a>
                                            <span class="text-gray-900 font-medium text-xs">${{ number_format($invoice->grand_total) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl shadow p-12 text-center text-gray-500">
            <h3 class="text-xl font-bold mb-2">No Data Available</h3>
            <p>Start by adding a Client to begin checking relationships.</p>
        </div>
    @endforelse

    <!-- Pagination -->
    <div class="mt-8">
        {{ $clients->links() }}
    </div>
</div>
@endsection
