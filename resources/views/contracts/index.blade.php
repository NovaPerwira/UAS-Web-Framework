@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Contracts</h2>
                <p class="text-sm text-gray-500">Manage your agreements and contracts.</p>
            </div>
            <a href="{{ route('contracts.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow transition duration-150 ease-in-out flex items-center">
                <span class="text-lg mr-1">+</span> Create Contract
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                <p class="font-bold">Success</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($contracts as $contract)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $contract->contract_number ?? 'DRAFT' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ Str::limit($contract->title, 30) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $contract->client->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $contract->start_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">
                                    Rp {{ number_format($contract->contract_value, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'draft' => 'bg-gray-100 text-gray-800',
                                            'sent' => 'bg-blue-100 text-blue-800',
                                            'accepted' => 'bg-green-100 text-green-800',
                                            'declined' => 'bg-red-100 text-red-800',
                                            'active' => 'bg-indigo-100 text-indigo-800',
                                            'completed' => 'bg-teal-100 text-teal-800',
                                            'terminated' => 'bg-gray-300 text-gray-800',
                                        ];
                                        $class = $statusClasses[$contract->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                                        {{ ucfirst($contract->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('contracts.show', $contract) }}"
                                            class="text-indigo-600 hover:text-indigo-900">View</a>
                                        <a href="{{ route('contracts.edit', $contract) }}"
                                            class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                        <form action="{{ route('contracts.destroy', $contract) }}" method="POST"
                                            onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                    No contracts found. <a href="{{ route('contracts.create') }}"
                                        class="text-indigo-600 hover:underline">Create one?</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $contracts->links() }}
            </div>
        </div>
    </div>
@endsection