@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Detail Pelaporan IKP</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nama Pasien --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nama Pasien</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->nama }}</p>
                            </div>

                            {{-- No. Rekam Medis --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">No. Rekam Medis</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->no_rm }}</p>
                            </div>

                            {{-- Tanggal Lahir --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tanggal Lahir</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->tanggal_lahir }}</p>
                            </div> 
                            
                            {{-- Kelompok Umur --}}  
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Kelompok Umur</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->kelompok_umur }}</p>
                            </div>
                            
                            {{-- Jenis Kelamin --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Jenis Kelamin</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->jenis_kelamin }}</p>
                            </div>

                            {{-- Penanggung Jawab --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Penanggung Jawab</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->penanggung_jawab }}</p>
                            </div>

                            {{-- Tanggal Masuk RS --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tanggal Masuk RS</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->tanggal_masuk_rs }}</p>
                            </div>

                            {{-- Rincian Kejadian --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Rincian Kejadian</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->rincian_kejadian }}</p>
                            </div>

                            {{-- Tanggal Kejadian --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tanggal Kejadian</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->tanggal_kejadian }}</p>
                            </div>

                            {{-- Waktu Kejadian --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Waktu Kejadian</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->waktu_kejadian }}</p>
                            </div>

                            {{-- Insiden --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Insiden</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->insiden }}</p>
                            </div>

                            {{-- Kronologis Kejadian --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Kronologis Kejadian</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->kronologis_kejadian }}</p>
                            </div>

                            {{-- Jenis Kejadian --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Jenis Kejadian</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->jenis_kejadian }}</p>
                            </div>

                            {{-- Orang Pertama Yang Melaporkan Insiden --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Orang Pertama Yang Melaporkan Insiden</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->orang_pelapor }}</p>
                            </div>

                            {{-- Insiden Terjadi Pada --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Insiden Terjadi Pada</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->jenis_insiden }}</p>
                            </div>

                            {{-- Insiden Menyangkut Pasien --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Insiden Menyangkut Pasien</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->insiden_pasien }}</p>
                            </div>

                            {{-- Lokasi Insiden --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Lokasi Insiden</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->lokasi_insiden }}</p>
                            </div>

                            {{-- Kejadian Terjadi Pada Pasien --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Kejadian Terjadi Pada Pasien</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->jenis_spesialisasi_pasien }}</p>
                            </div>

                            {{-- Unit Terkait Yang Berhubungan Dengan Insiden --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Unit Terkait Yang Berhubungan Dengan Insiden</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->unit_terkait }}</p>
                            </div>

                            {{-- Akibat Insiden Terhadap Pasien --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Akibat Insiden Terhadap Pasien</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->akibat_insiden }}</p>
                            </div>

                            {{-- Tindakan Yang Dilakukan Segera Setelah Kejadian Dan Hasilnya --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tindakan Yang Dilakukan Segera Setelah Kejadian Dan Hasilnya</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->tindakan_yang_dilakukan }}</p>
                            </div>

                            {{-- Tindakan Dilakukan Oleh --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tindakan Dilakukan Oleh</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->tindakan_dilakukan_oleh }}</p>
                            </div>

                            {{-- Apakah Kejadian Yang Sama Pernah Terjadi Di Unit Kerja Lain --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Apakah Kejadian Yang Sama Pernah Terjadi Di Unit Kerja Lain</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->kejadian_serupa }}</p>
                            </div>

                            {{-- Grading Risiko Kejadian --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Grading Risiko Kejadian</label>
                                <p class="text-slate-600">{{ $pelaporanIkp->grading_risiko }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('komite-mutu.pelaporan-ikp.index') }}"
                                class="inline-block px-6 py-2 text-xs font-semibold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
