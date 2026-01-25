@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div
            class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700/50 p-8">
            <div class="mb-8 border-b border-slate-100 dark:border-slate-700 pb-6">
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Create New User</h2>
                <p class="mt-2 text-slate-500 dark:text-slate-400">Fill in the details to create a new user account.</p>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="name"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700/50 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors @error('name') border-red-500 dark:border-red-500 @enderror"
                            required placeholder="John Doe">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email
                            Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700/50 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors @error('email') border-red-500 dark:border-red-500 @enderror"
                            required placeholder="john@example.com">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Password</label>
                            <input type="password" name="password" id="password"
                                class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700/50 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors @error('password') border-red-500 dark:border-red-500 @enderror"
                                required placeholder="••••••••">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Confirm
                                Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700/50 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors"
                                required placeholder="••••••••">
                        </div>
                    </div>

                    <div>
                        <label for="role"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Role</label>
                        <select name="role" id="role"
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700/50 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors cursor-pointer @error('role') border-red-500 dark:border-red-500 @enderror">
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-4">
                    <a href="{{ route('admin.users.index') }}"
                        class="px-5 py-2.5 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-500/20 shadow-lg shadow-indigo-500/30 transition-all duration-200">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection