@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="flex flex-wrap -mx-3">
        <div class="w-full max-w-full px-3 mx-auto mt-0">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                    <h6 class="mb-0 font-bold text-lg">Edit Komplain IPSRS</h6>
                </div>
                <div class="flex-auto p-6">
                    <form action="{{ route('komplain.ipsrs.update', $komplain->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @include('pages.komplain.ipsrs.form', ['komplain' => $komplain])

                        <div class="mt-6">
                            <button type="submit" class="inline-block px-6 py-2 mb-0 text-xs font-bold text-center text-slate-700 uppercase align-middle transition-all bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                                Edit
                            </button>
                            <a href="{{ route('komplain.ipsrs.index') }}" class="inline-block ml-2 px-6 py-2 mb-0 text-xs font-semibold text-center text-slate-700 uppercase align-middle transition-all bg-gray-200 rounded-lg hover:bg-gray-300 active:opacity-85">
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
