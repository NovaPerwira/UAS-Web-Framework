@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Project</h2>
        <p class="text-sm text-gray-500">Update project details and status.</p>
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
                    <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
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
        <form action="{{ route('projects.update', $project) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">
                
                {{-- PROJECT NAME --}}
                <div>
                    <label for="project_name" class="block text-sm font-medium text-gray-700">Project Name</label>
                    <input type="text" 
                           name="project_name" 
                           id="project_name"
                           value="{{ old('project_name', $project->project_name) }}"
                           required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border">
                </div>

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    
                    {{-- CLIENT --}}
                    <div>
                        <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                        <select name="client_id" 
                                id="client_id" 
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border bg-white">
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- FREELANCER --}}
                    <div>
                        <label for="freelancer_id" class="block text-sm font-medium text-gray-700">Freelancer</label>
                        <select name="freelancer_id" 
                                id="freelancer_id" 
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border bg-white">
                            @foreach ($freelancers as $freelancer)
                                <option value="{{ $freelancer->id }}" {{ old('freelancer_id', $project->freelancer_id) == $freelancer->id ? 'selected' : '' }}>
                                    {{ $freelancer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    
                    {{-- BUDGET --}}
                    <div>
                        <label for="budget" class="block text-sm font-medium text-gray-700">Budget</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" 
                                   name="budget" 
                                   id="budget" 
                                   min="1000"
                                   value="{{ old('budget', $project->budget) }}"
                                   required
                                   class="block w-full pl-10 sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
                        </div>
                    </div>

                    {{-- STATUS --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" 
                                id="status"
                                {{ $project->status === 'completed' ? 'disabled' : '' }}
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border bg-white {{ $project->status === 'completed' ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}">
                            @foreach (['pending','ongoing','completed','cancelled'] as $status)
                                <option value="{{ $status }}" {{ old('status', $project->status) === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Hidden Input & Helper Text for Locked Status --}}
                        @if ($project->status === 'completed')
                            <input type="hidden" name="status" value="completed">
                            <p class="mt-2 text-xs text-yellow-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Completed projects are locked.
                            </p>
                        @endif
                    </div>
                </div>

            </div>

            <div class="px-6 py-4 bg-gray-50 text-right sm:px-6 flex items-center justify-end space-x-4 border-t border-gray-100">
                <a href="{{ route('projects.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                    Cancel
                </a>
                
                <button type="submit" 
                        {{ $project->status === 'completed' ? 'disabled' : '' }}
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition duration-150">
                    Update Project
                </button>
            </div>
        </form>
    </div>
</div>
@endsection