@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow-md rounded p-6">
    <h2 class="text-xl font-bold mb-4">Edit Role User</h2>

    <form action="{{ route('roles.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-medium">Nama User</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                class="w-full border p-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block font-medium">Level</label>
            <select name="level" class="w-full border p-2 rounded">
                <option value="SUPERADMIN" {{ $user->level == 'SUPERADMIN' ? 'selected' : '' }}>SUPERADMIN</option>
                <option value="ADMIN" {{ $user->level == 'ADMIN' ? 'selected' : '' }}>ADMIN</option>
                <option value="USER" {{ $user->level == 'USER' ? 'selected' : '' }}>USER</option>
            </select>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('roles.index') }}" class="px-4 py-2 bg-gray-400 text-white rounded mr-2">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
        </div>
    </form>
</div>
@endsection
