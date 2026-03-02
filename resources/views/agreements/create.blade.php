@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Generate Service Agreement</h1>
                <p class="text-gray-600 mt-2">Fill out the details below to generate a formal Service Agreement for Invoice
                    #{{ $invoice->invoice_number }}</p>
            </div>

            <form action="{{ route('agreements.store') }}" method="POST"
                class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                @csrf
                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                <div class="p-8 space-y-8">
                    {{-- Meta Information --}}
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Agreement Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Agreement Number</label>
                                <input type="text" name="agreement_number" required
                                    value="AGR-{{ date('Y') }}-{{ str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Agreement Date</label>
                                <input type="date" name="agreement_date" required value="{{ date('Y-m-d') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    {{-- Provider Information --}}
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Provider (Pihak Pertama)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Provider Name</label>
                                <input type="text" name="provider_name" required value="Jasa Digital UMKM"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Provider Address</label>
                                <textarea name="provider_address" required rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">Jl. Contoh Bisnis No. 123, Tabanan, Bali</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Provider Email</label>
                                <input type="email" name="provider_email" required value="jasadigitalumkm@gmail.com"
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
                                <input type="text" name="client_name" required value="{{ $invoice->client->name }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Client Address</label>
                                <textarea name="client_address" required rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $invoice->client->address ?? 'Alamat lengkap Klien' }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Client Email</label>
                                <input type="email" name="client_email" required value="{{ $invoice->client->email }}"
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
                                    value="{{ $invoice->project->project_name ?? 'Website Development' }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Service Description</label>
                                <textarea name="service_description" required rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">Pengembangan Website Perusahaan / E-Commerce custom dengan fitur lengkap.</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Scope of Work (Rincian
                                    Pekerjaan)</label>
                                <textarea name="scope_of_work" required rows="5"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">1. Desain UI/UX struktur halaman depan.
    2. Pengembangan sistem backend dan database.
    3. Integrasi payment gateway.
    4. Setup dan deployment ke server.</textarea>
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
                                    value="{{ $invoice->grand_total ?? '0' }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Terms</label>
                                <input type="text" name="payment_terms" required value="50% DP, 50% Pelunasan"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" name="start_date" required value="{{ date('Y-m-d') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Estimated Completion Date</label>
                                <input type="date" name="estimated_completion_date" required
                                    value="{{ date('Y-m-d', strtotime('+30 days')) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="bg-gray-50 px-8 py-5 flex items-center justify-end space-x-4 border-t border-gray-200">
                    <a href="{{ route('invoices.show', $invoice) }}"
                        class="text-sm font-medium text-gray-600 hover:text-gray-900">Cancel</a>
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 shadow-sm transition-colors">
                        Generate Agreement
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection