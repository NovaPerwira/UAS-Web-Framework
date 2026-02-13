@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $client->name }}</h1>
                <p class="text-gray-600">{{ $client->email }}</p>
            </div>
            <div class="space-x-2">
                <a href="{{ route('clients.edit', $client) }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                        </path>
                    </svg>
                    <span>Edit</span>
                </a>
                <a href="{{ route('projects.create', ['client_id' => $client->id]) }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>New Project</span>
                </a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-500">
                <h3 class="text-gray-500 text-sm font-medium uppercase">Total Projects</h3>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $client->projects->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <h3 class="text-gray-500 text-sm font-medium uppercase">Active Contracts</h3>
                <p class="text-3xl font-bold text-gray-800 mt-2">
                    {{ $client->contracts->where('status', 'active')->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                <h3 class="text-gray-500 text-sm font-medium uppercase">Outstanding Invoices</h3>
                <p class="text-3xl font-bold text-red-600 mt-2">
                    ${{ number_format($client->invoices->where('status', 'unpaid')->sum('grand_total'), 2) }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <h3 class="text-gray-500 text-sm font-medium uppercase">Total Revenue</h3>
                <p class="text-3xl font-bold text-green-600 mt-2">
                    ${{ number_format($client->invoices->where('status', 'paid')->sum('grand_total'), 2) }}
                </p>
            </div>
        </div>

        <!-- Projects Section -->
        <div class="bg-white rounded-lg shadow mb-8 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    Projects
                </h2>
            </div>
            <div class="p-6">
                @if($client->projects->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($client->projects as $project)
                            <div class="border rounded-lg p-5 hover:shadow-lg transition bg-white group">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-bold text-lg text-gray-800 group-hover:text-indigo-600 transition">
                                        <a href="{{ route('projects.show', $project) }}">{{ $project->project_name }}</a>
                                    </h3>
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full 
                                                {{ $project->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($project->status) }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500 mb-4">
                                    Budget: <span class="font-medium text-gray-700">${{ number_format($project->budget, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm pt-4 border-t border-gray-100">
                                    <span class="text-gray-400">{{ $project->contracts->count() }} Contracts</span>
                                    <a href="{{ route('projects.show', $project) }}"
                                        class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center">
                                        Details <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500 italic mb-4">No projects found for this client.</p>
                        <a href="{{ route('projects.create', ['client_id' => $client->id]) }}"
                            class="text-indigo-600 hover:underline">Create a new project</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Contracts & Invoices (Split View) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Contracts -->
            <div class="bg-white rounded-lg shadow overflow-hidden h-full">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Contracts
                    </h2>
                    <a href="{{ route('contracts.create', ['client_id' => $client->id]) }}"
                        class="text-sm bg-white border border-indigo-600 text-indigo-600 hover:bg-indigo-50 font-medium py-1 px-3 rounded shadow-sm transition">
                        + New Contract
                    </a>
                </div>
                <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                    @forelse($client->contracts as $contract)
                        <div class="p-4 hover:bg-gray-50 transition block">
                            <div class="flex justify-between items-start">
                                <div>
                                    <a href="{{ route('contracts.show', $contract) }}"
                                        class="font-medium text-indigo-600 hover:text-indigo-800">{{ $contract->title }}</a>
                                    <p class="text-xs text-gray-500 font-mono mt-1">{{ $contract->contract_number }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Project:
                                        @if($contract->project)
                                            <a href="{{ route('projects.show', $contract->project) }}"
                                                class="text-blue-500 hover:underline">{{ $contract->project->project_name }}</a>
                                        @else
                                            <span class="text-gray-400">General</span>
                                        @endif
                                    </p>
                                </div>
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $contract->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($contract->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <p>No contracts found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Invoices -->
            <div class="bg-white rounded-lg shadow overflow-hidden h-full">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z">
                            </path>
                        </svg>
                        Invoices
                    </h2>
                    <a href="{{ route('invoices.create', ['client_id' => $client->id]) }}"
                        class="text-sm bg-white border border-indigo-600 text-indigo-600 hover:bg-indigo-50 font-medium py-1 px-3 rounded shadow-sm transition">
                        + New Invoice
                    </a>
                </div>
                <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                    @forelse($client->invoices as $invoice)
                        <div class="p-4 hover:bg-gray-50 transition block">
                            <div class="flex justify-between items-start">
                                <div>
                                    <a href="{{ route('invoices.show', $invoice) }}"
                                        class="font-medium text-indigo-600 hover:text-indigo-800">{{ $invoice->invoice_number ?? 'Draft' }}</a>
                                    <p class="text-xs text-gray-500 mt-1">Due: {{ $invoice->due_date->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Link:
                                        @if($invoice->contract)
                                            <a href="{{ route('contracts.show', $invoice->contract) }}"
                                                class="text-blue-500 hover:underline">Contract #{{ $invoice->contract->id }}</a>
                                        @elseif($invoice->project)
                                            <a href="{{ route('projects.show', $invoice->project) }}"
                                                class="text-blue-500 hover:underline">{{ $invoice->project->project_name }}</a>
                                        @else
                                            <span class="text-gray-400">Direct</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900">${{ number_format($invoice->grand_total, 2) }}</p>
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-800' : ($invoice->status == 'overdue' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <p>No invoices found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection