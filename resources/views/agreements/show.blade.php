@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Agreement Overview</h1>
                    <p class="text-gray-600 mt-2">Agreement <span
                            class="font-semibold">{{ $agreement->agreement_number }}</span> for
                        {{ $agreement->client_name }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('invoices.show', $agreement->invoice_id) }}"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 hover:bg-gray-50 font-medium">
                        Back to Invoice
                    </a>
                    <a href="{{ route('agreements.pdf', $agreement) }}"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm font-medium flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download PDF
                    </a>

                    <a href="{{ route('agreements.edit', $agreement) }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-sm font-medium flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                            </path>
                        </svg>
                        Edit
                    </a>

                    <form action="{{ route('agreements.destroy', $agreement) }}" method="POST" class="inline"
                        onsubmit="return confirm('Are you sure you want to delete this agreement?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 shadow-sm font-medium flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                    <p class="font-bold">Success</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden mb-8">
                <div class="p-8">
                    <div class="prose max-w-none text-gray-800">
                        <h2
                            class="text-center text-xl font-bold uppercase tracking-widest mb-2 border-b-2 border-gray-800 pb-4">
                            PERJANJIAN KERJA SAMA JASA PENGEMBANGAN WEBSITE</h2>
                        <p class="text-center mb-8">Nomor: {{ $agreement->agreement_number }}<br>Tanggal:
                            {{ $agreement->agreement_date->format('d/m/Y') }}
                        </p>

                        <h3 class="font-bold mt-8 mb-4">IDENTITAS PARA PIHAK</h3>
                        <p>Perjanjian ini dibuat dan ditandatangani pada tanggal
                            {{ $agreement->agreement_date->format('d F Y') }}, oleh dan antara:
                        </p>

                        <div class="ml-4 mb-4">
                            <p class="font-bold">Pihak Pertama:</p>
                            <table class="w-full text-sm">
                                <tr>
                                    <td class="w-32 py-1">Nama</td>
                                    <td>: {{ $agreement->provider_name }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1">Alamat</td>
                                    <td>: {{ $agreement->provider_address }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1">Email</td>
                                    <td>: {{ $agreement->provider_email }}</td>
                                </tr>
                            </table>
                            <p class="italic mt-2">Selanjutnya disebut sebagai <strong>"Penyedia Jasa"</strong>.</p>
                        </div>

                        <div class="ml-4 mb-8">
                            <p class="font-bold">Pihak Kedua:</p>
                            <table class="w-full text-sm">
                                <tr>
                                    <td class="w-32 py-1">Nama</td>
                                    <td>: {{ $agreement->client_name }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1">Alamat</td>
                                    <td>: {{ $agreement->client_address }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1">Email</td>
                                    <td>: {{ $agreement->client_email }}</td>
                                </tr>
                            </table>
                            <p class="italic mt-2">Selanjutnya disebut sebagai <strong>"Klien"</strong>.</p>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 p-6 rounded-lg text-sm text-blue-800">
                            <p class="font-semibold text-base mb-2">Notice</p>
                            <p>This is a web preview outlining the key variables you just submitted. To read the full,
                                legally structured formatting including all clauses and limitation of liability, please
                                generate the printable PDF document.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection