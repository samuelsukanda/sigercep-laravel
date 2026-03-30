{{-- Riwayat Penanganan --}}
@if (
    $ticket->approval &&
        $ticket->approval->approval_status == 'Need Clarification' &&
        $ticket->updates &&
        $ticket->updates->count())
    <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl mb-4">
        <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
            <h6 class="mb-0 font-bold text-lg">Riwayat Penanganan</h6>
        </div>
        <div class="flex-auto p-6">
            <div class="space-y-4">
                @foreach ($ticket->updates as $update)
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 mb-4">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="px-2 py-1 text-xs font-semibold bg-gray-300 rounded">
                                {{ $update->created_at->format('d-m-Y H:i') }}
                            </span>

                            @php
                                $status = strtolower(trim($update->status));

                                $colors = [
                                    'in progress' => '#3b82f6',
                                    'done' => '#22c55e',
                                    'closed' => '#9ca3af',
                                ];

                                $bg = $colors[$status] ?? '#d1d5db';
                            @endphp

                            <span class="px-2 py-1 text-xs font-semibold rounded"
                                style="background-color: {{ $bg }}; color: white;">
                                {{ $update->status }}
                            </span>
                        </div>

                        <p class="text-sm text-slate-600">
                            {{ $update->note }}
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                <a href="{{ route('helpdesk.index') }}"
                    class="inline-block px-6 py-2 text-xs font-semibold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                    Kembali
                </a>
            </div>

        </div>
    </div>
@endif
