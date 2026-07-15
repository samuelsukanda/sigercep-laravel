@extends('layouts.app')

@section('title', 'SIGERCEP - Edit Manajemen Risiko')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Edit Manajemen Risiko</h6>
                    </div>
                    <div class="flex-auto p-6">
                        @if ($errors->any())
                            <div
                                class="relative text-sm w-full p-4 mb-6 text-white border border-transparent rounded-lg bg-gradient-to-tl from-red-600 to-orange-600 shadow-md">
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="form" action="{{ route('komite-mutu.manajemen-risiko.update', $risiko->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')

                            <h6 class="text-sm font-bold uppercase text-slate-500 mb-4 border-b border-gray-100 pb-2">
                                Informasi Utama</h6>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <x-form.input name="unit" label="Unit" :value="old('unit', $risiko->unit)" required />

                                <div class="md:col-span-2">
                                    <x-form.textarea name="risiko" label="Risiko"
                                        required>{{ old('risiko', $risiko->risiko) }}</x-form.textarea>
                                </div>

                                <x-form.input name="kode_risiko" label="Kode Risiko" :value="old('kode_risiko', $risiko->kode_risiko)" />
                                <x-form.input name="sumber_risiko" label="Sumber Risiko" :value="old('sumber_risiko', $risiko->sumber_risiko)"
                                    placeholder="Internal / Eksternal" />

                                <div class="md:col-span-2">
                                    <x-form.textarea name="sebab"
                                        label="Sebab">{{ old('sebab', $risiko->sebab) }}</x-form.textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <x-form.textarea name="dampak"
                                        label="Dampak">{{ old('dampak', $risiko->dampak) }}</x-form.textarea>
                                </div>

                                <x-form.input name="c_uc" label="C / UC" :value="old('c_uc', $risiko->c_uc)" />
                            </div>

                            <h6 class="text-sm font-bold uppercase text-slate-500 mb-4 mt-6 border-b border-gray-100 pb-2">
                                Pengendalian & Analisis Risiko</h6>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <div class="md:col-span-3">
                                    <x-form.textarea name="pengendalian"
                                        label="Pengendalian Yang Ada">{{ old('pengendalian', $risiko->pengendalian) }}</x-form.textarea>
                                </div>

                                <div class="md:col-span-3 mt-1 mb-2">
                                    <p class="text-xs font-semibold text-slate-500 uppercase mb-2">Status Pengendalian</p>
                                    <div class="flex flex-wrap gap-4">
                                        <label
                                            class="flex items-center mr-2 gap-4 px-5 py-3 bg-white border border-gray-200 rounded-lg cursor-pointer hover:bg-emerald-50 transition-colors shadow-sm">
                                            <input type="checkbox" name="efektif" value="1"
                                                {{ old('efektif', $risiko->efektif) ? 'checked' : '' }}
                                                class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 cursor-pointer">
                                            <span class="text-sm font-bold text-slate-700 select-none ml-1">Efektif</span>
                                        </label>
                                        <label
                                            class="flex items-center gap-4 px-5 py-3 bg-white border border-gray-200 rounded-lg cursor-pointer hover:bg-red-50 transition-colors shadow-sm">
                                            <input type="checkbox" name="tidak_efektif" value="1"
                                                {{ old('tidak_efektif', $risiko->tidak_efektif) ? 'checked' : '' }}
                                                class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500 cursor-pointer">
                                            <span class="text-sm font-bold text-slate-700 select-none ml-1">Tidak
                                                Efektif</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <x-form.input type="number" step="any" name="analisis_p" label="Analisis (P)"
                                        id="ap" :value="old('analisis_p', (float) $risiko->analisis_p)" oninput="calcAnalisis()" />
                                </div>
                                <div>
                                    <x-form.input type="number" step="any" name="analisis_d" label="Analisis (D)"
                                        id="ad" :value="old('analisis_d', (float) $risiko->analisis_d)" oninput="calcAnalisis()" />
                                </div>
                                <div>
                                    <x-form.input type="number" step="any" name="analisis_nilai"
                                        label="Analisis Nilai (P x D x Bobot)" id="anilai" :value="old('analisis_nilai', (float) $risiko->analisis_nilai)" readonly />
                                </div>

                                <div>
                                    <x-form.input type="number" step="any" name="analisis_bobot" label="Analisis Bobot"
                                        id="abobot" :value="old('analisis_bobot', (float) $risiko->analisis_bobot)" oninput="calcAnalisis()" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-form.select name="analisis_tingkat" label="Tingkat Risiko Analisis" :options="[
                                        'Sangat Rendah' => 'Sangat Rendah',
                                        'Rendah' => 'Rendah',
                                        'Sedang' => 'Sedang',
                                        'Tinggi' => 'Tinggi',
                                        'Sangat Tinggi' => 'Sangat Tinggi',
                                    ]"
                                        :selected="old('analisis_tingkat', $risiko->analisis_tingkat)" id="atingkat" />
                                </div>
                            </div>

                            <h6 class="text-sm font-bold uppercase text-slate-500 mb-4 mt-6 border-b border-gray-100 pb-2">
                                Target Waktu</h6>
                            <div class="grid grid-cols-1 mb-6">
                                <x-form.input name="target_waktu" label="Target Waktu" :value="old('target_waktu', $risiko->target_waktu)" />
                            </div>

                            @foreach([1, 2, 3, 4] as $tw)
                            <h6 class="text-sm font-bold uppercase text-slate-500 mb-4 mt-6 border-b border-gray-100 pb-2">
                                Tingkat Mitigasi TW {{ $tw }}</h6>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <div>
                                    <x-form.input type="number" step="any" name="mitigasi_tw{{ $tw }}_p" label="Mitigasi (P)"
                                        id="mtw{{ $tw }}p" :value="old('mitigasi_tw'.$tw.'_p', (float) $risiko->{'mitigasi_tw'.$tw.'_p'})" oninput="calcMitigasiTW({{ $tw }})" />
                                </div>
                                <div>
                                    <x-form.input type="number" step="any" name="mitigasi_tw{{ $tw }}_d" label="Mitigasi (D)"
                                        id="mtw{{ $tw }}d" :value="old('mitigasi_tw'.$tw.'_d', (float) $risiko->{'mitigasi_tw'.$tw.'_d'})" oninput="calcMitigasiTW({{ $tw }})" />
                                </div>
                                <div>
                                    <x-form.input type="number" step="any" name="mitigasi_tw{{ $tw }}_nilai"
                                        label="Mitigasi Nilai (P x D x Bobot)" id="mtw{{ $tw }}nilai" :value="old('mitigasi_tw'.$tw.'_nilai', (float) $risiko->{'mitigasi_tw'.$tw.'_nilai'})" readonly />
                                </div>
                                <div>
                                    <x-form.input type="number" step="any" name="mitigasi_tw{{ $tw }}_bobot"
                                        label="Mitigasi Bobot" id="mtw{{ $tw }}bobot" :value="old('mitigasi_tw'.$tw.'_bobot', (float) $risiko->{'mitigasi_tw'.$tw.'_bobot'})" oninput="calcMitigasiTW({{ $tw }})" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-form.select name="mitigasi_tw{{ $tw }}_tingkat" label="Tingkat Risiko Mitigasi"
                                        :options="[
                                            'Sangat Rendah' => 'Sangat Rendah',
                                            'Rendah' => 'Rendah',
                                            'Sedang' => 'Sedang',
                                            'Tinggi' => 'Tinggi',
                                            'Sangat Tinggi' => 'Sangat Tinggi',
                                        ]" :selected="old('mitigasi_tw'.$tw.'_tingkat', $risiko->{'mitigasi_tw'.$tw.'_tingkat'})" id="mtw{{ $tw }}tingkat" />
                                </div>
                            </div>
                            @endforeach

                            <div class="mt-6">
                                <x-button.submit type="submit">Simpan</x-button.submit>
                                <a href="{{ route('komite-mutu.manajemen-risiko.index') }}"
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
            $('.select2').select2({
                width: '100%'
            });
        });

        function getTingkatRisiko(nilai) {
            if (isNaN(nilai) || nilai === '') return '';
            if (nilai <= 4) return 'Sangat Rendah';
            if (nilai <= 9) return 'Rendah';
            if (nilai <= 14) return 'Sedang';
            if (nilai <= 19) return 'Tinggi';
            return 'Sangat Tinggi';
        }

        function calcAnalisis() {
            const p = parseFloat(document.getElementById('analisis_p').value);
            const d = parseFloat(document.getElementById('analisis_d').value);
            const bobot = parseFloat(document.getElementById('analisis_bobot').value);
            if (!isNaN(p) && !isNaN(d) && !isNaN(bobot)) {
                const nilai = p * d * bobot;
                document.getElementById('analisis_nilai').value = nilai.toFixed(2);
                $('#analisis_tingkat').val(getTingkatRisiko(nilai)).trigger('change');
            } else {
                document.getElementById('analisis_nilai').value = '';
                $('#analisis_tingkat').val('').trigger('change');
            }
        }

        function calcMitigasiTW(tw) {
            const p = parseFloat(document.getElementById('mitigasi_tw' + tw + '_p').value);
            const d = parseFloat(document.getElementById('mitigasi_tw' + tw + '_d').value);
            const bobot = parseFloat(document.getElementById('mitigasi_tw' + tw + '_bobot').value);
            if (!isNaN(p) && !isNaN(d) && !isNaN(bobot)) {
                const nilai = p * d * bobot;
                document.getElementById('mitigasi_tw' + tw + '_nilai').value = nilai.toFixed(2);
                $('#mitigasi_tw' + tw + '_tingkat').val(getTingkatRisiko(nilai)).trigger('change');
            } else {
                document.getElementById('mitigasi_tw' + tw + '_nilai').value = '';
                $('#mitigasi_tw' + tw + '_tingkat').val('').trigger('change');
            }
        }
    </script>
@endpush
