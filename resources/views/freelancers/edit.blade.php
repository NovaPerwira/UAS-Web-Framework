@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-10">
    
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Freelancer</h2>
        <p class="text-sm text-gray-500">Update freelancer profile and skills.</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <form method="POST" action="{{ route('freelancers.update', $freelancer) }}">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">
                
                {{-- NAME --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" 
                           name="name" 
                           id="name"
                           value="{{ old('name', $freelancer->name) }}"
                           required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border">
                </div>

                {{-- SKILL --}}
                <div>
                    <label for="skill" class="block text-sm font-medium text-gray-700">Main Skill / Role</label>
                    <input type="text" 
                           name="skill" 
                           id="skill"
                           value="{{ old('skill', $freelancer->skill) }}"
                           placeholder="e.g. Backend Developer, UI Designer"
                           required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border">
                    <p class="mt-2 text-xs text-gray-500">Specify the primary expertise of this freelancer.</p>
                </div>

            </div>

            <div class="px-6 py-4 bg-gray-50 text-right sm:px-6 flex items-center justify-end space-x-4 border-t border-gray-100">
                <a href="{{ route('freelancers.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                    Cancel
                </a>
                
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                    Update Freelancer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection