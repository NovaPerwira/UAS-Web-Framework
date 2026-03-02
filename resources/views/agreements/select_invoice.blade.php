@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900">Select Invoice</h1>
                <p class="text-gray-600 mt-2">Choose an invoice to generate a new service agreement.</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-8">
                    <form action="{{ route('agreements.create') }}" method="GET">
                        <div class="mb-6">
                            <label for="invoice_id" class="block text-sm font-medium text-gray-700 mb-2">Available
                                Invoices</label>
                            <select name="invoice_id" id="invoice_id" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Select an Invoice --</option>
                                @foreach($invoices as $invoice)
                                    <option value="{{ $invoice->id }}">
                                        {{ $invoice->invoice_number }} - {{ $invoice->client->name }}
                                        (Rp {{ number_format($invoice->grand_total, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                            @if($invoices->isEmpty())
                                <p class="text-sm text-red-500 mt-2">No invoices available. All invoices already have
                                    agreements, or no invoices exist.</p>
                            @endif
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('agreements.index') }}"
                                class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">Cancel</a>
                            <button type="submit" @if($invoices->isEmpty()) disabled @endif
                                class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50">
                                Continue
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection