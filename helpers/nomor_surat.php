<?php

function kodeSurat(string $jenis): string
{
    return match ($jenis) {
        'keterangan_domisili'    => 'DOM',
        'keterangan_tidak_mampu' => 'TM',
        'keterangan_usaha'       => 'US',
        'belum_menikah'          => 'BM',
        'izin_kegiatan'          => 'IK',
        'izin_usaha'             => 'IU',
        'rekomendasi_beasiswa'   => 'RB',
        default                  => 'XX',
    };
}

function generateNomorSurat(string $jenis): string
{
    $tahun = date('Y');
    $kode  = kodeSurat($jenis);

    $storageDir = __DIR__ . '/../storage';

    if (!is_dir($storageDir)) {
        mkdir($storageDir, 0777, true);
    }

    $file = $storageDir . "/nomor_{$jenis}_{$tahun}.txt";

    if (!file_exists($file)) {
        $urut = 1;
    } else {
        $urut = (int) file_get_contents($file) + 1;
    }

    file_put_contents($file, $urut);

    $urutFormat = str_pad($urut, 3, '0', STR_PAD_LEFT);

    return "470/{$kode}/{$urutFormat}/ABIS/{$tahun}";
}
