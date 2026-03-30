{{-- Update Status Penanganan --}}
@php
    $allowedStatus = ['Need Clarification'];
@endphp

@if ($ticket->approval && in_array($ticket->approval->approval_status, $allowedStatus) && $ticket->status != 'Done')
    <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl mb-4">
        <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
            <h6 class="mb-0 font-bold text-lg">Update Status Penanganan</h6>
        </div>
        <div class="flex-auto p-6">
            <form id="form" action="{{ route('admin.helpdesk.update-status', $ticket->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Update Status --}}
                    <x-form.select name="status" label="Status" :options="config('units.approval_status')" :selected="old('status', $tickets->status ?? '')" required />

                    {{-- Catatan Penanganan --}}
                    <x-form.textarea name="note" label="Catatan Penanganan" :value="old('note', $tickets->note ?? '')" required />

                    <div class="mt-6">
                        <x-button.submit type="submit">Update</x-button.submit>

                        <a href="{{ route('admin.helpdesk.index') }}"
                            class="inline-block px-6 py-2 text-xs font-semibold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                            Kembali
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endif
