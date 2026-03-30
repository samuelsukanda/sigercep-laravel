{{-- Rekap Section --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

    {{-- Rekap Status Approval --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
        <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
            <h6 class="mb-0 font-bold text-lg">Rekap Status Approval</h6>
        </div>
        <div class="px-5 py-3">
            @php
                $allApprovalStatus = ['Approved', 'Rejected', 'Need Clarification'];
                $approvalCollection = $approvalRecap ?? collect();
            @endphp

            @foreach ($allApprovalStatus as $status)
                <div class="flex justify-between items-center py-2.5 border-b border-gray-100 last:border-0">
                    <span class="text-sm text-gray-600">{{ $status }}</span>
                    <span class="text-sm font-semibold text-gray-800 tabular-nums">
                        {{ $approvalCollection->get($status, 0) }}
                    </span>
                </div>
            @endforeach

            {{-- Total seluruh tiket --}}
            <div class="flex justify-between items-center py-2.5 mt-2 pt-2">
                <span class="text-sm font-bold text-gray-800">Total Tiket Masuk</span>
                <span class="text-sm font-bold text-gray-900">{{ $totalTickets }}</span>
            </div>
        </div>
    </div>

    {{-- Rekap Kategori --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
        <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
            <h6 class="mb-0 font-bold text-lg">Rekap Kategori</h6>
        </div>
        <div class="px-5 py-3">
            @php
                $allCategories = ['Hardware', 'Printer', 'Jaringan', 'Software', 'SIMRS'];
                $categoryData = $categoryRecap ?? [];
            @endphp
            @foreach ($allCategories as $cat)
                <div class="flex justify-between items-center py-2.5 border-b border-gray-100 last:border-0">
                    <span class="text-sm text-gray-600">{{ $cat }}</span>
                    <span class="text-sm font-semibold text-gray-800 tabular-nums">
                        {{ $categoryData[$cat] ?? 0 }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Rekap Status --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
            <h6 class="mb-0 font-bold text-lg">Rekap Status Tiket</h6>
        </div>
        <div class="px-5 py-3">
            @php
                $allStatuses = [
                    'Open' => ['color' => 'text-cyan-600', 'bg' => 'bg-cyan-50'],
                    'In Progress' => ['color' => 'text-blue-600', 'bg' => 'bg-blue-50'],
                    'Closed' => ['color' => 'text-gray-500', 'bg' => 'bg-gray-100'],
                    'Resolved' => ['color' => 'text-green-600', 'bg' => 'bg-green-50'],
                    'Rata-rata Penyelesaian' => [
                        'color' => 'text-purple-600',
                        'bg' => 'bg-purple-50',
                        'value' => number_format($avgResolution, 2) . ' Hari',
                    ],
                ];
                $statusData = $statusRecap ?? [];
            @endphp
            @foreach ($allStatuses as $st => $style)
                <div class="flex justify-between items-center py-2.5 border-b border-gray-100 last:border-0">
                    <span class="text-sm text-gray-600">{{ $st }}</span>
                    <span class="text-sm font-semibold text-gray-800 tabular-nums">
                        @if ($st === 'Rata-rata Penyelesaian')
                            {{ $style['value'] }}
                        @else
                            {{ $statusData[$st] ?? 0 }}
                        @endif
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>
