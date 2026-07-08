@extends('layouts.app')

@section('title', 'SIGERCEP - Edit Daftar Risiko')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Edit Daftar Risiko</h6>
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
                                <x-form.input name="unit" label="Unit" :value="old('unit', $risiko->unit)" placeholder="Contoh: IGD"
                                    required />
                                <x-form.input type="number" name="no_urut" label="No Urut" :value="old('no_urut', $risiko->no_urut)" required />

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

                                <div class="md:col-span-3 flex gap-6 mt-1 mb-2">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="efektif" value="1"
                                            {{ old('efektif', $risiko->efektif) ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="text-sm font-semibold text-slate-700">Efektif</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="tidak_efektif" value="1"
                                            {{ old('tidak_efektif', $risiko->tidak_efektif) ? 'checked' : '' }}
                                            class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                        <span class="text-sm font-semibold text-slate-700">Tidak Efektif</span>
                                    </label>
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
                                        label="Analisis Nilai (P x D)" id="anilai" :value="old('analisis_nilai', (float) $risiko->analisis_nilai)" readonly />
                                </div>

                                <div>
                                    <x-form.input type="number" step="any" name="analisis_bobot" label="Analisis Bobot"
                                        :value="old('analisis_bobot', (float) $risiko->analisis_bobot)" />
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
                                Target & Mitigasi</h6>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <div class="md:col-span-3">
                                    <x-form.input name="target_waktu" label="Target Waktu" :value="old('target_waktu', $risiko->target_waktu)"
                                        placeholder="Contoh: Maret 2026" />
                                </div>

                                <div>
                                    <x-form.input type="number" step="any" name="mitigasi_p" label="Mitigasi (P)"
                                        id="mp" :value="old('mitigasi_p', (float) $risiko->mitigasi_p)" oninput="calcMitigasi()" />
                                </div>
                                <div>
                                    <x-form.input type="number" step="any" name="mitigasi_d" label="Mitigasi (D)"
                                        id="md" :value="old('mitigasi_d', (float) $risiko->mitigasi_d)" oninput="calcMitigasi()" />
                                </div>
                                <div>
                                    <x-form.input type="number" step="any" name="mitigasi_nilai"
                                        label="Mitigasi Nilai (P x D)" id="mnilai" :value="old('mitigasi_nilai', (float) $risiko->mitigasi_nilai)" readonly />
                                </div>

                                <div>
                                    <x-form.input type="number" step="any" name="mitigasi_bobot"
                                        label="Mitigasi Bobot" :value="old('mitigasi_bobot', (float) $risiko->mitigasi_bobot)" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-form.select name="mitigasi_tingkat" label="Tingkat Risiko Mitigasi"
                                        :options="[
                                            'Sangat Rendah' => 'Sangat Rendah',
                                            'Rendah' => 'Rendah',
                                            'Sedang' => 'Sedang',
                                            'Tinggi' => 'Tinggi',
                                            'Sangat Tinggi' => 'Sangat Tinggi',
                                        ]" :selected="old('mitigasi_tingkat', $risiko->mitigasi_tingkat)" id="mtingkat" />
                                </div>
                            </div>

                            <div class="mt-6 border-t border-gray-100 pt-6">
                                <x-button.submit type="submit">Simpan Perubahan</x-button.submit>
                                <a href="{{ route('komite-mutu.manajemen-risiko.index') }}"
                                    class="ml-2 inline-block px-6 py-2.5 font-bold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                                    Batal
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
        function getTingkatRisiko(nilai) {
            if (!nilai) return '';
            if (nilai <= 4) return 'Sangat Rendah';
            if (nilai <= 9) return 'Rendah';
            if (nilai <= 14) return 'Sedang';
            if (nilai <= 19) return 'Tinggi';
            return 'Sangat Tinggi';
        }

        function calcAnalisis() {
            const p = parseFloat(document.getElementById('ap').value) || 0;
            const d = parseFloat(document.getElementById('ad').value) || 0;
            if (p && d) {
                const nilai = p * d;
                document.getElementById('anilai').value = nilai.toFixed(2);
                document.getElementById('atingkat').value = getTingkatRisiko(nilai);
            }
        }

        function calcMitigasi() {
            const p = parseFloat(document.getElementById('mp').value) || 0;
            const d = parseFloat(document.getElementById('md').value) || 0;
            if (p && d) {
                const nilai = p * d;
                document.getElementById('mnilai').value = nilai.toFixed(2);
                document.getElementById('mtingkat').value = getTingkatRisiko(nilai);
            }
        }
    </script>
@endpush
