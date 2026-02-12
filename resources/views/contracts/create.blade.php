@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Create New Contract</h1>

            <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
                <form action="{{ route('contracts.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Client -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Client</label>
                            <select name="client_id" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contract Title</label>
                            <input type="text" name="title" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                            <input type="date" name="start_date" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- End Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">End Date (Optional)</label>
                            <input type="date" name="end_date"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Value -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contract Value</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="contract_value" step="0.01" min="0" placeholder="0.00"
                                    class="pl-12 w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="draft">Draft</option>
                                <option value="sent">Sent</option>
                                <option value="accepted">Accepted</option>
                                <option value="declined">Declined</option>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                                <option value="terminated">Terminated</option>
                            </select>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contract Terms (Content)</label>
                        <textarea name="content" rows="10"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Enter the full contract agreement here..."></textarea>
                        <p class="text-sm text-gray-500 mt-1">You may use simple formatted text.</p>
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Internal Notes</label>
                        <textarea name="notes" rows="3"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Private notes..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('contracts.index') }}"
                            class="px-4 py-2 text-gray-700 hover:text-gray-900 font-medium">Cancel</a>
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow transition">
                            Create Contract
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection