<?php

namespace App\Http\Controllers;

use App\Models\Ipsrs;
use Illuminate\Http\Request;

class IPSRSController extends Controller
{
    public function index()
    {
        $data = Ipsrs::all();
        return view('komplain.ipsrs.index', compact('data'));
    }

    public function create()
    {
        return view('komplain.ipsrs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
        ]);

        Ipsrs::create($request->all());
        return redirect()->route('ipsrs.index')->with('success', 'Data berhasil ditambahkan.');
    }

    // lanjutkan: show, edit, update, destroy...
}

