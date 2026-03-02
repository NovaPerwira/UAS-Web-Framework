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
                    Back to Agreement
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Edit Agreement</h1>
                <p class="text-gray-500 mt-1">{{ $agreement->agreement_number }}</p>
            </div>

            {{-- Locked Alert --}}
            @if (!$agreement->canEdit())
                <div class="bg-amber-50 border border-amber-300 text-amber-800 p-4 mb-6 rounded-lg flex items-start gap-3">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg>
                    <div>
                        <p class="font-semibold">Agreement is locked</p>
                        <p class="text-sm">This agreement is <strong>{{ $agreement->status }}</strong>. The document content has
                            been legally frozen and cannot be modified. Only draft agreements can be edited.</p>
                    </div>
                </div>
                <div class="flex justify-center">
                    <a href="{{ route('agreements.show', $agreement) }}"
                        class="px-6 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700">
                        Back to Agreement
                    </a>
                </div>
            @else

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('agreements.update', $agreement) }}" method="POST"
                    class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    @csrf
                    @method('PUT')

                    <div class="p-8 space-y-8">

                        {{-- Agreement Meta --}}
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Agreement Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Agreement Number</label>
                                    <input type="text" value="{{ $agreement->agreement_number }}" disabled
                                        class="mt-1 block w-full rounded-md border-gray-200 bg-gray-50 text-gray-500 shadow-sm cursor-not-allowed">
                                    <p class="text-xs text-gray-400 mt-1">Agreement number cannot be changed.</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Agreement Date <span
                                            class="text-red-500">*</span></label>
                                    <input type="date" name="agreement_date" required
                                        value="{{ old('agreement_date', $agreement->agreement_date->format('Y-m-d')) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        {{-- Provider --}}
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Provider (Pihak Pertama)</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Provider Name <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="provider_name" required
                                        value="{{ old('provider_name', $agreement->provider_name) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Provider Address <span
                                            class="text-red-500">*</span></label>
                                    <textarea name="provider_address" required rows="2"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('provider_address', $agreement->provider_address) }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Provider Email <span
                                            class="text-red-500">*</span></label>
                                    <input type="email" name="provider_email" required
                                        value="{{ old('provider_email', $agreement->provider_email) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        {{-- Client --}}
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Client (Pihak Kedua)</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Client Name <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="client_name" required
                                        value="{{ old('client_name', $agreement->client_name) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Client Address <span
                                            class="text-red-500">*</span></label>
                                    <textarea name="client_address" required rows="2"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('client_address', $agreement->client_address) }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Client Email <span
                                            class="text-red-500">*</span></label>
                                    <input type="email" name="client_email" required
                                        value="{{ old('client_email', $agreement->client_email) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        {{-- Project & Service --}}
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Project & Service Details</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Project Name <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="project_name" required
                                        value="{{ old('project_name', $agreement->project_name) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Service Description <span
                                            class="text-red-500">*</span></label>
                                    <textarea name="service_description" required rows="2"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('service_description', $agreement->service_description) }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Scope of Work <span
                                            class="text-red-500">*</span></label>
                                    <textarea name="scope_of_work" required rows="5"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('scope_of_work', $agreement->scope_of_work) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Financials --}}
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Financials & Timeline</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Total Agreement Value (Rp) <span
                                            class="text-red-500">*</span></label>
                                    <input type="number" step="1" name="total_value" required
                                        value="{{ old('total_value', (float) $agreement->total_value) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Payment Terms <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="payment_terms" required
                                        value="{{ old('payment_terms', $agreement->payment_terms) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Start Date <span
                                            class="text-red-500">*</span></label>
                                    <input type="date" name="start_date" required
                                        value="{{ old('start_date', $agreement->start_date->format('Y-m-d')) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Est. Completion Date <span
                                            class="text-red-500">*</span></label>
                                    <input type="date" name="estimated_completion_date" required
                                        value="{{ old('estimated_completion_date', $agreement->estimated_completion_date->format('Y-m-d')) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="bg-gray-50 px-8 py-5 flex items-center justify-end gap-3 border-t border-gray-200">
                        <a href="{{ route('agreements.show', $agreement) }}"
                            class="text-sm font-medium text-gray-600 hover:text-gray-900 px-4 py-2">Cancel</a>
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 shadow-sm">
                            Update Agreement
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection