@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <h3 class="text-xl font-bold mb-4">Edit Role - {{ $role->name }}</h3>

        <form action="{{ route('roles.update', $role->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Nama Role --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Role</label>
                <input type="text" name="name" id="name" value="{{ $role->name }}" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
            </div>

            {{-- Permissions --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Permissions</label>
                <div class="grid grid-cols-2 gap-2 mt-2">
                    @foreach (['hardware', 'software', 'user-management', 'create', 'edit', 'delete'] as $perm)
                        <div class="flex items-center">
                            <input type="checkbox" name="permissions[]" value="{{ $perm }}"
                                id="perm-{{ $perm }}" class="mr-2"
                                @if (in_array($perm, $role->permissions ?? [])) checked @endif>
                            <label for="perm-{{ $perm }}">{{ ucfirst($perm) }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Update</button>
            <a href="{{ route('roles.index') }}" class="ml-2 text-gray-600">Batal</a>
        </form>
    </div>
@endsection
