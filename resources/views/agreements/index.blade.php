@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Agreements</h1>
            <a href="{{ route('agreements.create') }}"
                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                Generate Agreement
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="p-4 font-semibold text-gray-600">Agreement No</th>
                        <th class="p-4 font-semibold text-gray-600">Client</th>
                        <th class="p-4 font-semibold text-gray-600">Total Price</th>
                        <th class="p-4 font-semibold text-gray-600">Date Range</th>
                        <th class="p-4 font-semibold text-gray-600">Status</th>
                        <th class="p-4 font-semibold text-gray-600 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($agreements as $agreement)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 font-medium text-gray-800">
                                {{ $agreement->agreement_number }}
                                @if($agreement->invoice)
                                    <a href="{{ route('invoices.show', $agreement->invoice) }}"
                                        class="block text-xs text-indigo-500 hover:underline">Inv
                                        #{{ $agreement->invoice->invoice_number }}</a>
                                @endif
                            </td>
                            <td class="p-4">
                                <p class="font-medium text-gray-800">{{ $agreement->client_name }}</p>
                                <p class="text-xs text-gray-500">{{ $agreement->company_name }}</p>
                            </td>
                            <td class="p-4 text-gray-800">
                                Rp {{ number_format($agreement->price, 0, ',', '.') }}
                            </td>
                            <td class="p-4 text-sm text-gray-600">
                                {{ $agreement->start_date->format('M d, Y') }} - {{ $agreement->end_date->format('M d, Y') }}
                            </td>
                            <td class="p-4">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-gray-100 text-gray-800',
                                        'sent' => 'bg-blue-100 text-blue-800',
                                        'signed' => 'bg-green-100 text-green-800',
                                        'expired' => 'bg-red-100 text-red-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                    $color = $statusColors[$agreement->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }} uppercase tracking-wider">
                                    {{ $agreement->status }}
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <a href="{{ route('agreements.show', $agreement) }}"
                                    class="text-indigo-600 hover:text-indigo-900 font-medium">View</a>

                                @if($agreement->status === 'draft')
                                    <a href="{{ route('agreements.edit', $agreement) }}"
                                        class="text-yellow-600 hover:text-yellow-900 font-medium">Edit</a>
                                    <form action="{{ route('agreements.destroy', $agreement) }}" method="POST" class="inline-block"
                                        onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500">
                                No agreements found. <a href="{{ route('agreements.create') }}"
                                    class="text-indigo-600 hover:underline">Generate one now</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4 border-t border-gray-200">
                {{ $agreements->links() }}
            </div>
        </div>
    </div>
@endsection