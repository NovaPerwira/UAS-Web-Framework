@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Action Buttons (No Print) -->
        <div class="max-w-4xl mx-auto mb-6 flex justify-between items-center no-print">
            <div class="flex gap-4">
                <a href="{{ route('contracts.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center">
                    <span class="mr-1">&larr;</span> Back to Contracts
                </a>
                <a href="{{ route('relations.index', ['search' => $contract->client->name]) }}" class="text-indigo-600 hover:text-indigo-800 flex items-center font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    View in Master Data
                </a>
            </div>
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
        <div
            class="printable-area max-w-4xl mx-auto bg-white shadow-2xl overflow-hidden print:shadow-none print:max-w-none print:m-0">

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

                    @if($contract->project)
                        <div class="mt-6 pt-4 border-t border-gray-100">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Project</h3>
                            <p class="font-bold text-gray-900">{{ $contract->project->project_name }}</p>
                            <p class="text-sm text-gray-500">Budget: Rp
                                {{ number_format($contract->project->budget, 0, ',', '.') }}</p>
                        </div>
                    @endif
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
                                {{ $contract->end_date ? $contract->end_date->format('M d, Y') : 'Ongoing' }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500 mb-1 font-semibold uppercase">Total Contract Value</p>
                        <p class="text-2xl font-bold text-gray-900">Rp
                            {{ number_format($contract->contract_value, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- CONTENT BODY: Isi Perjanjian -->
            <div class="p-10 print:p-8 text-gray-800 leading-relaxed font-serif text-sm">

                <h3 class="text-lg font-bold text-gray-900 mb-6 border-b pb-2">Terms and Conditions</h3>

                <div class="space-y-8 text-justify">
                    <!-- 1. Identitas Para Pihak -->
                    <div>
                        <h4 class="font-bold uppercase mb-2">1. IDENTITAS PARA PIHAK</h4>
                        <p class="mb-2">Perjanjian ini dibuat pada tanggal
                            <strong>{{ $contract->start_date ? $contract->start_date->format('d F Y') : now()->format('d F Y') }}</strong>,
                            oleh dan antara:
                        </p>
                        <div class="pl-4 mb-2">
                            <p><strong>Pihak Pertama:</strong></p>
                            <p>Nama: Admin User</p>
                            <p>Jabatan: Project Manager</p>
                            <p>Perusahaan: <strong>JASA DIGITAL UMKM</strong></p>
                            <p>Alamat: Jl. Contoh Bisnis No. 123, Tabanan, Bali</p>
                            <p>Selanjutnya disebut sebagai <strong>"Penyedia Jasa"</strong>.</p>
                        </div>
                        <div class="pl-4">
                            <p><strong>Pihak Kedua:</strong></p>
                            <p>Nama: {{ $contract->client->name }}</p>
                            <p>Perusahaan: {{ $contract->client->company_name ?? $contract->client->name }}</p>
                            <p>Alamat: {{ $contract->client->address ?? 'Alamat tidak tersedia' }}</p>
                            <p>Selanjutnya disebut sebagai <strong>"Klien"</strong>.</p>
                        </div>
                    </div>

                    <!-- 2. Ruang Lingkup Pekerjaan -->
                    @if($contract->scope_of_work)
                        <div>
                            <h4 class="font-bold uppercase mb-2">2. RUANG LINGKUP PEKERJAAN (SCOPE OF WORK)</h4>
                            <div class="whitespace-pre-line">{{ $contract->scope_of_work }}</div>
                        </div>
                    @endif

                    <!-- 3. Timeline Pengerjaan -->
                    @if($contract->timeline)
                        <div>
                            <h4 class="font-bold uppercase mb-2">3. TIMELINE PENGERJAAN</h4>
                            <div class="whitespace-pre-line">{{ $contract->timeline }}</div>
                        </div>
                    @endif

                    <!-- 4. Biaya & Skema Pembayaran -->
                    @if($contract->payment_terms)
                        <div>
                            <h4 class="font-bold uppercase mb-2">4. BIAYA & SKEMA PEMBAYARAN</h4>
                            <div class="whitespace-pre-line">{{ $contract->payment_terms }}</div>
                        </div>
                    @endif

                    <!-- 5. Revisi & Perubahan Scope -->
                    @if($contract->revisions)
                        <div>
                            <h4 class="font-bold uppercase mb-2">5. REVISI & PERUBAHAN SCOPE</h4>
                            <div class="whitespace-pre-line">{{ $contract->revisions }}</div>
                        </div>
                    @endif

                    <!-- 6. Hak Kepemilikan -->
                    @if($contract->ownership_rights)
                        <div>
                            <h4 class="font-bold uppercase mb-2">6. HAK KEPEMILIKAN</h4>
                            <div class="whitespace-pre-line">{{ $contract->ownership_rights }}</div>
                        </div>
                    @endif

                    <!-- 7. Garansi & Support -->
                    @if($contract->warranty)
                        <div>
                            <h4 class="font-bold uppercase mb-2">7. GARANSI & SUPPORT</h4>
                            <div class="whitespace-pre-line">{{ $contract->warranty }}</div>
                        </div>
                    @endif

                    <!-- 8. Ketentuan Umum -->
                    @if($contract->general_terms)
                        <div>
                            <h4 class="font-bold uppercase mb-2">8. KETENTUAN UMUM</h4>
                            <div class="whitespace-pre-line">{{ $contract->general_terms }}</div>
                        </div>
                    @endif

                    <!-- Additional/Legacy Content -->
                    @if($contract->content)
                        <div>
                            <h4 class="font-bold uppercase mb-2">9. TAMBAHAN / LAIN-LAIN</h4>
                            <div class="whitespace-pre-line">{{ $contract->content }}</div>
                        </div>
                    @endif
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
            @page {
                margin: 1cm;
                size: auto;
            }

            /* Reset Body */
            body {
                margin: 0;
                padding: 0;
                background-color: white;
                color: black;
            }

            /* Hide everything primarily */
            body * {
                visibility: hidden;
            }

            /* Un-hide the printable area and its children */
            .printable-area,
            .printable-area * {
                visibility: visible;
            }

            /* Position the printable area to flow naturally */
            .printable-area {
                position: static !important;
                /* Critical for multi-page */
                width: 100% !important;
                height: auto !important;
                overflow: visible !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
                display: block !important;
            }

            /* Explicitly hide navigation/sidebar/etc */
            nav,
            header,
            footer,
            aside,
            .no-print,
            .action-buttons {
                display: none !important;
            }

            /* Ensure grids don't break layout */
            .grid {
                display: block !important;
            }

            .grid-cols-2>div {
                width: 50%;
                float: left;
            }

            .grid-cols-2::after {
                content: "";
                clear: both;
                display: table;
            }

            /* Page Break Logic */
            h1,
            h2,
            h3,
            h4,
            .border-b {
                page-break-after: avoid;
            }

            p,
            div {
                page-break-inside: auto;
                /* Allow breaking inside long texts */
                orphans: 3;
                widows: 3;
            }

            /* Signatures: Keep together */
            .grid-cols-2.gap-16 {
                page-break-inside: avoid;
            }
        }
    </style>
@endsection