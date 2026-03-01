@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">

            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900">Document Review & Signature</h1>
                <p class="mt-2 text-gray-600">Please review the agreement below. If you accept the terms, provide your
                    signature at the bottom.</p>
            </div>

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Document Container -->
            <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-gray-200 mb-8">
                <div class="p-8 md:p-12 text-gray-800 leading-relaxed font-serif border-b border-gray-200">
                    {!! $agreement->scope_of_work !!}
                </div>
            </div>

            <!-- Signature Section -->
            @if($agreement->status !== 'signed')
                <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-gray-200 p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Digital Signature</h3>
                    <p class="text-gray-600 mb-6 text-sm">By signing below, you agree to the terms and conditions outlined in
                        the agreement above.</p>

                    <form id="signatureForm"
                        action="{{ URL::signedRoute('client.agreements.sign', ['agreement' => $agreement->id]) }}"
                        method="POST">
                        @csrf
                        <input type="hidden" name="signature" id="signatureInput" required>

                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-2 bg-gray-50 mb-4 inline-block">
                            <canvas id="signaturePad" width="600" height="200"
                                class="cursor-crosshair bg-white rounded border border-gray-200 touch-none max-w-full"></canvas>
                        </div>

                        <div class="flex gap-4 items-center">
                            <button type="button" id="clearBtn"
                                class="text-gray-500 hover:text-gray-800 text-sm font-medium px-4 py-2 border border-gray-300 rounded hover:bg-gray-50 transition">
                                Clear Signature
                            </button>
                            <button type="submit" id="submitBtn"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-8 rounded shadow transition">
                                I Agree & Sign Document
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-green-50 border border-green-200 rounded-lg p-8 text-center shadow-sm">
                    <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-2xl font-bold text-green-900 mb-2">This agreement has been signed.</h3>
                    <p class="text-green-700 mb-6">Thank you. The document was signed on
                        {{ $agreement->signed_at->format('F d, Y \a\t H:i') }}.</p>
                    <a href="{{ URL::signedRoute('client.agreements.pdf', ['agreement' => $agreement->id]) }}"
                        class="inline-block bg-white text-green-700 border border-green-300 font-medium py-2 px-6 rounded shadow-sm hover:bg-green-50 transition">
                        Download Signed PDF
                    </a>
                </div>
            @endif

        </div>
    </div>

    @if($agreement->status !== 'signed')
        <!-- Simple Signature Canvas Logic -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const canvas = document.getElementById('signaturePad');
                const ctx = canvas.getContext('2d');
                const clearBtn = document.getElementById('clearBtn');
                const form = document.getElementById('signatureForm');
                const signatureInput = document.getElementById('signatureInput');

                let isDrawing = false;
                let hasSignature = false;

                // Line settings
                ctx.lineWidth = 3;
                ctx.lineCap = 'round';
                ctx.strokeStyle = '#000000';

                function getMousePos(canvas, evt) {
                    const rect = canvas.getBoundingClientRect();
                    // Handle touch and mouse events
                    const clientX = evt.touches ? evt.touches[0].clientX : evt.clientX;
                    const clientY = evt.touches ? evt.touches[0].clientY : evt.clientY;

                    return {
                        x: (clientX - rect.left) / (rect.right - rect.left) * canvas.width,
                        y: (clientY - rect.top) / (rect.bottom - rect.top) * canvas.height
                    };
                }

                function startPosition(e) {
                    if (e.touches) e.preventDefault(); // Prevent scrolling on touch
                    isDrawing = true;
                    hasSignature = true;
                    draw(e);
                }

                function endPosition() {
                    isDrawing = false;
                    ctx.beginPath();
                }

                function draw(e) {
                    if (!isDrawing) return;
                    if (e.touches) e.preventDefault();

                    const pos = getMousePos(canvas, e);
                    ctx.lineTo(pos.x, pos.y);
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.moveTo(pos.x, pos.y);
                }

                canvas.addEventListener('mousedown', startPosition);
                canvas.addEventListener('mouseup', endPosition);
                canvas.addEventListener('mousemove', draw);
                canvas.addEventListener('mouseleave', endPosition);

                // Touch support for mobile
                canvas.addEventListener('touchstart', startPosition, { passive: false });
                canvas.addEventListener('touchend', endPosition);
                canvas.addEventListener('touchmove', draw, { passive: false });

                clearBtn.addEventListener('click', () => {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    hasSignature = false;
                    ctx.beginPath();
                });

                form.addEventListener('submit', function (e) {
                    if (!hasSignature) {
                        e.preventDefault();
                        alert('Please provide your signature before submitting.');
                        return;
                    }

                    // Convert canvas to base64 image
                    signatureInput.value = canvas.toDataURL('image/png');
                });
            });
        </script>
    @endif
@endsection