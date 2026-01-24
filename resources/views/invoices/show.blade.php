@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <div class="flex items-center space-x-3 mb-1">
                        <h2 class="text-3xl font-bold text-gray-900">
                            {{ $invoice->invoice_number ?? 'DRAFT INVOICE' }}
                        </h2>
                        @php
                            $statusClasses = [
                                'draft' => 'bg-gray-100 text-gray-800',
                                'sent' => 'bg-blue-100 text-blue-800',
                                'unpaid' => 'bg-yellow-100 text-yellow-800',
                                'paid' => 'bg-green-100 text-green-800',
                                'overdue' => 'bg-red-100 text-red-800',
                                'cancelled' => 'bg-gray-300 text-gray-800',
                            ];
                            $class = $statusClasses[$invoice->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $class }}">
                            {{ strtoupper($invoice->status) }}
                        </span>
                    </div>
                    <p class="text-gray-500">Created on {{ $invoice->created_at->format('M d, Y') }}</p>
                </div>

                <div class="flex space-x-3">
                    <a href="{{ route('invoices.index') }}"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 hover:bg-gray-50 font-medium">
                        Back
                    </a>

                    @if($invoice->status === 'draft')
                        <a href="{{ route('invoices.edit', $invoice) }}"
                            class="px-4 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 rounded-lg hover:bg-indigo-100 font-medium">
                            Edit Draft
                        </a>
                        <form action="{{ route('invoices.send', $invoice) }}" method="POST"
                            onsubmit="return confirm('Are you sure? This will lock the invoice and generate a number.');">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow font-medium">
                                Send Invoice
                            </button>
                        </form>
                    @endif

                    @if(in_array($invoice->status, ['sent', 'unpaid', 'overdue']))
                        <button onclick="document.getElementById('payment-modal').classList.remove('hidden')"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 shadow font-medium">
                            Record Payment
                        </button>
                    @endif

                    <button onclick="window.print()"
                        class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 shadow font-medium flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                            </path>
                        </svg>
                        Print / PDF
                    </button>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                    <p class="font-bold">Success</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Invoice Details -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-8" id="invoice-printable">
                <div class="p-8">
                    <!-- Top Info -->
                    <div class="grid grid-cols-2 gap-8 mb-8 border-b border-gray-200 pb-8">
                        <div>
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">To:</h3>
                            <p class="text-lg font-bold text-gray-900">{{ $invoice->client->name }}</p>
                            <p class="text-gray-600">{{ $invoice->client->email }}</p>
                            <!-- Add address if available in client model -->
                        </div>
                        <div class="text-right">
                            <div class="mb-2">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Invoice
                                    Date</span>
                                <span
                                    class="text-gray-900 font-medium">{{ $invoice->invoice_date->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Due
                                    Date</span>
                                <span class="text-red-600 font-medium">{{ $invoice->due_date->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <table class="min-w-full divide-y divide-gray-200 mb-8">
                        <thead>
                            <tr>
                                <th class="text-left py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Description</th>
                                <th class="text-right py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Qty
                                </th>
                                <th class="text-right py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Unit
                                    Price</th>
                                <th class="text-right py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($invoice->items as $item)
                                <tr>
                                    <td class="py-4 text-sm text-gray-900">{{ $item->description }}</td>
                                    <td class="py-4 text-sm text-gray-600 text-right">{{ $item->quantity }}</td>
                                    <td class="py-4 text-sm text-gray-600 text-right">Rp
                                        {{ number_format($item->unit_price, 2) }}</td>
                                    <td class="py-4 text-sm font-medium text-gray-900 text-right">Rp
                                        {{ number_format($item->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Totals -->
                    <div class="flex justify-end">
                        <div class="w-1/2 space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium text-gray-900">Rp {{ number_format($invoice->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax ({{ $invoice->tax_rate }}%)</span>
                                <span class="font-medium text-gray-900">Rp
                                    {{ number_format($invoice->tax_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Discount</span>
                                <span class="font-medium text-green-600">- Rp
                                    {{ number_format($invoice->discount_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-base font-bold border-t border-gray-200 pt-3">
                                <span class="text-gray-900">Grand Total</span>
                                <span class="text-indigo-600">Rp {{ number_format($invoice->grand_total, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-500 pt-1">
                                <span>Amount Paid</span>
                                <span>Rp {{ number_format($invoice->payments->sum('amount'), 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm font-bold pt-1 text-red-600">
                                <span>Balance Due</span>
                                <span>Rp
                                    {{ number_format($invoice->grand_total - $invoice->payments->sum('amount'), 2) }}</span>
                            </div>
                        </div>
                    </div>

                    @if($invoice->notes)
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Notes</h4>
                            <p class="text-sm text-gray-600">{{ $invoice->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payments History -->
            @if($invoice->payments->count() > 0)
                <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800">Payment History</h3>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($invoice->payments as $payment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ ucfirst($payment->method) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $payment->notes }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">Rp
                                        {{ number_format($payment->amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="payment-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900 text-center">Record Payment</h3>
                <div class="mt-2 text-center text-sm text-gray-500 mb-4">
                    Remaining Balance: Rp {{ number_format($invoice->grand_total - $invoice->payments->sum('amount'), 2) }}
                </div>
                <form action="{{ route('invoices.payment', $invoice) }}" method="POST">
                    @csrf
                    <div class="space-y-4 text-left">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Amount</label>
                            <input type="number" name="amount" step="0.01"
                                max="{{ $invoice->grand_total - $invoice->payments->sum('amount') }}"
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
                    <div class="items-center px-4 py-3 mt-4 text-right">
                        <button type="button" onclick="document.getElementById('payment-modal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 mr-2">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Save
                            Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection