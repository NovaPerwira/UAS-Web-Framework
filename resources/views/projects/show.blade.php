@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-gray-500 text-sm">Project /</span>
                    <a href="{{ route('clients.show', $project->client) }}"
                        class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">{{ $project->client->name }}</a>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $project->project_name }}</h1>
                <div class="flex items-center gap-4 mt-2">
                    <span
                        class="px-3 py-1 rounded-full text-sm font-semibold 
                        {{ $project->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ ucfirst($project->status) }}
                    </span>
                    <span class="text-gray-600 font-medium">${{ number_format($project->budget, 2) }} Budget</span>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('projects.edit', $project) }}"
                    class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-2 px-4 rounded shadow-sm inline-flex items-center transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                        </path>
                    </svg>
                    Edit Project
                </a>
                <a href="{{ route('contracts.create', ['client_id' => $project->client_id, 'project_id' => $project->id]) }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow-sm inline-flex items-center transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Add Contract
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 border-t-4 border-indigo-500">
                <h3 class="text-gray-500 text-sm font-medium uppercase">Budget Utilization</h3>
                <div class="mt-2 flex items-end gap-2">
                    <span
                        class="text-3xl font-bold text-gray-800">${{ number_format($project->invoices->sum('grand_total'), 2) }}</span>
                    <span class="text-sm text-gray-500 mb-1">/ ${{ number_format($project->budget, 2) }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
                    @php
                        $percentage = $project->budget > 0 ? ($project->invoices->sum('grand_total') / $project->budget) * 100 : 0;
                    @endphp
                    <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-500">
                <h3 class="text-gray-500 text-sm font-medium uppercase">Contracts</h3>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $project->contracts->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-t-4 border-red-500">
                <h3 class="text-gray-500 text-sm font-medium uppercase">Unpaid Invoices</h3>
                <p class="text-3xl font-bold text-red-600 mt-2">
                    ${{ number_format($project->invoices->where('status', 'unpaid')->sum('grand_total'), 2) }}
                </p>
            </div>
        </div>

        <!-- Content Grid -->
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
                    <a href="{{ route('contracts.create', ['client_id' => $project->client_id, 'project_id' => $project->id]) }}"
                        class="text-sm text-indigo-600 hover:text-indigo-800 font-medium bg-white border border-indigo-200 px-3 py-1 rounded shadow-sm hover:bg-indigo-50 transition">+
                        New</a>
                </div>
                <div class="divide-y divide-gray-200 can-scroll max-h-96 overflow-y-auto">
                    @forelse($project->contracts as $contract)
                        <div class="p-4 hover:bg-gray-50 transition block">
                            <div class="flex justify-between items-start">
                                <div>
                                    <a href="{{ route('contracts.show', $contract) }}"
                                        class="font-medium text-indigo-600 hover:text-indigo-800">{{ $contract->title }}</a>
                                    <p class="text-xs text-gray-500 font-mono mt-1">{{ $contract->contract_number }}</p>
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
                            <p>No contracts linked to this project.</p>
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
                    <a href="{{ route('invoices.create', ['project_id' => $project->id, 'client_id' => $project->client_id]) }}"
                        class="text-sm text-indigo-600 hover:text-indigo-800 font-medium bg-white border border-indigo-200 px-3 py-1 rounded shadow-sm hover:bg-indigo-50 transition">+
                        New</a>
                </div>
                <div class="divide-y divide-gray-200 can-scroll max-h-96 overflow-y-auto">
                    @forelse($project->invoices as $invoice)
                        <div class="p-4 hover:bg-gray-50 transition block">
                            <div class="flex justify-between items-start">
                                <div>
                                    <a href="{{ route('invoices.show', $invoice) }}"
                                        class="font-medium text-indigo-600 hover:text-indigo-800">{{ $invoice->invoice_number ?? 'Draft' }}</a>
                                    <p class="text-xs text-gray-500 mt-1">Due: {{ $invoice->due_date->format('M d, Y') }}</p>
                                    @if($invoice->contract)
                                        <p class="text-xs text-gray-400 mt-1">Contract: {{ $invoice->contract->title }}</p>
                                    @endif
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
                            <p>No invoices found for this project.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
@endsection