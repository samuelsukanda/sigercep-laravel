{{-- resources/views/pages/komplain/ipsrs/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Daftar Komplain IPSRS</h1>

    <a href="{{ route('komplain.ipsrs.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Tambah Komplain</a>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full bg-white shadow-md rounded">
        <thead>
            <tr>
                <th class="py-2 px-4 border">Nama</th>
                <th class="py-2 px-4 border">Unit</th>
                <th class="py-2 px-4 border">Tujuan</th>
                <th class="py-2 px-4 border">Tanggal</th>
                <th class="py-2 px-4 border">Status</th>
                <th class="py-2 px-4 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($komplain as $item)
                <tr>
                    <td class="py-2 px-4 border">{{ $item->nama }}</td>
                    <td class="py-2 px-4 border">{{ $item->unit }}</td>
                    <td class="py-2 px-4 border">{{ $item->tujuan_unit }}</td>
                    <td class="py-2 px-4 border">{{ $item->tanggal }}</td>
                    <td class="py-2 px-4 border">{{ $item->status ?? '-' }}</td>
                    <td class="py-2 px-4 border space-x-2">
                        <a href="{{ route('komplain.ipsrs.edit', $item->id) }}" class="text-blue-500">Edit</a>
                        <form action="{{ route('komplain.ipsrs.destroy', $item->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin ingin hapus?')" class="text-red-500">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection


{{-- resources/views/pages/komplain/ipsrs/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Tambah Komplain IPSRS</h1>

    <form action="{{ route('komplain.ipsrs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        @include('pages.komplain.ipsrs.form')

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
        <a href="{{ route('komplain.ipsrs.index') }}" class="text-gray-600">Kembali</a>
    </form>
</div>
@endsection


{{-- resources/views/pages/komplain/ipsrs/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Edit Komplain IPSRS</h1>

    <form action="{{ route('komplain.ipsrs.update', $komplain->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        @include('pages.komplain.ipsrs.form', ['komplain' => $komplain])

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Perbarui</button>
        <a href="{{ route('komplain.ipsrs.index') }}" class="text-gray-600">Batal</a>
    </form>
</div>
@endsection


{{-- resources/views/pages/komplain/ipsrs/form.blade.php --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label>Nama</label>
        <input type="text" name="nama" class="w-full border p-2 rounded" value="{{ old('nama', $komplain->nama ?? '') }}" required>
    </div>
    <div>
        <label>Unit</label>
        <input type="text" name="unit" class="w-full border p-2 rounded" value="{{ old('unit', $komplain->unit ?? '') }}" required>
    </div>
    <div>
        <label>Tujuan Unit</label>
        <input type="text" name="tujuan_unit" class="w-full border p-2 rounded" value="{{ old('tujuan_unit', $komplain->tujuan_unit ?? '') }}" required>
    </div>
    <div>
        <label>Tanggal</label>
        <input type="date" name="tanggal" class="w-full border p-2 rounded" value="{{ old('tanggal', $komplain->tanggal ?? '') }}" required>
    </div>
    <div class="md:col-span-2">
        <label>Kendala</label>
        <textarea name="kendala" rows="4" class="w-full border p-2 rounded" required>{{ old('kendala', $komplain->kendala ?? '') }}</textarea>
    </div>
    <div>
        <label>Foto</label>
        <input type="file" name="foto" class="w-full border p-2 rounded">
        @if(isset($komplain) && $komplain->foto)
            <img src="{{ asset('storage/' . $komplain->foto) }}" alt="Foto" class="mt-2 h-24">
        @endif
    </div>
    <div>
        <label>Status</label>
        <select name="status" class="w-full border p-2 rounded">
            <option value="">Pilih Status</option>
            @foreach(['Pending', 'On Progress', 'Done'] as $status)
                <option value="{{ $status }}" {{ (old('status', $komplain->status ?? '') == $status) ? 'selected' : '' }}>{{ $status }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <label>Keterangan</label>
        <input type="text" name="keterangan" class="w-full border p-2 rounded" value="{{ old('keterangan', $komplain->keterangan ?? '') }}">
    </div>
</div>
