<?php
function validateSurat($jenis, $data) {
    $rules = [
        'keterangan_domisili' => ['nama','nik','alamat','keperluan'],
        'keterangan_tidak_mampu' => ['nama','nik','alamat'],
        'keterangan_usaha' => ['nama','nik','nama_usaha','jenis_usaha'],
        'rekomendasi_beasiswa' => ['nama','nik','sekolah'],
        'izin_kegiatan' => ['nama','nama_kegiatan','waktu']
    ];

    if (!isset($rules[$jenis])) return [];

    $errors = [];
    foreach ($rules[$jenis] as $field) {
        if (empty($data[$field])) {
            $errors[] = "Field $field wajib diisi";
        }
    }

    return $errors;
}
