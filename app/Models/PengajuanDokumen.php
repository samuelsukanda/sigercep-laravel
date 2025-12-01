<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanDokumen extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_dokumen';

    protected $fillable = [
        'jenis_dokumen',
        'permintaan_pengajuan',
        'kategori_pengajuan',
        'nomor_dokumen',
        'judul_dokumen',
        'nomor_revisi',
        'alasan_pengajuan',
        'bagian_yang_direvisi',
        'sebelum_revisi',
        'usulan_revisi',
        'tanggal_pengajuan',
        'diajukan_oleh',
        'diperiksa_oleh',
        'disetujui_oleh',
        'file_pdf',
        'file_path',
    ];
}
