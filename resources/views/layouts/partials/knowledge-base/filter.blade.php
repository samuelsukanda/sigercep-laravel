{{-- Filter Section --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
    <div class="px-5 py-4">
        <form method="GET" action="{{ route('knowledge-base.index') }}" id="filterForm">
            @if (request('tab'))
                <input type="hidden" name="tab" value="{{ request('tab') }}">
            @endif
            <div class="flex flex-wrap gap-3 items-end filter-wrap">

                {{-- Cari Artikel --}}
                <div class="flex flex-col mr-1 filter-item" style="min-width:200px; flex:1 1 200px;">
                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Cari Artikel</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Cari judul atau isi artikel...">
                </div>

                {{-- Kategori --}}
                <div class="flex flex-col mr-1 filter-item" style="min-width:148px; flex:1 1 148px; max-width:220px;">
                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Kategori</label>
                    <select name="category"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                        <option value="">-- Semua Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-end flex-1 justify-between gap-2 filter-action">
                    <div class="flex items-end gap-1">
                        {{-- Button Cari --}}
                        <button type="submit"
                            class="mr-1 inline-block px-4 py-2 mb-0 text-xs font-semibold text-center text-white uppercase align-middle transition-all rounded-lg shadow-md hover:shadow-xs active:opacity-85"
                            style="background-color: #7664E4 !important;">
                            <i class="fas fa-search text-sm leading-normal"></i>
                        </button>

                        {{-- Button Reset --}}
                        <a href="{{ route('knowledge-base.index', request('tab') ? ['tab' => request('tab')] : []) }}"
                            class="btn-reset inline-flex items-center justify-center h-9 px-4 text-xs font-semibold text-slate-700 uppercase rounded-lg shadow-md bg-gray-200 hover:shadow-sm active:opacity-85 transition-all">
                            Reset
                        </a>
                    </div>

                    {{-- Buat Artikel --}}
                    @if ($isIT)
                        <a href="{{ route('knowledge-base.create') }}"
                            class="inline-flex items-center gap-2 justify-center h-9 px-4 text-xs font-semibold text-white uppercase rounded-lg shadow-md hover:shadow-sm active:opacity-85 transition-all"
                            style="background-color: #7664E4 !important;">
                            Buat Artikel
                        </a>
                    @endif
                </div>

            </div>
        </form>

        {{-- Tabs --}}
        @if ($isIT)
            <div
                style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 16px; padding-top: 16px; border-top: 1px solid #f1f5f9; align-items: center;">

                {{-- Tab Published --}}
                <a href="{{ route('knowledge-base.index', ['tab' => 'published']) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-lg border transition-all"
                    @if ($tab === 'published') style="border-color: #3b82f6; color: #2563eb; background-color: #eff6ff;"
                    @else
                        style="border-color: #e2e8f0; color: #64748b; background-color: #ffffff;" @endif>
                    <i class="fas fa-globe" style="margin-right: 5px;"></i>Published
                    <span class="inline-flex items-center justify-center text-xs font-bold rounded-full"
                        style="display: inline-flex; align-items: center; justify-content: center; min-width:20px; height:20px; line-height: 1; padding: 0 5px; margin-left: 4px; {{ $tab === 'published' ? 'background-color: #3b82f6; color: #ffffff;' : 'background-color: #e2e8f0; color: #475569;' }}">
                        {{ $counts['published'] ?? 0 }}
                    </span>
                </a>

                {{-- Tab Draft --}}
                <a href="{{ route('knowledge-base.index', ['tab' => 'draft']) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-lg border transition-all"
                    @if ($tab === 'draft') style="border-color: #f59e0b; color: #d97706; background-color: #fffbeb;"
                    @else
                        style="border-color: #e2e8f0; color: #64748b; background-color: #ffffff;" @endif>
                    <i class="fas fa-file-alt" style="margin-right: 5px;"></i>Draft
                    @if (($counts['draft'] ?? 0) > 0)
                        <span class="inline-flex items-center justify-center text-xs font-bold rounded-full"
                            style="display: inline-flex; align-items: center; justify-content: center; min-width:20px; height:20px; line-height: 1; padding: 0 5px; margin-left: 4px; {{ $tab === 'draft' ? 'background-color: #f59e0b; color: #ffffff;' : 'background-color: #e2e8f0; color: #475569;' }}">
                            {{ $counts['draft'] }}
                        </span>
                    @endif
                </a>

                {{-- Tab Artikel Saya --}}
                <a href="{{ route('knowledge-base.index', ['tab' => 'mine']) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-lg border transition-all"
                    @if ($tab === 'mine') style="border-color: #8b5cf6; color: #7c3aed; background-color: #f5f3ff;"
                    @else
                        style="border-color: #e2e8f0; color: #64748b; background-color: #ffffff;" @endif>
                    <i class="fas fa-user" style="margin-right: 5px;"></i>Artikel Saya
                    <span class="inline-flex items-center justify-center text-xs font-bold rounded-full"
                        style="display: inline-flex; align-items: center; justify-content: center; min-width:20px; height:20px; line-height: 1; padding: 0 5px; margin-left: 4px; {{ $tab === 'mine' ? 'background-color: #8b5cf6; color: #ffffff;' : 'background-color: #e2e8f0; color: #475569;' }}">
                        {{ $counts['mine'] ?? 0 }}
                    </span>
                </a>
            </div>

            {{-- Alert info untuk tab draft --}}
            @if ($tab === 'draft')
                <div
                    style="margin-top: 10px; display: flex; align-items: center; gap: 8px; padding: 10px 14px; background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; font-size: 12px; color: #92400e;">
                    <i class="fas fa-info-circle"
                        style="color: #f59e0b; flex-shrink: 0; margin-right: 4px;"></i>
                    <span>
                        <strong>Draft</strong> tidak terlihat oleh pengguna biasa. Klik
                        <strong style="color: #16a34a;">Publish</strong> pada artikel untuk mempublikasikannya.
                    </span>
                </div>
            @endif
        @endif
    </div>
</div>
