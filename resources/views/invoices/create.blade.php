@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8">
                <a href="{{ route('agreements.show', $agreement) }}"
                    class="text-sm text-gray-500 hover:text-indigo-600 flex items-center gap-1 mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to {{ $agreement->agreement_number }}
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Create Invoice</h1>
                <p class="text-gray-500 mt-1">Under Agreement <strong>{{ $agreement->agreement_number }}</strong> ·
                    {{ $agreement->client_name }}</p>
            </div>

            {{-- Agreement Context Banner --}}
            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mb-6 flex items-start gap-4">
                <svg class="w-6 h-6 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <div class="flex-1 text-sm">
                    <p class="font-semibold text-indigo-900">Agreement Context (Read-Only)</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-2 text-indigo-800">
                        <div>
                            <p class="text-xs text-indigo-400 font-semibold uppercase">Client</p>
                            <p>{{ $agreement->client_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-indigo-400 font-semibold uppercase">Project</p>
                            <p>{{ $agreement->project_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-indigo-400 font-semibold uppercase">Agreement Value</p>
                            <p class="font-semibold">Rp {{ number_format($agreement->total_value, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-indigo-400 font-semibold uppercase">Remaining</p>
                            <p
                                class="font-semibold {{ $agreement->remainingValue() > 0 ? 'text-green-700' : 'text-red-600' }}">
                                Rp {{ number_format($agreement->remainingValue(), 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('agreements.invoices.store', $agreement) }}" method="POST"
                class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                @csrf

                <div class="p-8 space-y-8">

                    {{-- Dates --}}
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Invoice Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Invoice Date <span
                                        class="text-red-500">*</span></label>
                                <input type="date" name="invoice_date" required
                                    value="{{ old('invoice_date', date('Y-m-d')) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Due Date <span
                                        class="text-red-500">*</span></label>
                                <input type="date" name="due_date" required
                                    value="{{ old('due_date', date('Y-m-d', strtotime('+14 days'))) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Reference</label>
                                <input type="text" name="payment_reference" value="{{ old('payment_reference') }}"
                                    placeholder="e.g. DP 50%, Termin 1"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Notes</label>
                                <input type="text" name="notes" value="{{ old('notes') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    {{-- Line Items --}}
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Line Items</h3>
                        <div id="items-container" class="space-y-3">
                            <div class="item-row grid grid-cols-12 gap-3 items-end">
                                <div class="col-span-5">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                                    <input type="text" name="items[0][description]" required
                                        placeholder="Service description"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Qty</label>
                                    <input type="number" name="items[0][quantity]" required min="1" value="1"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm item-qty">
                                </div>
                                <div class="col-span-4">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Unit Price (Rp)</label>
                                    <input type="number" name="items[0][unit_price]" required min="0" step="1" value="0"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm item-price">
                                </div>
                                <div class="col-span-1 flex justify-end">
                                    <button type="button" onclick="removeItem(this)"
                                        class="text-red-400 hover:text-red-600 pb-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="addItem()"
                            class="mt-3 px-4 py-2 border border-dashed border-indigo-300 text-indigo-600 rounded-lg hover:bg-indigo-50 text-sm font-medium w-full">
                            + Add Line Item
                        </button>
                    </div>

                    {{-- Taxes & Discounts --}}
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Adjustments</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tax Rate (%)</label>
                                <input type="number" step="0.01" name="tax_rate" min="0" max="100"
                                    value="{{ old('tax_rate', 0) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Discount (Rp)</label>
                                <input type="number" step="1" name="discount_amount" min="0"
                                    value="{{ old('discount_amount', 0) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="bg-gray-50 px-8 py-5 flex items-center justify-between border-t border-gray-200">
                    <p class="text-xs text-gray-400">Invoice will be issued immediately and linked to agreement
                        {{ $agreement->agreement_number }}.</p>
                    <div class="flex gap-3">
                        <a href="{{ route('agreements.show', $agreement) }}"
                            class="text-sm font-medium text-gray-600 hover:text-gray-900 px-4 py-2">Cancel</a>
                        <button type="submit"
                            class="px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 shadow-sm">
                            Create Invoice
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let itemIndex = 1;

        function addItem() {
            const container = document.getElementById('items-container');
            const row = document.createElement('div');
            row.className = 'item-row grid grid-cols-12 gap-3 items-end';
            row.innerHTML = `
                <div class="col-span-5">
                    <input type="text" name="items[${itemIndex}][description]" required placeholder="Service description"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div class="col-span-2">
                    <input type="number" name="items[${itemIndex}][quantity]" required min="1" value="1"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm item-qty">
                </div>
                <div class="col-span-4">
                    <input type="number" name="items[${itemIndex}][unit_price]" required min="0" step="1" value="0"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm item-price">
                </div>
                <div class="col-span-1 flex justify-end">
                    <button type="button" onclick="removeItem(this)" class="text-red-400 hover:text-red-600 pb-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>`;
            container.appendChild(row);
            itemIndex++;
        }

        function removeItem(btn) {
            const rows = document.querySelectorAll('.item-row');
            if (rows.length > 1) {
                btn.closest('.item-row').remove();
            }
        }
    </script>
@endpush