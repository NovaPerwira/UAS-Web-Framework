@extends('layouts.app')

@section('content')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #invoice-printable,
            #invoice-printable * {
                visibility: visible;
            }

            #invoice-printable {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px !important;
                border: none !important;
                box-shadow: none !important;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>

    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">

            {{-- Header --}}
            <div class="flex justify-between items-start mb-6 no-print">
                <div>
                    <a href="{{ route('invoices.index') }}"
                        class="text-sm text-gray-500 hover:text-indigo-600 flex items-center gap-1 mb-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Invoices
                    </a>
                    <div class="flex items-center gap-3">
                        <h2 class="text-3xl font-bold text-gray-900">{{ $invoice->invoice_number ?? 'Invoice' }}</h2>
                        @php
                            $statusClasses = [
                                'draft' => 'bg-gray-100 text-gray-700',
                                'unpaid' => 'bg-yellow-100 text-yellow-800',
                                'paid' => 'bg-green-100 text-green-800',
                                'overdue' => 'bg-red-100 text-red-700',
                                'cancelled' => 'bg-gray-200 text-gray-600',
                            ];
                            $class = $statusClasses[$invoice->status] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $class }}">
                            {{ strtoupper($invoice->status) }}
                        </span>
                    </div>
                    <p class="text-gray-500 text-sm mt-1">Created {{ $invoice->created_at->format('M d, Y') }}</p>
                </div>
                <div class="flex gap-3 flex-wrap justify-end">
                    @if ($invoice->agreement)
                        <a href="{{ route('agreements.show', $invoice->agreement) }}"
                            class="px-4 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 rounded-lg hover:bg-indigo-100 font-medium text-sm">
                            View Agreement
                        </a>
                    @endif
                    <a href="{{ route('invoices.pdf', $invoice) }}"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download PDF
                    </a>
                    @if (in_array($invoice->status, ['unpaid', 'overdue']))
                        <button onclick="document.getElementById('payment-modal').classList.remove('hidden')"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium text-sm">
                            Record Payment
                        </button>
                    @endif
                </div>
            </div>

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded no-print">
                    {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded no-print">{{ session('error') }}
                </div>
            @endif

            {{-- Agreement Summary (Read-Only) --}}
            @if ($invoice->agreement)
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mb-6 no-print">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-bold text-indigo-400 uppercase tracking-wider">Linked Agreement</p>
                        <span class="text-xs px-2 py-0.5 bg-indigo-100 text-indigo-600 rounded font-semibold">Read-Only</span>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <p class="text-xs text-indigo-400 font-semibold">Agreement #</p>
                            <p class="font-semibold text-indigo-900">{{ $invoice->agreement->agreement_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-indigo-400 font-semibold">Client</p>
                            <p class="text-indigo-900">{{ $invoice->agreement->client_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-indigo-400 font-semibold">Project</p>
                            <p class="text-indigo-900">{{ $invoice->agreement->project_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-indigo-400 font-semibold">Agreement Value</p>
                            <p class="font-semibold text-indigo-900">Rp
                                {{ number_format($invoice->agreement->total_value, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Invoice Document --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6" id="invoice-printable">
                <div class="p-8">
                    <div class="flex justify-between items-start border-b border-gray-200 pb-8 mb-8">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Jasa Digital UMKM</h1>
                            <div class="text-sm text-gray-500 mt-1 space-y-0.5">
                                <p>Jl. Contoh Bisnis No. 123, Tabanan, Bali</p>
                                <p>jasadigitalumkm@gmail.com</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <h2 class="text-5xl font-light text-gray-700 uppercase tracking-wide">Invoice</h2>
                            <p class="text-xl font-semibold text-gray-900 mt-1">{{ $invoice->invoice_number }}</p>
                            @if ($invoice->agreement)
                                <p class="text-xs text-gray-400 mt-1">Ref: {{ $invoice->agreement->agreement_number }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-8 mb-8 pb-8 border-b border-gray-200">
                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Bill To</h3>
                            @if ($invoice->agreement)
                                <p class="text-lg font-bold text-gray-900">{{ $invoice->agreement->client_name }}</p>
                                <p class="text-gray-600 text-sm">{{ $invoice->agreement->client_email }}</p>
                                <p class="text-gray-500 text-sm mt-1">{{ $invoice->agreement->client_address }}</p>
                            @endif
                            @if ($invoice->payment_reference)
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Payment Reference</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $invoice->payment_reference }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="flex justify-end gap-8">
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Date
                                    Issued</span>
                                <span
                                    class="text-gray-900 font-medium">{{ $invoice->invoice_date->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Due
                                    Date</span>
                                <span class="text-red-600 font-semibold">{{ $invoice->due_date->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 mb-8">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">Description</th>
                                <th class="text-right py-3 px-4 text-xs font-bold text-gray-500 uppercase">Qty</th>
                                <th class="text-right py-3 px-4 text-xs font-bold text-gray-500 uppercase">Unit Price</th>
                                <th class="text-right py-3 px-4 text-xs font-bold text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($invoice->items as $item)
                                <tr>
                                    <td class="py-4 px-4 text-sm text-gray-900 font-medium">{{ $item->description }}</td>
                                    <td class="py-4 px-4 text-sm text-gray-600 text-right">{{ $item->quantity }}</td>
                                    <td class="py-4 px-4 text-sm text-gray-600 text-right">Rp
                                        {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="py-4 px-4 text-sm font-bold text-gray-900 text-right">Rp
                                        {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="flex justify-end mb-8">
                        <div class="w-1/2 lg:w-1/3 space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium text-gray-900">Rp
                                    {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                            </div>
                            @if ($invoice->tax_amount > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Tax ({{ $invoice->tax_rate }}%)</span>
                                    <span class="font-medium text-gray-900">Rp
                                        {{ number_format($invoice->tax_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            @if ($invoice->discount_amount > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Discount</span>
                                    <span class="font-medium text-green-600">- Rp
                                        {{ number_format($invoice->discount_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="border-t-2 border-gray-200 pt-3">
                                <div class="flex justify-between items-end">
                                    <span class="text-base font-bold text-gray-900">Grand Total</span>
                                    <span class="text-xl font-bold text-indigo-700">Rp
                                        {{ number_format($invoice->grand_total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="pt-3 border-t border-gray-100 text-xs text-gray-500 space-y-1">
                                <div class="flex justify-between">
                                    <span>Amount Paid</span>
                                    <span>Rp {{ number_format($invoice->payments->sum('amount'), 0, ',', '.') }}</span>
                                </div>
                                @php $balance = $invoice->grand_total - $invoice->payments->sum('amount'); @endphp
                                <div
                                    class="flex justify-between font-bold {{ $balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    <span>Balance Due</span>
                                    <span>Rp {{ number_format($balance, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($invoice->notes)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <h4 class="text-xs font-bold text-gray-500 uppercase mb-1">Notes</h4>
                            <p class="text-sm text-gray-600 italic">{{ $invoice->notes }}</p>
                        </div>
                    @endif

                    <div class="mt-10 text-center text-xs text-gray-400">
                        <p>Thank you for your business!</p>
                    </div>
                </div>
            </div>

            {{-- Payment History --}}
            @if ($invoice->payments->count() > 0)
                <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden no-print">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800">Payment History</h3>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Method</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Notes</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($invoice->payments as $payment)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($payment->method) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $payment->notes }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 text-right">Rp
                                        {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Payment Modal --}}
    <div id="payment-modal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 no-print">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 text-center mb-2">Record Payment</h3>
                <p class="text-center text-sm text-gray-500 mb-4">
                    Balance Due: Rp
                    {{ number_format($invoice->grand_total - $invoice->payments->sum('amount'), 0, ',', '.') }}
                </p>
                <form action="{{ route('invoices.payment', $invoice) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Amount (Rp)</label>
                            <input type="number" name="amount" step="1" required
                                value="{{ $invoice->grand_total - $invoice->payments->sum('amount') }}"
                                class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="payment_date" value="{{ date('Y-m-d') }}"
                                class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Method</label>
                            <select name="method"
                                class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="cash">Cash</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" rows="2"
                                class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>
                    <div class="text-right mt-4 space-x-2">
                        <button type="button" onclick="document.getElementById('payment-modal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Save
                            Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection