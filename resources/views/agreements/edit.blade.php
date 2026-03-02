@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Edit Service Agreement</h1>
                <p class="text-gray-600 mt-2">Update the details for Agreement #{{ $agreement->agreement_number }}</p>
            </div>

            <form action="{{ route('agreements.update', $agreement) }}" method="POST"
                class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                @csrf
                @method('PUT')

                <div class="p-8 space-y-8">
                    {{-- Meta Information --}}
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Agreement Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Agreement Number</label>
                                <input type="text" name="agreement_number" required
                                    value="{{ old('agreement_number', $agreement->agreement_number) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Agreement Date</label>
                                <input type="date" name="agreement_date" required
                                    value="{{ old('agreement_date', $agreement->agreement_date->format('Y-m-d')) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="draft" {{ $agreement->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="issued" {{ $agreement->status == 'issued' ? 'selected' : '' }}>Issued
                                    </option>
                                    <option value="signed" {{ $agreement->status == 'signed' ? 'selected' : '' }}>Signed
                                    </option>
                                    <option value="cancelled" {{ $agreement->status == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Provider Information --}}
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Provider (Pihak Pertama)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Provider Name</label>
                                <input type="text" name="provider_name" required
                                    value="{{ old('provider_name', $agreement->provider_name) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Provider Address</label>
                                <textarea name="provider_address" required rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('provider_address', $agreement->provider_address) }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Provider Email</label>
                                <input type="email" name="provider_email" required
                                    value="{{ old('provider_email', $agreement->provider_email) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    {{-- Client Information --}}
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Client (Pihak Kedua)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Client Name</label>
                                <input type="text" name="client_name" required
                                    value="{{ old('client_name', $agreement->client_name) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Client Address</label>
                                <textarea name="client_address" required rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('client_address', $agreement->client_address) }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Client Email</label>
                                <input type="email" name="client_email" required
                                    value="{{ old('client_email', $agreement->client_email) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    {{-- Project scope and terms --}}
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Project & Service Details</h3>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Project Name</label>
                                <input type="text" name="project_name" required
                                    value="{{ old('project_name', $agreement->project_name) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Service Description</label>
                                <textarea name="service_description" required rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('service_description', $agreement->service_description) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Scope of Work (Rincian
                                    Pekerjaan)</label>
                                <textarea name="scope_of_work" required rows="5"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('scope_of_work', $agreement->scope_of_work) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Gunakan enter/angka untuk memberikan daftar list yang
                                    rapi pada dokumen.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Financials --}}
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Financials & Timeline</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Total Price (Rp)</label>
                                <input type="number" step="0.01" name="total_price" required
                                    value="{{ old('total_price', (float) $agreement->total_price) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Terms</label>
                                <input type="text" name="payment_terms" required
                                    value="{{ old('payment_terms', $agreement->payment_terms) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" name="start_date" required
                                    value="{{ old('start_date', $agreement->start_date->format('Y-m-d')) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Estimated Completion Date</label>
                                <input type="date" name="estimated_completion_date" required
                                    value="{{ old('estimated_completion_date', $agreement->estimated_completion_date->format('Y-m-d')) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="bg-gray-50 px-8 py-5 flex items-center justify-end space-x-4 border-t border-gray-200">
                    <a href="{{ route('agreements.index') }}"
                        class="text-sm font-medium text-gray-600 hover:text-gray-900">Cancel</a>
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 shadow-sm transition-colors">
                        Update Agreement
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection