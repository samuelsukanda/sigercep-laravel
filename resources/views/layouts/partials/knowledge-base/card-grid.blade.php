{{-- Grid Artikel --}}
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px; margin-bottom: 24px;">
    @forelse($knowledgeBases as $kb)
        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); display: flex; flex-direction: column; overflow: hidden; transition: box-shadow 0.2s;">

            {{-- Card Body --}}
            <div style="padding: 20px; display: flex; flex-direction: column; flex: 1;">

                {{-- Top: Kategori + Views --}}
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <span style="display: inline-block; background-color: #eff6ff; color: #3b82f6; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; white-space: nowrap;">
                        {{ $kb->category ?? 'Umum' }}
                    </span>
                    <span style="display: flex; align-items: center; font-size: 12px; color: #94a3b8;">
                        <i class="fas fa-eye" style="margin-right: 4px;"></i>
                        {{ number_format($kb->views) }}
                    </span>
                </div>

                {{-- Title --}}
                <a href="{{ route('knowledge-base.show', $kb) }}" style="text-decoration: none;">
                    <h5 style="font-size: 15px; font-weight: 700; color: #1e293b; margin: 0 0 8px 0; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $kb->title }}
                    </h5>
                </a>

                {{-- Description --}}
                <p style="font-size: 13px; color: #64748b; margin: 0 0 16px 0; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; flex: 1;">
                    {{ $kb->description ?: Str::limit(strip_tags($kb->content), 100) }}
                </p>

                {{-- Footer --}}
                <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 12px; border-top: 1px solid #f1f5f9;">

                    {{-- Author + Date --}}
                    <div style="display: flex; align-items: center; font-size: 12px; color: #94a3b8; overflow: hidden; min-width: 0;">
                        <i class="fas fa-user" style="margin-right: 5px; flex-shrink: 0;"></i>
                        <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 80px; margin-right: 6px;">
                            {{ $kb->author?->name ?? 'Unknown' }}
                        </span>
                        <span style="margin-right: 6px; color: #cbd5e1; flex-shrink: 0;">·</span>
                        <span style="white-space: nowrap; flex-shrink: 0;">{{ $kb->created_at->format('d M Y') }}</span>
                    </div>

                    {{-- Actions --}}
                    <div style="display: flex; align-items: center; flex-shrink: 0; margin-left: 8px;">
                        @if ($isIT)
                            <a href="{{ route('knowledge-base.edit', $kb) }}"
                                title="Edit"
                                style="color: #3b82f6; font-size: 14px; text-decoration: none; margin-left: 8px; transition: color 0.15s;"
                                onmouseover="this.style.color='#1d4ed8'"
                                onmouseout="this.style.color='#3b82f6'">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('knowledge-base.destroy', $kb) }}" method="POST" style="display: inline; margin-left: 8px;">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="delete-button"
                                    data-confirm="Apakah Anda yakin ingin menghapus artikel ini?"
                                    title="Hapus"
                                    style="background: none; border: none; cursor: pointer; color: #ef4444; font-size: 14px; padding: 0; transition: color 0.15s; outline: none;"
                                    onmouseover="this.style.color='#dc2626'"
                                    onmouseout="this.style.color='#ef4444'">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @if ($kb->status === 'draft')
                                <form action="{{ route('knowledge-base.publish', $kb) }}" method="POST" style="display: inline; margin-left: 8px;">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        title="Publish"
                                        style="background: none; border: none; cursor: pointer; color: #22c55e; font-size: 14px; padding: 0; transition: color 0.15s;"
                                        onmouseover="this.style.color='#16a34a'"
                                        onmouseout="this.style.color='#22c55e'">
                                        <i class="fas fa-upload"></i>
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('knowledge-base.show', $kb) }}"
                                title="Baca"
                                style="color: #3b82f6; font-size: 14px; text-decoration: none; margin-left: 8px;"
                                onmouseover="this.style.color='#1d4ed8'"
                                onmouseout="this.style.color='#3b82f6'">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Draft badge di bawah card --}}
            @if ($kb->status === 'draft')
                <div style="padding: 6px 20px; background-color: #fffbeb; border-top: 1px solid #fde68a; font-size: 11px; font-weight: 600; color: #d97706; text-align: center;">
                    <i class="fas fa-file-alt" style="margin-right: 5px;"></i>Draft — belum dipublikasikan
                </div>
            @endif
        </div>
    @empty
        <div style="grid-column: 1 / -1;">
            <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 48px; text-align: center;">
                <i class="fas fa-book-open" style="font-size: 40px; color: #cbd5e1; display: block; margin-bottom: 12px;"></i>
                <p style="color: #94a3b8; font-size: 14px; margin: 0;">Tidak ada artikel ditemukan.</p>
            </div>
        </div>
    @endforelse
</div>

<div style="margin-top: 24px;">
    {{ $knowledgeBases->links() }}
</div>
