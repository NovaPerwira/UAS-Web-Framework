@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-5xl">

        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <a href="{{ route('agreements.index') }}"
                    class="text-gray-500 hover:text-gray-800 transition flex items-center mb-2">
                    <span class="mr-1">&larr;</span> Back to Agreements
                </a>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                    Agreement {{ $agreement->agreement_number }}
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
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $color }} uppercase tracking-wider">
                        {{ $agreement->status }}
                    </span>
                </h1>
            </div>

            <div class="flex gap-2">
                @if($agreement->status === 'draft')
                    <a href="{{ route('agreements.edit', $agreement) }}"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded shadow transition">
                        Edit Document
                    </a>

                    <form action="{{ route('agreements.send', $agreement) }}" method="POST" class="inline-block"
                        onsubmit="return confirm('Mark this agreement as sent? It can no longer be edited.')">
                        @csrf
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Send to Client
                        </button>
                    </form>
                @endif

                <a href="{{ route('agreements.pdf', $agreement) }}"
                    class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded shadow transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 relative">
                <p>{{ session('success') }}</p>
                @if(str_contains(session('success'), 'Client link:'))
                    <button onclick="copyToClipboard(this)"
                        class="absolute right-4 top-4 text-sm bg-white border border-gray-300 px-3 py-1 rounded shadow-sm hover:bg-gray-50">Copy
                        Link</button>
                @endif
            </div>
        @endif

        <!-- Document Preview (Similar to Contracts Show) -->
        <div class="bg-white shadow-xl max-w-4xl mx-auto rounded-lg overflow-hidden border border-gray-200">

            <!-- Header -->
            <div class="p-10 border-b-2 border-gray-900 flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-wider uppercase">JASA DIGITAL UMKM</h1>
                    <div class="text-sm text-gray-500 mt-2 space-y-1">
                        <p>Jl. Contoh Bisnis No. 123, Tabanan, Bali</p>
                        <p>jasadigitalumkm@gmail.com</p>
                    </div>
                </div>
                <div class="text-right">
                    <h2 class="text-3xl font-light text-gray-400 uppercase tracking-widest">Service Agreement</h2>
                    <div class="mt-4 text-sm text-gray-500">
                        <p><span class="font-semibold text-gray-700">Ref:</span> {{ $agreement->agreement_number }}</p>
                        <p><span class="font-semibold text-gray-700">Date:</span>
                            {{ $agreement->start_date->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Meta -->
            <div class="grid grid-cols-2">
                <div class="p-8 border-b md:border-b-0 border-r border-gray-200">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Client Information</h3>
                    <p class="text-xl font-bold text-gray-900">{{ $agreement->client_name }}</p>
                    <p class="text-gray-600">{{ $agreement->company_name }}</p>
                    <p class="text-sm text-blue-600 mt-1">{{ $agreement->client_email }}</p>
                </div>
                <div class="p-8 bg-gray-50">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Agreement Totals</h3>
                    <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($agreement->price, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-500 mt-2"><span class="font-medium">Term:</span>
                        {{ $agreement->start_date->format('M d, Y') }} - {{ $agreement->end_date->format('M d, Y') }}</p>
                    <p class="text-sm text-gray-500 mt-1"><span class="font-medium">Invoice Ref:</span>
                        #{{ $agreement->invoice->invoice_number ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Body -->
            <div class="p-10 text-gray-800 leading-relaxed font-serif">
                <!-- Render the raw template content, which contains HTML -->
                {!! $agreement->scope_of_work !!}
            </div>

            <!-- Signatures -->
            <div class="p-10 border-t border-gray-200 bg-gray-50">
                <div class="grid grid-cols-2 gap-16">
                    <!-- Provider Signature -->
                    <div class="text-center">
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-14">Authorized by Provider:</p>
                        <div class="border-b border-gray-900 pb-2 mb-2">
                            <p class="font-bold text-gray-900">JASA DIGITAL UMKM</p>
                        </div>
                    </div>

                    <!-- Client Signature -->
                    <div class="text-center">
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Agreed by Client:</p>
                        @if($agreement->status === 'signed' && $agreement->signature_path)
                            <div class="flex justify-center mb-2 h-20 items-end">
                                <img src="{{ Storage::url($agreement->signature_path) }}" alt="Client Signature"
                                    class="max-h-full">
                            </div>
                        @else
                            <div class="h-20 mb-2"></div>
                        @endif
                        <div class="border-b border-gray-900 pb-2 mb-2">
                            <p class="font-bold text-gray-900">{{ $agreement->client_name }}</p>
                        </div>
                        @if($agreement->signed_at)
                            <p class="text-xs text-green-600">Signed on: {{ $agreement->signed_at->format('d M Y, H:i') }}</p>
                        @else
                            <p class="text-xs text-gray-400 italic">Pending Signature</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(button) {
            const textToCopy = "{{ session('success') }}".split('Client link: ')[1];
            if (textToCopy) {
                navigator.clipboard.writeText(textToCopy).then(() => {
                    button.innerText = 'Copied!';
                    setTimeout(() => { button.innerText = 'Copy Link'; }, 2000);
                });
            }
        }
    </script>
@endsection