@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Edit Pelaporan IKP</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form action="{{ route('komite-mutu.pelaporan-ikp.update', $pelaporanIkp->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Nama Pasien --}}
                                <x-form.input name="nama" label="Nama Pasien" :value="old('nama', $pelaporanIkp->nama ?? '')" required />

                                {{-- No. Rekam Medis --}}
                                <x-form.input name="no_rm" label="No. Rekam Medis" :value="old('no_rm', $pelaporanIkp->no_rm ?? '')" required />

                                {{-- Tanggal Lahir --}}
                                <x-form.input name="tanggal_lahir" label="Tanggal Lahir" :value="old('tanggal_lahir', $pelaporanIkp->tanggal_lahir ?? '')" id="tanggal"
                                    placeholder="Pilih Tanggal" required />

                                {{-- Kelompok Umur --}}
                                <x-form.select name="kelompok_umur" label="Kelompok Umur" :options="config('units.umur')"
                                    :selected="old('kelompok_umur', $pelaporanIkp->kelompok_umur ?? '')" required />

                                {{-- Jenis Kelamin --}}
                                <x-form.select name="jenis_kelamin" label="Jenis Kelamin" :options="config('units.jenis_kelamin')"
                                    :selected="old('jenis_kelamin', $pelaporanIkp->jenis_kelamin ?? '')" required />

                                {{-- Penanggung Jawab --}}
                                <x-form.select name="penanggung_jawab" label="Penanggung Jawab" :options="config('units.penanggung_jawab')"
                                    :selected="old('penanggung_jawab', $pelaporanIkp->penanggung_jawab ?? '')" required />

                                {{-- Tanggal Masuk RS --}}
                                <x-form.input name="tanggal_masuk_rs" label="Tanggal Masuk RS" :value="old('tanggal_masuk_rs', $pelaporanIkp->tanggal_masuk_rs ?? '')"
                                    id="tanggal" placeholder="Pilih Tanggal" required />

                                {{-- Rincian Kejadian --}}
                                <x-form.input name="rincian_kejadian" label="Rincian Kejadian" :value="old('rincian_kejadian', $pelaporanIkp->rincian_kejadian ?? '')"
                                    required />

                                {{-- Tanggal Kejadian --}}
                                <x-form.input name="tanggal_kejadian" label="Tanggal Kejadian" :value="old('tanggal_kejadian', $pelaporanIkp->tanggal_kejadian ?? '')"
                                    id="tanggal" placeholder="Pilih Tanggal" required />

                                {{-- Waktu Kejadian --}}
                                <x-form.input type="time" name="waktu_kejadian" label="Waktu Kejadian" :value="old('waktu_kejadian', $pelaporanIkp->waktu_kejadian ?? '')"
                                    required />

                                {{-- Insiden --}}
                                <x-form.input name="insiden" label="Insiden" :value="old('insiden', $pelaporanIkp->insiden ?? '')" required />

                                {{-- Kronologis Kejadian --}}
                                <x-form.input name="kronologis_kejadian" label="Kronologis Kejadian" :value="old('kronologis_kejadian', $pelaporanIkp->kronologis_kejadian ?? '')"
                                    required />

                                {{-- Jenis Kejadian --}}
                                <x-form.select name="jenis_kejadian" label="Jenis Kejadian" :options="config('units.jenis_kejadian')"
                                    :selected="old('jenis_kejadian', $pelaporanIkp->jenis_kejadian ?? '')" required />

                                {{-- Orang Pertama Yang Melaporkan Insiden --}}
                                <x-form.input name="orang_pelapor" label="Orang Pertama Yang Melaporkan Insiden"
                                    :value="old('orang_pelapor', $pelaporanIkp->orang_pelapor ?? '')" required />

                                {{-- Insiden Terjadi Pada --}}
                                <x-form.select name="jenis_insiden" label="Insiden Terjadi Pada" :options="config('units.jenis_insiden')"
                                    :selected="old('jenis_insiden', $pelaporanIkp->jenis_insiden ?? '')" required />

                                {{-- Insiden Menyangkut Pasien --}}
                                <x-form.select name="insiden_pasien" label="Insiden Menyangkut Pasien" :options="config('units.insiden_pasien')"
                                    :selected="old('insiden_pasien', $pelaporanIkp->insiden_pasien ?? '')" required />

                                {{-- Lokasi Insiden --}}
                                <x-form.input name="lokasi_insiden" label="Lokasi Insiden" :value="old('lokasi_insiden', $pelaporanIkp->lokasi_insiden ?? '')" required />

                                {{-- Kejadian Terjadi Pada Pasien --}}
                                <x-form.select name="jenis_spesialisasi_pasien" label="Kejadian Terjadi Pada Pasien"
                                    :options="config('units.jenis_spesialisasi_pasien')" :selected="old(
                                        'jenis_spesialisasi_pasien',
                                        $pelaporanIkp->jenis_spesialisasi_pasien ?? '',
                                    )" required />

                                {{-- Unit Terkait Yang Berhubungan Dengan Insiden --}}
                                <x-form.input name="unit_terkait" label="Unit Terkait Yang Berhubungan Dengan Insiden"
                                    :value="old('unit_terkait', $pelaporanIkp->unit_terkait ?? '')" required />

                                {{-- Akibat Insiden Terhadap Pasien --}}
                                <x-form.select name="akibat_insiden" label="Akibat Insiden Terhadap Pasien"
                                    :options="config('units.akibat_insiden')" :selected="old('akibat_insiden', $pelaporanIkp->akibat_insiden ?? '')" required />

                                {{-- Tindakan Yang Dilakukan Segera Setelah Kejadian Dan Hasilnya --}}
                                <x-form.input name="tindakan_yang_dilakukan"
                                    label="Tindakan Yang Dilakukan Segera Setelah Kejadian Dan Hasilnya" :value="old(
                                        'tindakan_yang_dilakukan',
                                        $pelaporanIkp->tindakan_yang_dilakukan ?? '',
                                    )"
                                    required />

                                {{-- Tindakan Dilakukan Oleh --}}
                                <x-form.select name="tindakan_dilakukan_oleh" label="Tindakan Dilakukan Oleh"
                                    :options="config('units.tindakan_dilakukan_oleh')" :selected="old(
                                        'tindakan_dilakukan_oleh',
                                        $pelaporanIkp->tindakan_dilakukan_oleh ?? '',
                                    )" required />

                                {{-- Apakah Kejadian Yang Sama Pernah Terjadi Di Unit Kerja Lain --}}
                                <x-form.select name="kejadian_serupa"
                                    label="Apakah Kejadian Yang Sama Pernah Terjadi Di Unit Kerja Lain" :options="config('units.kejadian_serupa')"
                                    :selected="old('kejadian_serupa', $pelaporanIkp->kejadian_serupa ?? '')" required />

                                {{-- Grading Risiko Kejadian --}}
                                <x-form.select name="grading_risiko" label="Grading Risiko Kejadian" :options="config('units.grading_risiko')"
                                    :selected="old('grading_risiko', $pelaporanIkp->grading_risiko ?? '')" required />
                            </div>

                            <div class="mt-6">
                                <x-button.submit>Ubah</x-button.submit>
                                <x-button.cancel type="button" class="ml-2 bg-gray-200"
                                    onclick="window.location='{{ route('komite-mutu.pelaporan-ikp.index') }}'">
                                    Batal
                                </x-button.cancel>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/file-upload.js') }}"></script>
@endpush
