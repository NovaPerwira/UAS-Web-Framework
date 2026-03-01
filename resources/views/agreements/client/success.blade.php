@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-12 px-4 shadow-xl sm:rounded-lg sm:px-10 text-center border-t-4 border-green-500">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                    <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Agreement Signed!</h2>
                <p class="text-gray-600 mb-8">
                    Thank you, <strong>{{ $agreement->client_name }}</strong>. Your agreement has been successfully signed
                    and recorded.
                </p>

                <a href="{{ URL::signedRoute('client.agreements.pdf', ['agreement' => $agreement->id]) }}"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    Download PDF Copy
                </a>

                <div class="mt-6 text-sm text-gray-500">
                    A copy has been saved on our servers. You may keep this link to access the signed document in the
                    future.
                </div>
            </div>
        </div>
    </div>
@endsection