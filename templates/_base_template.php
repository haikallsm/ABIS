<?php
/**
 * Template Base - Helper Functions for Letter Templates
 * ABIS - Aplikasi Desa Digital
 */

// Fungsi helper untuk format tanggal Indonesia
function formatTanggalIndonesia($tanggal = null) {
    $bulanIndo = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    if ($tanggal) {
        $date = strtotime($tanggal);
        return date('d', $date) . ' ' . $bulanIndo[(int)date('m', $date)] . ' ' . date('Y', $date);
    }

    return date('d') . ' ' . $bulanIndo[(int)date('m')] . ' ' . date('Y');
}

// Fungsi helper untuk menghitung umur
function hitungUmur($tanggal_lahir) {
    if (!$tanggal_lahir) return '...';

    $birthDate = new DateTime($tanggal_lahir);
    $today = new DateTime();
    $age = $today->diff($birthDate);

    return $age->y . ' Tahun';
}

// Fungsi helper untuk generate letter number
function generateLetterNumber($sequential, $year, $month) {
    $romanMonths = [
        1 => 'I', 'II', 'III', 'IV', 'V', 'VI',
        'VII', 'VIII', 'IX', 'X', 'XI', 'XII'
    ];

    return sprintf('%s/%s/%s/%s', '510', $sequential, $romanMonths[$month], $year);
}

// Fungsi helper untuk render kop surat
function renderKopSurat($kabupaten = 'Magelang', $kecamatan = 'Grabag', $desa = 'Kleteran', $alamat_desa = 'Jl. Telaga Bleder Km.1 Grabag Magelang') {
    ?>
    <!-- Kop Surat -->
    <div style="width: 100%; border-bottom: 4px double #000; padding-bottom: 10px; margin-bottom: 20px;">
        <table style="width: 100%;">
            <tr>
                <td style="width: 120px; text-align: center;">
                    <img src="/templates/logo.png" width="120" height="auto" alt="Logo">
                </td>
                <td style="text-align: center;">
                    <h1 style="margin: 0; font-size: 10pt; font-weight: bold; text-transform: uppercase;">PEMERINTAH KABUPATEN <?= strtoupper($kabupaten) ?></h1>
                    <h2 style="margin: 0; font-size: 12pt; font-weight: bold; text-transform: uppercase;">KECAMATAN <?= strtoupper($kecamatan) ?></h2>
                    <h3 style="margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase;">DESA <?= strtoupper($desa) ?></h3>
                    <p style="margin: 0; font-size: 9pt; font-style: italic;"><?= $alamat_desa ?></p>
                </td>
            </tr>
        </table>
    </div>
    <?php
}

// Fungsi helper untuk render tanda tangan
function renderTandaTangan($desa = 'Kleteran', $kepala_desa = 'Muhammad Waris Zainal, S.Pd.') {
    $tanggal_sekarang = formatTanggalIndonesia();
    ?>
    <!-- Tanda Tangan -->
    <br><br>

    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%; text-align: center; vertical-align: top;">
                <div style="margin-bottom: 5px;">
                    <?= strtoupper($desa) ?>, <?= $tanggal_sekarang ?>
                </div>

                <div style="margin-bottom: 30px; font-weight: bold;">
                    KEPALA DESA <?= strtoupper($desa) ?>
                </div>

                <div style="margin-top: 40px; margin-bottom: 20px; text-align: center;">
                    <div style="font-size: 10pt; color: #666; margin-bottom: 10px;">
                        [Tempat untuk Stempel]
                    </div>
                </div>

                <div style="font-weight: bold; text-decoration: underline; margin-top: 10px;">
                    <?= strtoupper($kepala_desa) ?>
                </div>
            </td>
        </tr>
    </table>
    <?php
}

// Fungsi helper untuk render identitas orang
function renderIdentitasOrang($data) {
    ?>
    <table style="margin-left: 20px; width: 95%;">
        <tr>
            <td width="180">Nama</td>
            <td>: <strong><?= strtoupper($data['nama'] ?? '') ?></strong></td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>: <?= $data['nik'] ?? '' ?></td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>: <?= strtoupper($data['jenis_kelamin'] ?? '') ?></td>
        </tr>
        <tr>
            <td>Tempat, Tanggal Lahir</td>
            <td>: <?= strtoupper($data['tempat_lahir'] ?? '') ?>, <?= $data['tanggal_lahir'] ?? '' ?></td>
        </tr>
        <tr>
            <td>Agama</td>
            <td>: <?= strtoupper($data['agama'] ?? '') ?></td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td>: <?= strtoupper($data['pekerjaan'] ?? 'WIRASWASTA') ?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: <?= strtoupper($data['alamat'] ?? '') ?></td>
        </tr>
    </table>
    <?php
}

// CSS umum untuk semua template
function renderCommonCSS() {
    ?>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 10pt;
            line-height: 1.3;
            margin: 0;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
        }

        .kop-container {
            width: 100%;
            border-bottom: 4px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-logo {
            width: 120px;
            text-align: center;
        }

        .kop-text {
            text-align: center;
        }

        .kop-text h1 {
            margin: 0;
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-text h2 {
            margin: 0;
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-text h3 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-text p {
            margin: 0;
            font-size: 9pt;
            font-style: italic;
        }
    </style>
    <?php
}
?>
