<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <!-- Nama -->
    <div>
        <label class="block text-sm font-semibold mb-2 text-slate-700">Nama</label>
        <input type="text" name="nama" value="{{ old('nama', $komplain->nama ?? '') }}" required
            class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500" />
    </div>

    <!-- Unit -->
    <div>
        <label class="block text-sm font-semibold mb-2 text-slate-700">Unit</label>
        <input type="text" name="unit" value="{{ old('unit', $komplain->unit ?? '') }}" required
            class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500" />
    </div>

    <!-- Tujuan Unit -->
    <div>
        <label class="block text-sm font-semibold mb-2 text-slate-700">Tujuan Unit</label>
        <input type="text" name="tujuan_unit" value="{{ old('tujuan_unit', $komplain->tujuan_unit ?? '') }}" required
            class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500" />
    </div>

    <!-- Tanggal -->
    <div>
        <label class="block text-sm font-semibold mb-2 text-slate-700">Tanggal</label>
        <input type="date" name="tanggal" value="{{ old('tanggal', $komplain->tanggal ?? '') }}" required
            class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500" />
    </div>

    <!-- Kendala -->
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold mb-2 text-slate-700">Kendala</label>
        <textarea name="kendala" rows="4" required
            class="form-textarea w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500">{{ old('kendala', $komplain->kendala ?? '') }}</textarea>
    </div>

    <!-- Foto -->
    <div>
        <label class="block text-sm font-semibold mb-2 text-slate-700">Foto</label>
        <input type="file" name="foto"
            class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500" />
        @if(isset($komplain) && $komplain->foto)
            <img src="{{ asset('storage/' . $komplain->foto) }}" alt="Foto"
                class="mt-2 h-24 rounded shadow-md object-cover border border-gray-200" />
        @endif
    </div>

    <!-- Status -->
    <div>
        <label class="block text-sm font-semibold mb-2 text-slate-700">Status</label>
        <select name="status"
            class="form-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500">
            <option value="">Pilih Status</option>
            @foreach(['Pending', 'On Progress', 'Done'] as $status)
                <option value="{{ $status }}"
                    {{ old('status', $komplain->status ?? '') === $status ? 'selected' : '' }}>
                    {{ $status }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Keterangan -->
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold mb-2 text-slate-700">Keterangan</label>
        <input type="text" name="keterangan" value="{{ old('keterangan', $komplain->keterangan ?? '') }}"
            class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500" />
    </div>
</div>
