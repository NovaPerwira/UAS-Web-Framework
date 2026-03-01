@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Draft Agreement: {{ $agreement->agreement_number }}</h1>
            <a href="{{ route('agreements.show', $agreement) }}"
                class="text-gray-500 hover:text-gray-800 transition">Cancel</a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('agreements.update', $agreement) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Client Info Group -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Client Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="client_name" value="{{ old('client_name', $agreement->client_name) }}"
                            required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $agreement->company_name) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Client Email <span
                                class="text-red-500">*</span></label>
                        <input type="email" name="client_email" value="{{ old('client_email', $agreement->client_email) }}"
                            required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contract Value (Total Price) <span
                                class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', (float) $agreement->price) }}"
                            required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Date <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="start_date"
                            value="{{ old('start_date', $agreement->start_date->format('Y-m-d')) }}" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Date <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="end_date"
                            value="{{ old('end_date', $agreement->end_date->format('Y-m-d')) }}" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                    </div>
                </div>

                <div class="mb-6 border-t border-gray-100 pt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Invoiced Items (Summary)</label>
                    <textarea name="service_description" rows="3" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border font-mono text-sm bg-gray-50">{{ old('service_description', $agreement->service_description) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">This is a summary of services pulled from the invoice.</p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contract Body (Scope of Work /
                        Editor)</label>
                    <textarea name="scope_of_work" rows="15" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-4 border font-serif bg-gray-50 leading-relaxed">{{ old('scope_of_work', $agreement->scope_of_work) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Supports basic HTML (since it was generated from the template). Be
                        careful when modifying tags.</p>
                </div>

                <div class="mb-8 border-b border-gray-100 pb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Terms</label>
                    <input type="text" name="payment_terms" value="{{ old('payment_terms', $agreement->payment_terms) }}"
                        required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                </div>

                <div class="flex justify-end gap-3">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-md shadow transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection