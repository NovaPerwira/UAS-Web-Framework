@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Invoices</h1>
                <p class="text-sm text-gray-500 mt-1">Financial transactions linked to signed agreements.</p>
            </div>
            <a href="{{ route('agreements.index') }}"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm font-medium text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Go to Agreements
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">{{ session('success') }}</div>
        @endif
        @if (session('info'))
            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded">{{ session('info') }}</div>
        @endif

        <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Invoice #</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Agreement #</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Client</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Due Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($invoices as $invoice)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                    {{ $invoice->invoice_number ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-indigo-700 font-medium">
                                    @if ($invoice->agreement)
                                        <a href="{{ route('agreements.show', $invoice->agreement) }}" class="hover:underline">
                                            {{ $invoice->agreement->agreement_number }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $invoice->agreement?->client_name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $invoice->due_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClasses = [
                                            'draft' => 'bg-gray-100 text-gray-700',
                                            'unpaid' => 'bg-yellow-100 text-yellow-800',
                                            'paid' => 'bg-green-100 text-green-800',
                                            'overdue' => 'bg-red-100 text-red-700',
                                            'cancelled' => 'bg-gray-200 text-gray-600',
                                        ];
                                        $sClass = $statusClasses[$invoice->status] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $sClass }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 text-right">
                                    Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <a href="{{ route('invoices.show', $invoice) }}"
                                        class="text-indigo-600 hover:text-indigo-900 font-medium">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="font-medium text-gray-500">No invoices yet</p>
                                    <p class="text-sm mt-1">Create a signed Agreement first, then use "Create Invoice" from the
                                        agreement detail page.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($invoices->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">{{ $invoices->links() }}</div>
            @endif
        </div>
    </div>
@endsection