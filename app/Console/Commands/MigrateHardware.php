<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateHardware extends Command
{
    protected $signature = 'migrasi:hardware';
    protected $description = 'Migrasi data toner dari database lama ke database baru Laravel';

    public function handle()
    {
        $old = DB::connection('mysql_old')
            ->table('data_hardware')
            ->get();

        foreach ($old as $row) {

            $createdAt = $row->tanggal . ' ' . $row->waktu_input;

            $checklist = [

                "Wallpaper backround RS" => [
                    "status" => (string) $row->wallpaper_cek,
                    "keterangan" => $row->wallpaper_text
                ],

                "Pastikan login sistem operasi ada dua (admin dan limit)" => [
                    "status" => (string) $row->login_cek,
                    "keterangan" => $row->login_text
                ],

                "Pastikan password admin dan limit terkontrol" => [
                    "status" => (string) $row->password_cek,
                    "keterangan" => $row->password_text
                ],

                "Screen saver jalan" => [
                    "status" => (string) $row->saver_cek,
                    "keterangan" => $row->saver_text
                ],

                "Aplikasi remote VNC berjalan" => [
                    "status" => (string) $row->vnc_cek,
                    "keterangan" => $row->vnc_text
                ],

                "Folder sharing tersedia" => [
                    "status" => (string) $row->folder_cek,
                    "keterangan" => $row->folder_text
                ],

                "Bersihkan komputer dari software yang tidak diijinkan" => [
                    "status" => (string) $row->software_cek,
                    "keterangan" => $row->software_text
                ],

                "Cek kapasitas hardisk sistem operasi C" => [
                    "status" => (string) $row->hardisk_cek,
                    "keterangan" => $row->hardisk_text
                ],

                "Jalankan SIMRS HAMORI" => [
                    "status" => (string) $row->simrs_cek,
                    "keterangan" => $row->simrs_text
                ],

                "IP address sesuai" => [
                    "status" => (string) $row->ip_cek,
                    "keterangan" => $row->ip_text
                ],

                "Ping Local dan Internet berjalan/reply" => [
                    "status" => (string) $row->ping_cek,
                    "keterangan" => $row->ping_text
                ],

                "Printer bisa digunakan" => [
                    "status" => (string) $row->printer_cek,
                    "keterangan" => $row->printer_text
                ],

                "Catridge terisi tinta" => [
                    "status" => (string) $row->tinta_cek,
                    "keterangan" => $row->tinta_text
                ],

                "Cek nyalanya Monitor" => [
                    "status" => (string) $row->monitor_cek,
                    "keterangan" => $row->monitor_text
                ],

                "Cek fungsi UPS" => [
                    "status" => (string) $row->ups_cek,
                    "keterangan" => $row->ups_text
                ],

                "Cek fungsi Mouse" => [
                    "status" => (string) $row->mouse_cek,
                    "keterangan" => $row->mouse_text
                ],

                "Cek fungsi Keyboard" => [
                    "status" => (string) $row->keyboard_cek,
                    "keterangan" => $row->keyboard_text
                ],

                "Bersihkan Casing bagian dalam dari debu" => [
                    "status" => (string) $row->case_cek,
                    "keterangan" => $row->case_text
                ],

                "Rapihkan pengkabelan" => [
                    "status" => (string) $row->kabel_cek,
                    "keterangan" => $row->kabel_text
                ],

                "Rapikan penempatan" => [
                    "status" => (string) $row->penempatan_cek,
                    "keterangan" => $row->penempatan_text
                ],
            ];

            DB::table('hardware')->insert([
                'nama'       => $row->nama,
                'unit'       => $row->unit,
                'lantai'     => $row->lantai,
                'tanggal'    => $row->tanggal,
                'checklist'  => json_encode($checklist, JSON_UNESCAPED_UNICODE),
                'created_at'  => $createdAt,
                'updated_at'  => now(),
            ]);
        }

        $this->info("Migrasi selesai! Total: " . count($old));
    }
}
