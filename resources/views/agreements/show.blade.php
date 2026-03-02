@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-5xl mx-auto space-y-6">

            {{-- Header --}}
            <div class="flex justify-between items-start">
                <div>
                    <a href="{{ route('agreements.index') }}"
                        class="text-sm text-gray-500 hover:text-indigo-600 flex items-center gap-1 mb-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Agreements
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $agreement->agreement_number }}</h1>
                    <p class="text-gray-500 mt-1">{{ $agreement->client_name }} · {{ $agreement->project_name }}</p>
                </div>

                {{-- Status Badge + Actions --}}
                <div class="flex items-center gap-3 flex-wrap justify-end">
                    @php
                        $statusClasses = [
                            'draft' => 'bg-gray-100 text-gray-700',
                            'issued' => 'bg-blue-100 text-blue-800',
                            'signed' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ];
                        $class = $statusClasses[$agreement->status] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <span class="px-3 py-1.5 text-sm font-semibold rounded-full {{ $class }}">
                        {{ ucfirst($agreement->status) }}
                    </span>

                    <a href="{{ route('agreements.pdf', $agreement) }}"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        PDF
                    </a>

                    @if ($agreement->canEdit())
                        <a href="{{ route('agreements.edit', $agreement) }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm">
                            Edit
                        </a>
                    @endif

                    <form action="{{ route('agreements.destroy', $agreement) }}" method="POST" class="inline"
                        onsubmit="return confirm('Delete this agreement?\n\nThis cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium text-sm">
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">{{ session('error') }}</div>
            @endif
            @if (session('info'))
                <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded shadow-sm">{{ session('info') }}
                </div>
            @endif

            {{-- Status Transition Buttons --}}
            @php
                $allowedNext = $agreement->allowedNextStatuses();
            @endphp
            @if (count($allowedNext) > 0)
                <div class="bg-white rounded-xl shadow border border-gray-100 p-5">
                    <h2 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Status Transitions</h2>
                    <div class="flex items-center gap-3 flex-wrap">
                        @if (in_array('issued', $allowedNext))
                            <form action="{{ route('agreements.transition', $agreement) }}" method="POST"
                                onsubmit="return confirm('Issue this agreement? The document body will be frozen as a legal snapshot.');">
                                @csrf
                                <input type="hidden" name="status" value="issued">
                                <button type="submit"
                                    class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm">
                                    📋 Issue Agreement
                                </button>
                            </form>
                        @endif
                        @if (in_array('signed', $allowedNext))
                            <form action="{{ route('agreements.transition', $agreement) }}" method="POST"
                                onsubmit="return confirm('Sign this agreement? This will permanently lock the document and allow invoice creation.');">
                                @csrf
                                <input type="hidden" name="status" value="signed">
                                <button type="submit"
                                    class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium text-sm">
                                    ✅ Sign Agreement
                                </button>
                            </form>
                        @endif
                        @if (in_array('cancelled', $allowedNext))
                            <form action="{{ route('agreements.transition', $agreement) }}" method="POST"
                                onsubmit="return confirm('Cancel this agreement? No further invoices can be created.');">
                                @csrf
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit"
                                    class="px-5 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 font-medium text-sm">
                                    Cancel Agreement
                                </button>
                            </form>
                        @endif
                    </div>
                    @if (in_array('issued', $allowedNext))
                        <p class="text-xs text-gray-400 mt-2">⚠ Issuing will freeze the agreement document body permanently.</p>
                    @endif
                </div>
            @endif

            {{-- Agreement Summary --}}
            <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-900">Agreement Summary</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Provider</p>
                            <p class="text-sm font-medium text-gray-900">{{ $agreement->provider_name }}</p>
                            <p class="text-sm text-gray-500">{{ $agreement->provider_email }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Client</p>
                            <p class="text-sm font-medium text-gray-900">{{ $agreement->client_name }}</p>
                            <p class="text-sm text-gray-500">{{ $agreement->client_email }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Project</p>
                            <p class="text-sm font-medium text-gray-900">{{ $agreement->project_name }}</p>
                            <p class="text-sm text-gray-500">{{ $agreement->service_description }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Agreement Date</p>
                            <p class="text-sm text-gray-900">{{ $agreement->agreement_date->format('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Timeline</p>
                            <p class="text-sm text-gray-900">{{ $agreement->start_date->format('d M Y') }} →
                                {{ $agreement->estimated_completion_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Agreement Value</p>
                            <p class="text-xl font-bold text-indigo-700">Rp
                                {{ number_format($agreement->total_value, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Payment Terms</p>
                            <p class="text-sm text-gray-900">{{ $agreement->payment_terms }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Billed / Remaining</p>
                            <p class="text-sm text-gray-900">
                                <span class="text-gray-600">Rp
                                    {{ number_format($agreement->totalInvoiced(), 0, ',', '.') }}</span>
                                <span class="text-gray-400 mx-1">/</span>
                                <span
                                    class="{{ $agreement->remainingValue() > 0 ? 'text-green-600 font-semibold' : 'text-gray-500' }}">
                                    Rp {{ number_format($agreement->remainingValue(), 0, ',', '.') }} remaining
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="px-6 pb-6">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Scope of Work</p>
                    <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700 whitespace-pre-line">
                        {{ $agreement->scope_of_work }}</div>
                </div>
            </div>

            {{-- Related Invoices --}}
            <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h2 class="font-semibold text-gray-900">Related Invoices</h2>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $agreement->invoices->count() }} invoice(s) issued under
                            this agreement</p>
                    </div>
                    @if ($agreement->canCreateInvoice())
                        <a href="{{ route('agreements.invoices.create', $agreement) }}"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium text-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create Invoice
                        </a>
                    @elseif ($agreement->status === 'draft' || $agreement->status === 'issued')
                        <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg font-medium text-sm cursor-not-allowed"
                            title="Agreement must be signed to create invoices">
                            Create Invoice (requires Signed status)
                        </span>
                    @endif
                </div>

                @if ($agreement->invoices->isEmpty())
                    <div class="px-6 py-10 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="font-medium text-gray-500">No invoices yet</p>
                        @if ($agreement->canCreateInvoice())
                            <p class="text-sm mt-1">Use the "Create Invoice" button to add one.</p>
                        @else
                            <p class="text-sm mt-1">Sign this agreement to unlock invoice creation.</p>
                        @endif
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Invoice #</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Due</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($agreement->invoices as $invoice)
                                @php
                                    $invStatusClasses = [
                                        'draft' => 'bg-gray-100 text-gray-700',
                                        'unpaid' => 'bg-yellow-100 text-yellow-800',
                                        'paid' => 'bg-green-100 text-green-800',
                                        'overdue' => 'bg-red-100 text-red-700',
                                        'cancelled' => 'bg-gray-200 text-gray-600',
                                    ];
                                    $iClass = $invStatusClasses[$invoice->status] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $invoice->invoice_number ?? '(draft)' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $invoice->invoice_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $invoice->due_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full {{ $iClass }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 text-right">
                                        Rp {{ number_format($invoice->amount_due ?? $invoice->grand_total, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('invoices.show', $invoice) }}"
                                            class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
@endsection