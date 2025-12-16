<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Izin Usaha</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 10pt; line-height: 1.3; }

        table { width: 100%; border-collapse: collapse; }

        td { vertical-align: top; }

        /* Style khusus Kop */
        .kop-container {
            width: 100%;
            border-bottom: 4px double #000; /* Garis ganda tebal */
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-logo { width: 120px; text-align: center; }
        .kop-text { text-align: center; }
        .kop-text h1 { margin: 0; font-size: 10pt; font-weight: bold; text-transform: uppercase; }
        .kop-text h2 { margin: 0; font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        .kop-text h3 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .kop-text p { margin: 0; font-size: 9pt; font-style: italic; }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <div class="kop-container">
        <table>
            <tr>
                <td class="kop-logo"><img src="/templates/logo.png" width="120" height="auto" alt="Logo"></td>
                <td class="kop-text">
                    <h1>PEMERINTAH KABUPATEN <?= strtoupper($kabupaten ?? 'Magelang') ?></h1>
                    <h2>KECAMATAN <?= strtoupper($kecamatan ?? 'Grabag') ?></h2>
                    <h3>DESA <?= strtoupper($desa ?? 'Kleteran') ?></h3>
                    <p><?= $alamat_desa ?? 'Jl. Telaga Bleder Km.1 Grabag Magelang' ?></p>
                </td>
            </tr>
        </table>
    </div>

    <!-- Konten Surat -->
    <div class="content">

        <div style="text-align: center; margin-bottom: 20px;">
            <h3 style="margin: 0; text-decoration: underline; text-transform: uppercase;">SURAT IZIN USAHA</h3>
            <p style="margin: 0;">Nomor: <?= $letter_number ?? '...../...../...../.....' ?></p>
        </div>

        <p>Yang bertanda tangan dibawah ini Kepala Desa <?= htmlspecialchars($desa ?? 'Kleteran') ?>, Kecamatan <?= htmlspecialchars($kecamatan ?? 'Grabag') ?>, Kabupaten <?= htmlspecialchars($kabupaten ?? 'Magelang') ?> memberikan <strong>IZIN</strong> kepada:</p>

        <table style="margin-left: 20px; width: 95%;">
            <tr>
                <td width="180">Nama Lengkap</td>
                <td>: <strong><?= strtoupper($nama ?? '') ?></strong></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>: <?= $nik ?? '' ?></td>
            </tr>
            <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>: <?= $tempat_lahir ?? '' ?>, <?= $tanggal_lahir ?? '' ?></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>: <?= $jenis_kelamin ?? '' ?></td>
            </tr>
            <tr>
                <td>Pekerjaan</td>
                <td>: <?= strtoupper($pekerjaan ?? 'WIRASWASTA') ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>: <?= strtoupper($alamat ?? '') ?></td>
            </tr>
        </table>

        <p>Untuk menjalankan usaha dengan rincian sebagai berikut:</p>

        <table style="margin-left: 20px; width: 95%;">
            <tr>
                <td width="180">Nama Usaha</td>
                <td>: <strong><?= strtoupper($nama_usaha ?? '') ?></strong></td>
            </tr>
            <tr>
                <td>Jenis Usaha</td>
                <td>: <?= strtoupper($jenis_usaha ?? '') ?></td>
            </tr>
            <tr>
                <td>Alamat Usaha</td>
                <td>: <?= strtoupper($alamat_usaha ?? $alamat ?? '') ?></td>
            </tr>
            <tr>
                <td>Mulai Usaha</td>
                <td>: <?= $mulai_usaha ?? date('d-m-Y') ?></td>
            </tr>
        </table>

        <p>Pemberian izin usaha ini disertai dengan persyaratan yang harus dipatuhi:</p>

        <ol style="margin-top: 0;">
            <li>Menjalankan usaha sesuai dengan ketentuan perundang-undangan yang berlaku.</li>
            <li>Menjaga kebersihan dan ketertiban di lingkungan sekitar lokasi usaha.</li>
            <li>Tidak menimbulkan gangguan bagi masyarakat sekitar.</li>
            <li>Membayar pajak dan retribusi sesuai ketentuan yang berlaku.</li>
            <li>Izin ini dapat dicabut sewaktu-waktu jika pemegang izin melanggar ketentuan di atas.</li>
        </ol>

        <p>Demikian Surat Izin Usaha ini diberikan untuk dapat dipergunakan sebagaimana mestinya.</p>

    </div>

    <!-- Tanda Tangan -->
    <?php
    // Konversi Tanggal ke Bahasa Indonesia
    $bulanIndo = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $tanggal_sekarang = date('d') . ' ' . $bulanIndo[(int)date('m')] . ' ' . date('Y');

    // Pastikan variabel $desa dan $kepala_desa tersedia
    $desa = $desa ?? 'Kleteran';
    $kepala_desa = $kepala_desa ?? 'Muhammad Waris Zainal, S.Pd.';
    ?>

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

                <div style="position: relative; width: 100%; height: 120px; margin-bottom: 20px;">
                    <!-- Tanda Tangan -->
                    <div style="position: absolute; left: 50%; top: 10px; transform: translateX(-50%); width: 150px; height: 60px;">
                        <img src="/templates/ttd.png" style="width: 100%; height: auto; opacity: 0.9;">
                    </div>

                    <div style="position: absolute; left: 10px; top: -10px; width: 100px; height: 60px; opacity: 0.8;">
                        <img src="/templates/stempel.png" style="width: 100%; height: auto;">
                    </div>

                    <br><br><br>

                </div>

                <div style="font-weight: bold; text-decoration: underline; margin-top: 10px;">
                    <?= strtoupper($kepala_desa) ?>
                </div>

            </td>
        </tr>
    </table>

</body>
</html>
