{{-- Informasi Tiket --}}
<div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl mb-4">

    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
        <h6 class="mb-0 font-bold text-lg">Informasi Tiket</h6>
    </div>
    <div class="flex-auto p-6">
        <div class="flex flex-wrap -mx-3">

            <div class="w-full md:w-1/2 xl:w-1/3 px-3">
                <label class="block  text-sm font-semibold" style="color: #7664E4 !important;">No.
                    Tiket</label>
                <p class="text-slate-600">{{ $ticket->ticket_number }}</p>
            </div>

            <div class="w-full md:w-1/2 xl:w-1/3 px-3">
                <label class="block  text-sm font-semibold" style="color: #7664E4 !important;">Nama
                    Pelapor</label>
                <p class="text-slate-600">{{ ucwords(str_replace('.', ' ', $ticket->name)) }}</p>
            </div>

            <div class="w-full md:w-1/2 xl:w-1/3 px-3">
                <label class="block  text-sm font-semibold" style="color: #7664E4 !important;">Divisi</label>
                <p class="text-slate-600">{{ $ticket->unit_name }}</p>
            </div>

            <div class="w-full md:w-1/2 xl:w-1/3 px-3">
                <label class="block  text-sm font-semibold" style="color: #7664E4 !important;">Tanggal/Jam</label>
                <p class="text-slate-600">
                    {{ \Carbon\Carbon::parse($ticket->created_at)->translatedFormat('d F Y H:i') }}
                </p>
            </div>

            <div class="w-full md:w-1/2 xl:w-1/3 px-3">
                <label class="block  text-sm font-semibold" style="color: #7664E4 !important;">Status
                    Tiket</label>
                <p class="text-slate-600">{{ $ticket->status ?? '-' }}</p>
            </div>

            <div class="w-full px-3">
                <label class="block  text-sm font-semibold" style="color: #7664E4 !important;">Deskripsi</label>
                <p class="text-slate-600">{{ $ticket->description }}</p>
            </div>

            <div class="w-full px-3" x-data="previewModal()">

                <label class="block text-sm font-semibold" style="color: #7664E4;">
                    Lampiran Pendukung
                </label>

                @if ($ticket->attachment && count($ticket->attachment) > 0)

                    {{-- GRID --}}
                    <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:8px; margin-top:10px;">

                        @foreach ($ticket->attachment as $file)
                            @php
                                $url = asset('storage/' . $file);
                                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                            @endphp

                            <div @click="openModal('{{ $url }}', '{{ $ext }}')"
                                style="position:relative; width:100%; padding-top:100%; cursor:pointer; overflow:hidden; border-radius:8px; border:1px solid #e5e7eb;">

                                @if ($isImage)
                                    <img src="{{ $url }}"
                                        style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover;" />
                                @else
                                    <div
                                        style="position:absolute; top:0; left:0; width:100%; height:100%;
                                                        display:flex; flex-direction:column; align-items:center; justify-content:center;
                                                        background:#f3f4f6; font-size:12px; color:#555;">
                                        📄 {{ $ext }}
                                    </div>
                                @endif

                            </div>
                        @endforeach

                    </div>
                @else
                    <p class="mt-2 text-sm text-slate-600">Tidak ada lampiran pendukung</p>
                @endif

                {{-- MODAL --}}
                <div x-show="show" x-cloak x-transition.opacity @click.self="closeModal()"
                    style="
                    position: fixed;
                    inset: 0;
                    background: rgba(0,0,0,0.9);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 9999;
                    padding: 20px;
                    ">

                    <div
                        style="
                        position: relative;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        width: 100%;
                        height: 100%;
                        ">

                        {{-- CLOSE BUTTON --}}
                        <button @click="closeModal()"
                            style="
                            position: absolute;
                            top: 20px;
                            right: 20px;
                            background: rgba(255,255,255,0.9);
                            border-radius: 50%;
                            width: 40px;
                            height: 40px;
                            border: none;
                            cursor: pointer;
                            font-size: 18px;
                            font-weight: bold;
                            z-index: 10;
                            ">
                            ✕
                        </button>

                        {{-- IMAGE --}}
                        <img x-show="isImage" :src="fileUrl"
                            style="
                            max-width: 90%;
                            max-height: 85vh;
                            object-fit: contain;
                            border-radius: 10px;
                            display: block;
                            margin: auto;
                            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
                            ">

                        {{-- NON IMAGE --}}
                        <iframe x-show="!isImage" :src="fileUrl"
                            style="
                            width: 90vw;
                            height: 85vh;
                            background: white;
                            border-radius: 10px;
                            border: none;
                            ">
                        </iframe>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
