@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-800">Edit Draft Invoice</h2>
                <a href="{{ route('invoices.show', $invoice) }}" class="text-gray-600 hover:text-gray-900">Back to
                    Invoice</a>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('invoices.update', $invoice) }}" method="POST"
                class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <!-- Client & Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                            <select name="client_id" id="client_id"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="">Select Client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id', $invoice->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="invoice_date" class="block text-sm font-medium text-gray-700 mb-1">Invoice
                                Date</label>
                            <input type="date" name="invoice_date" id="invoice_date"
                                value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                        </div>
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                            <input type="date" name="due_date" id="due_date"
                                value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Invoice Items</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="items-table">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Description</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase w-24">Qty
                                        </th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase w-32">
                                            Price</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase w-32">
                                            Total</th>
                                        <th class="px-4 py-2 text-white w-10"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200" id="items-body">
                                    <!-- JS will populate this -->
                                </tbody>
                            </table>
                        </div>
                        <button type="button" onclick="addItem()"
                            class="mt-4 text-sm text-indigo-600 hover:text-indigo-900 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Add Item
                        </button>
                    </div>

                    <!-- Totals -->
                    <div class="border-t border-gray-200 pt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea name="notes" id="notes" rows="4"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $invoice->notes) }}</textarea>
                        </div>
                        <div class="space-y-3 bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium" id="display-subtotal">0.00</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 flex items-center">Tax (%) <input type="number" name="tax_rate"
                                        id="tax_rate" value="{{ old('tax_rate', $invoice->tax_rate) }}" min="0" max="100"
                                        class="ml-2 w-16 text-sm border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 p-1"
                                        oninput="calculateTotals()"></span>
                                <span class="font-medium text-red-600" id="display-tax">0.00</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 flex items-center">Discount <input type="number"
                                        name="discount_amount" id="discount_amount"
                                        value="{{ old('discount_amount', $invoice->discount_amount) }}" min="0" step="0.01"
                                        class="ml-2 w-24 text-sm border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 p-1"
                                        oninput="calculateTotals()"></span>
                                <span class="font-medium text-green-600" id="display-discount">-0.00</span>
                            </div>
                            <div class="border-t border-gray-200 pt-3 flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900">Grand Total</span>
                                <span class="text-lg font-bold text-indigo-600" id="display-grand-total">0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg shadow transition duration-150">
                        Update Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>

    <template id="item-row-template">
        <tr>
            <td class="px-4 py-2">
                <input type="text" name="items[INDEX][description]"
                    class="w-full text-sm border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500" required
                    placeholder="Item description">
            </td>
            <td class="px-4 py-2">
                <input type="number" name="items[INDEX][quantity]" value="1" min="1"
                    class="w-full text-sm border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 qty-input"
                    oninput="calculateRow(this)">
            </td>
            <td class="px-4 py-2">
                <input type="number" name="items[INDEX][unit_price]" value="0" min="0" step="0.01"
                    class="w-full text-sm border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 price-input"
                    oninput="calculateRow(this)">
            </td>
            <td class="px-4 py-2 text-right font-medium text-gray-700 row-total">
                0.00
            </td>
            <td class="px-4 py-2 text-center">
                <button type="button" onclick="removeItem(this)" class="text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                </button>
            </td>
        </tr>
    </template>

    <script>
        let itemIndex = 0;

        function addItem(description = '', quantity = 1, price = 0) {
            const template = document.getElementById('item-row-template').innerHTML;
            const html = template.replace(/INDEX/g, itemIndex++);
            document.getElementById('items-body').insertAdjacentHTML('beforeend', html);

            // Populate if data exists
            if (description || price) {
                const rows = document.getElementById('items-body').querySelectorAll('tr');
                const lastRow = rows[rows.length - 1];
                lastRow.querySelector('input[name*="[description]"]').value = description;
                lastRow.querySelector('input[name*="[quantity]"]').value = quantity;
                lastRow.querySelector('input[name*="[unit_price]"]').value = price;
                // trigger calculation
                calculateRow(lastRow.querySelector('.qty-input'));
            }
        }

        function removeItem(btn) {
            const row = btn.closest('tr');
            if (document.querySelectorAll('#items-body tr').length > 1) {
                row.remove();
                calculateTotals();
            } else {
                alert("Invoice must contain at least one item.");
            }
        }

        function calculateRow(input) {
            const row = input.closest('tr');
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const total = qty * price;
            row.querySelector('.row-total').textContent = total.toFixed(2);
            calculateTotals();
        }

        function calculateTotals() {
            let subtotal = 0;
            document.querySelectorAll('#items-body tr').forEach(row => {
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                subtotal += qty * price;
            });

            const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
            const discount = parseFloat(document.getElementById('discount_amount').value) || 0;

            const taxAmount = subtotal * (taxRate / 100);
            const grandTotal = subtotal + taxAmount - discount;

            document.getElementById('display-subtotal').textContent = subtotal.toFixed(2);
            document.getElementById('display-tax').textContent = taxAmount.toFixed(2);
            document.getElementById('display-discount').textContent = '-' + discount.toFixed(2);
            document.getElementById('display-grand-total').textContent = grandTotal.toFixed(2);
        }

        // Initialize with existing items
        document.addEventListener('DOMContentLoaded', () => {
            const existingItems = @json($invoice->items);
            if (existingItems.length > 0) {
                existingItems.forEach(item => {
                    addItem(item.description, item.quantity, item.unit_price);
                });
            } else {
                addItem();
            }
        });
    </script>
@endsection