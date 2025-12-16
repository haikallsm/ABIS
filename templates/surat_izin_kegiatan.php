<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Izin Kegiatan</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.5; }

        table { width: 100%; border-collapse: collapse; }

        td { vertical-align: top; }

        /* Style khusus Kop */
        .kop-container {
            width: 100%;
            border-bottom: 4px double #000; /* Garis ganda tebal */
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-logo { width: 80px; text-align: center; }
        .kop-text { text-align: center; }
        .kop-text h1 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .kop-text h2 { margin: 0; font-size: 16pt; font-weight: bold; text-transform: uppercase; }
        .kop-text h3 { margin: 0; font-size: 18pt; font-weight: bold; text-transform: uppercase; }
        .kop-text p { margin: 0; font-size: 10pt; font-style: italic; }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <div class="kop-container">
        <table>
            <tr>
                <td class="kop-logo"><img src="<?php echo __DIR__ . '/logo.png'; ?>" width="80" height="auto" alt="Logo"></td>
                <td class="kop-text">
                    <h1>PEMERINTAH KABUPATEN <?= strtoupper($kabupaten ?? 'LOMBOK BARAT') ?></h1>
                    <h2>KECAMATAN <?= strtoupper($kecamatan ?? 'LABUAPI') ?></h2>
                    <h3>DESA <?= strtoupper($desa ?? 'TERONG TAWAH') ?></h3>
                    <p><?= $alamat_desa ?? 'Jl. TGH. Mansyur - Kode Pos 83361' ?></p>
                </td>
            </tr>
        </table>
    </div>

    <!-- Konten Surat -->
    <div class="content">

        <div style="text-align: center; margin-bottom: 20px;">
            <h3 style="margin: 0; text-decoration: underline; text-transform: uppercase;">SURAT IZIN KEGIATAN / KERAMAIAN</h3>
            <p style="margin: 0;">Nomor: <?= $letter_number ?? '...../...../...../.....' ?></p>
        </div>

        <p>Yang bertanda tangan dibawah ini Kepala Desa <?= htmlspecialchars($desa ?? 'Terong Tawah') ?>, Kecamatan <?= htmlspecialchars($kecamatan ?? 'Labuapi') ?> memberikan <strong>IZIN / REKOMENDASI</strong> kepada:</p>

        <table style="margin-left: 20px; width: 95%;">
            <tr>
                <td width="180">Nama Penanggung Jawab</td>
                <td>: <strong><?= strtoupper($request['user_full_name'] ?? '') ?></strong></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>: <?= $request['nik'] ?? '' ?></td>
            </tr>
            <tr>
                <td>Umur</td>
                <td>: <?= $umur ?? '...' ?> Tahun</td>
            </tr>
            <tr>
                <td>Pekerjaan</td>
                <td>: <?= strtoupper($request['occupation'] ?? 'WIRASWASTA') ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>: <?= strtoupper($request['address'] ?? '') ?></td>
            </tr>
        </table>

        <p>Untuk menyelenggarakan kegiatan/acara dengan rincian sebagai berikut:</p>

        <table style="margin-left: 20px; width: 95%;">
            <tr>
                <td width="180">Nama Kegiatan</td>
                <td>: <strong><?= strtoupper($nama_kegiatan ?? 'HAJATAN PERNIKAHAN') ?></strong></td>
            </tr>
            <tr>
                <td>Hari, Tanggal</td>
                <td>: <?= $hari_kegiatan ?? 'Minggu' ?>, <?= $tanggal_kegiatan ?? date('d-m-Y') ?></td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td>: <?= $waktu_kegiatan ?? '08.00 WIB s/d Selesai' ?></td>
            </tr>
            <tr>
                <td>Tempat</td>
                <td>: <?= strtoupper($tempat_kegiatan ?? $request['address'] ?? '') ?></td>
            </tr>
            <tr>
                <td>Hiburan (Jika ada)</td>
                <td>: <?= strtoupper($hiburan ?? '-') ?></td>
            </tr>
        </table>

        <p>Pemberian izin ini disertai dengan persyaratan yang harus dipatuhi:</p>

        <ol style="margin-top: 0;">
            <li>Bertanggung jawab penuh atas keamanan dan ketertiban selama kegiatan berlangsung.</li>
            <li>Menjaga kebersihan lingkungan dan tidak mengganggu fasilitas umum.</li>
            <li>Tidak menyajikan minuman keras (miras), narkoba, dan hal-hal yang melanggar norma agama/hukum.</li>
            <li>Apabila kegiatan berskala besar/menggunakan jalan umum, wajib berkoordinasi dengan pihak Kepolisian (Polsek) dan Babinsa setempat.</li>
            <li>Izin ini dapat dibatalkan sewaktu-waktu jika pemegang izin melanggar ketentuan di atas.</li>
        </ol>

        <p>Demikian Surat Izin ini diberikan untuk dapat dipergunakan sebagaimana mestinya.</p>

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
    $desa = $desa ?? 'Terong Tawah';
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

</body>
</html>


