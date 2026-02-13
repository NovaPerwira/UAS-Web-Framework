@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Contract: {{ $contract->contract_number }}</h1>

            <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
                <form action="{{ route('contracts.update', $contract) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Client -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Client</label>
                            <select name="client_id" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" {{ $contract->client_id == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contract Title</label>
                            <input type="text" name="title" value="{{ old('title', $contract->title) }}" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                            <input type="date" name="start_date"
                                value="{{ old('start_date', $contract->start_date ? $contract->start_date->format('Y-m-d') : '') }}"
                                required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- End Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">End Date (Optional)</label>
                            <input type="date" name="end_date"
                                value="{{ old('end_date', $contract->end_date ? $contract->end_date->format('Y-m-d') : '') }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Value -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contract Value</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="contract_value" step="0.01" min="0"
                                    value="{{ old('contract_value', $contract->contract_value) }}"
                                    class="pl-12 w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach(['draft', 'sent', 'accepted', 'declined', 'active', 'completed', 'terminated'] as $status)
                                    <option value="{{ $status }}" {{ $contract->status == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-6 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Detailed Terms</h3>

                        <div class="space-y-6">
                            <!-- Scope of Work -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Scope of Work</label>
                                <textarea name="scope_of_work" rows="4"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('scope_of_work', $contract->scope_of_work) }}</textarea>
                            </div>

                            <!-- Timeline -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Timeline</label>
                                <textarea name="timeline" rows="3"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('timeline', $contract->timeline) }}</textarea>
                            </div>

                            <!-- Payment Terms -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Terms</label>
                                <textarea name="payment_terms" rows="3"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('payment_terms', $contract->payment_terms) }}</textarea>
                            </div>

                            <!-- Revisions -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Revisions & Changes</label>
                                <textarea name="revisions" rows="3"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('revisions', $contract->revisions) }}</textarea>
                            </div>

                            <!-- Ownership Rights -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ownership Rights</label>
                                <textarea name="ownership_rights" rows="3"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('ownership_rights', $contract->ownership_rights) }}</textarea>
                            </div>

                            <!-- Warranty & Support -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Warranty & Support</label>
                                <textarea name="warranty" rows="3"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('warranty', $contract->warranty) }}</textarea>
                            </div>

                            <!-- General Terms -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">General Terms</label>
                                <textarea name="general_terms" rows="3"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('general_terms', $contract->general_terms) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Additional Content / Legacy</label>
                        <textarea name="content" rows="15"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono text-sm"
                            placeholder="Enter any additional agreement text here...">{{ old('content', $contract->content) }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">Use this for any extra clauses or if you are not using the structured fields above.</p>
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Internal Notes</label>
                        <textarea name="notes" rows="3"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $contract->notes) }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('contracts.index') }}"
                            class="px-4 py-2 text-gray-700 hover:text-gray-900 font-medium">Cancel</a>
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow transition">
                            Update Contract
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection