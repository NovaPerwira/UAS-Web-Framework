@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Action Buttons (No Print) -->
        <div class="max-w-4xl mx-auto mb-6 flex justify-between items-center no-print">
            <a href="{{ route('contracts.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center">
                <span class="mr-1">&larr;</span> Back to Contracts
            </a>
            <div class="flex gap-2">
                <a href="{{ route('contracts.edit', $contract) }}"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded shadow transition">
                    Edit
                </a>
                <button onclick="window.print()"
                    class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded shadow transition flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print / PDF
                </button>
            </div>
        </div>

        <!-- Main Contract Card -->
        <div class="max-w-4xl mx-auto bg-white shadow-2xl overflow-hidden print:shadow-none print:max-w-none print:m-0">

            <!-- HEADER SECTION -->
            <div
                class="p-10 border-b-2 border-gray-900 flex flex-col md:flex-row justify-between items-start print:p-8 print:border-b-2">
                <!-- Info Perusahaan -->
                <div class="mb-6 md:mb-0">
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-wider uppercase">JASA DIGITAL UMKM</h1>
                    <!-- <p class="text-sm font-bold text-gray-600 mb-3 tracking-wide"></p> -->
                    <div class="text-sm text-gray-500 space-y-1">
                        <p>Jl. Contoh Bisnis No. 123</p>
                        <p>Tabanan, Bali</p>
                        <p>jasadigitalumkm@gmail.com</p>
                    </div>
                </div>

                <!-- Judul Dokumen -->
                <div class="md:text-right">
                    <h2 class="text-4xl font-light text-gray-300 uppercase tracking-widest">Agreement</h2>
                    <div class="mt-4 text-sm text-gray-500">
                        <p><span class="font-semibold text-gray-700">Ref:</span> {{ $contract->contract_number }}</p>
                        <p><span class="font-semibold text-gray-700">Date:</span> {{ now()->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- INFO GRID: Client & Project Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 border-b border-gray-200 print:grid-cols-2">

                <!-- Detail Klien -->
                <div class="p-10 border-b md:border-b-0 md:border-r border-gray-200 print:p-8 print:border-r">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Client Details</h3>
                    <div class="space-y-1">
                        <p class="text-xl font-bold text-gray-900">{{ $contract->client->name }}</p>
                        <p class="text-sm text-blue-600">{{ $contract->client->email }}</p>
                        <p class="text-sm text-gray-500">{{ $contract->client->phone ?? '' }}</p>
                        <p class="text-sm text-gray-500">{{ $contract->client->address ?? '' }}</p>
                    </div>
                </div>

                <!-- Detail Kontrak -->
                <div class="bg-gray-50 p-10 print:bg-transparent print:p-8">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Contract Terms</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs text-gray-500 mb-1 font-semibold uppercase">Start Date</p>
                            <p class="font-medium text-gray-900">{{ $contract->start_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1 font-semibold uppercase">End Date</p>
                            <p class="font-medium text-gray-900">
                                {{ $contract->end_date ? $contract->end_date->format('M d, Y') : 'Ongoing' }}</p>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500 mb-1 font-semibold uppercase">Total Contract Value</p>
                        <p class="text-2xl font-bold text-gray-900">Rp
                            {{ number_format($contract->contract_value, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- CONTENT BODY: Isi Perjanjian -->
            <div class="p-10 print:p-8">
                <div class="flex justify-between items-center mb-6 border-b pb-2">
                    <h3 class="text-lg font-bold text-gray-900">{{ $contract->title }}</h3>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold 
                            {{ $contract->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} 
                            print:hidden">
                        Status: {{ ucfirst($contract->status) }}
                    </span>
                </div>

                <div class="font-serif text-sm leading-relaxed text-gray-700 space-y-4 text-justify whitespace-pre-line">
                    {{ $contract->content }}
                </div>
            </div>

            <!-- FOOTER: Tanda Tangan -->
            <div class="p-10 mt-4 print:p-8 print:mt-0 print:break-inside-avoid">
                <div class="grid grid-cols-2 gap-16">
                    <!-- Tanda Tangan Klien -->
                    <div class="text-center">
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-16">Agreed & Accepted by Client:</p>
                        <div class="border-b border-gray-900 pb-2">
                            <p class="font-bold text-gray-900">{{ $contract->client->name }}</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Date: _________________</p>
                    </div>

                    <!-- Tanda Tangan Perusahaan -->
                    <div class="text-center">
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-16">Authorized by Service Provider:</p>
                        <div class="border-b border-gray-900 pb-2">
                            <p class="font-bold text-gray-900">JASA DIGITAL UMKM</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Date: {{ now()->format('d / m / Y') }}</p>
                    </div>
                </div>

                <!-- Catatan Kaki -->
                <div class="mt-12 pt-6 border-t border-gray-200 text-center text-xs text-gray-400 print:mt-8">
                    <p>This document constitutes a binding agreement between the parties listed above.</p>
                    
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .container,
            .container * {
                visibility: visible;
            }

            .container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }

            nav,
            header,
            footer {
                display: none !important;
            }

            /* Ensure the card takes full width and no shadow in print */
            .shadow-2xl {
                box-shadow: none !important;
            }

            .bg-white {
                background-color: white !important;
            }
        }
    </style>
@endsection