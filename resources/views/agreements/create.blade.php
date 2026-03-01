@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Generate Agreement from Invoice</h1>
            <a href="{{ route('agreements.index') }}" class="text-gray-500 hover:text-gray-800 transition">Back to List</a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('agreements.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="invoice_id" class="block text-sm font-medium text-gray-700 mb-2">Select Target
                        Invoice</label>
                    <select name="invoice_id" id="invoice_id"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2.5 bg-gray-50 border"
                        required>
                        <option value="" disabled {{ !$selectedInvoice ? 'selected' : '' }}>-- Choose an Invoice without
                            Agreement --</option>
                        @foreach($invoices as $invoice)
                            <option value="{{ $invoice->id }}" {{ $selectedInvoice == $invoice->id ? 'selected' : '' }}>
                                {{ $invoice->invoice_number }} - {{ $invoice->client->name }} (Rp
                                {{ number_format($invoice->grand_total, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                    @if($invoices->isEmpty())
                        <p class="text-xs text-amber-600 mt-2">No invoices found without an existing agreement.</p>
                    @endif
                </div>

                <div class="mb-8">
                    <label for="template_id" class="block text-sm font-medium text-gray-700 mb-2">Select Agreement
                        Template</label>
                    <select name="template_id" id="template_id"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2.5 bg-gray-50 border"
                        required>
                        <option value="" disabled selected>-- Choose a Template --</option>
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}">{{ $template->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end border-t border-gray-100 pt-4">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-md shadow transition"
                        {{ $invoices->isEmpty() || $templates->isEmpty() ? 'disabled' : '' }}>
                        Generate Draft
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection