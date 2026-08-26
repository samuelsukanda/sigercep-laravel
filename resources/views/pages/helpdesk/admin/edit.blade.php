@extends('layouts.app')

@section('title', 'SIGERCEP - Edit Tiket Helpdesk Admin')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Edit Tiket Helpdesk</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form action="{{ route('admin.helpdesk.update', $ticket->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Nama Pelapor --}}
                                <x-form.input name="user_name" label="Nama Pelapor" :value="ucfirst($ticket->user->name ?? '')" readonly disabled />

                                {{-- Unit --}}
                                <x-form.input name="unit_name" label="Unit" :value="ucfirst($ticket->user->unit ?? '')" readonly disabled />

                                {{-- Jabatan --}}
                                <x-form.input name="jabatan_name" label="Jabatan" :value="ucfirst($ticket->user->jabatan ?? '')" readonly disabled />

                                {{-- Kategori --}}
                                <x-form.select name="category" label="Kategori" :options="['Hardware', 'Printer', 'Jaringan', 'Software', 'SIMRS']" :selected="old('category', $ticket->category)"
                                    required />

                                {{-- Urgensi --}}
                                <x-form.select name="urgency" label="Urgensi" :options="['Low', 'Medium', 'High', 'Critical']" :selected="old('urgency', $ticket->urgency)"
                                    required />

                                {{-- Deskripsi --}}
                                <div class="md:col-span-2">
                                    <x-form.textarea name="description" label="Deskripsi" :value="old('description', $ticket->description)" required />
                                </div>
                            </div>

                            <div class="mt-6">
                                <x-button.submit>Ubah</x-button.submit>
                                <a href="{{ route('admin.helpdesk.index') }}"
                                    class="ml-2 inline-block px-6 py-2 text-xs font-semibold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                                    Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#category').select2({
                placeholder: "Pilih Kategori",
                allowClear: true,
                width: '100%'
            });
            $('#urgency').select2({
                placeholder: "Pilih Urgensi",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endpush
