@extends('layouts.dashboard')

@section('page_title', 'New User - SGR')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">
    <div class="flex items-center gap-3">
        <a href="{{ route('users.index') }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">New User</h1>
            <p class="text-sm text-gray-500">Create a new system user</p>
        </div>
    </div>

    <form action="{{ route('users.store') }}" method="POST" class="bg-white rounded-xl border p-6 space-y-5">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Name <span class="text-danger-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 rounded-lg border @error('name') border-danger-300 @else border-gray-200 @enderror focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm">
                @error('name')<p class="mt-1 text-xs text-danger-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email <span class="text-danger-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2.5 rounded-lg border @error('email') border-danger-300 @else border-gray-200 @enderror focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm">
                @error('email')<p class="mt-1 text-xs text-danger-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm" placeholder="+255...">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Role <span class="text-danger-500">*</span></label>
                <select name="role" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm">
                    <option value="supervisor" {{ old('role') === 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                    <option value="admin_manager" {{ old('role') === 'admin_manager' ? 'selected' : '' }}>Admin Manager</option>
                    <option value="finance" {{ old('role') === 'finance' ? 'selected' : '' }}>Finance Officer</option>
                    <option value="owner" {{ old('role') === 'owner' ? 'selected' : '' }}>Company Owner</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password <span class="text-danger-500">*</span></label>
                <input type="password" name="password" required class="w-full px-4 py-2.5 rounded-lg border @error('password') border-danger-300 @else border-gray-200 @enderror focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm">
                @error('password')<p class="mt-1 text-xs text-danger-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password <span class="text-danger-500">*</span></label>
                <input type="password" name="password_confirmation" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status</label>
                <select name="status" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm">
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('users.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-maroon-500 rounded-lg hover:bg-maroon-600 transition-colors">Create User</button>
        </div>
    </form>
</div>
@endsection
