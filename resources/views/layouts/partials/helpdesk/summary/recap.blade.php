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

    {{-- Tabel Rekap Tindakan Admin --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
        <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
            <h6 class="mb-0 font-bold text-lg">Rekap Tindakan Admin</h6>
        </div>
        <div class="pb-5 pt-2 border-gray-200 mt-2">
            <div class="overflow-x-auto px-4">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Admin</th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Approved</th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Rejected</th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Need
                                Clarification</th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($adminActionsRecap ?? [] as $adminName => $actions)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 font-medium text-gray-800">
                                    {{ ucwords(str_replace('.', ' ', $adminName)) }}</td>
                                <td class="px-3 py-2 text-center text-green-600 font-semibold">
                                    {{ $actions['Approved'] ?? 0 }}
                                </td>
                                <td class="px-3 py-2 text-center text-red-600 font-semibold">
                                    {{ $actions['Rejected'] ?? 0 }}
                                </td>
                                <td class="px-3 py-2 text-center text-yellow-600 font-semibold">
                                    {{ $actions['Need Clarification'] ?? 0 }}
                                </td>
                                <td class="px-3 py-2 text-center font-bold text-gray-800">
                                    {{ ($actions['Approved'] ?? 0) + ($actions['Rejected'] ?? 0) + ($actions['Need Clarification'] ?? 0) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-4 text-center text-gray-400">Belum ada tindakan admin
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if (!empty($adminActionsRecap))
                        <tfoot class="bg-gray-50 font-semibold">
                            <tr>
                                <td class="px-3 py-2 text-gray-700">Total</td>
                                <td class="px-3 py-2 text-center text-green-700">
                                    {{ collect($adminActionsRecap)->sum(fn($a) => $a['Approved'] ?? 0) }}
                                </td>
                                <td class="px-3 py-2 text-center text-red-700">
                                    {{ collect($adminActionsRecap)->sum(fn($a) => $a['Rejected'] ?? 0) }}
                                </td>
                                <td class="px-3 py-2 text-center text-yellow-700">
                                    {{ collect($adminActionsRecap)->sum(fn($a) => $a['Need Clarification'] ?? 0) }}
                                </td>
                                <td class="px-3 py-2 text-center text-gray-800">
                                    {{ collect($adminActionsRecap)->sum(fn($a) => ($a['Approved'] ?? 0) + ($a['Rejected'] ?? 0) + ($a['Need Clarification'] ?? 0)) }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
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
                    'Done' => ['color' => 'text-green-600', 'bg' => 'bg-green-50'],
                    'Rata-rata Penyelesaian' => [
                        'color' => 'text-purple-600',
                        'bg' => 'bg-purple-50',
                        'value' =>
                            number_format($avgResolutionDays, 2) .
                            ' Hari (± ' .
                            $hours .
                            ' jam ' .
                            $minutes .
                            ' menit)',
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
