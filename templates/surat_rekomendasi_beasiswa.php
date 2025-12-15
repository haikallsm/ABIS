<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Rekomendasi Beasiswa</title>
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
            <h3 style="margin: 0; text-decoration: underline; text-transform: uppercase;">SURAT REKOMENDASI BEASISWA</h3>
            <p style="margin: 0;">Nomor: <?= $letter_number ?? '...../...../...../.....' ?></p>
        </div>

        <p>Yang bertanda tangan dibawah ini Kepala Desa <?= htmlspecialchars($desa ?? 'Terong Tawah') ?>, Kecamatan <?= htmlspecialchars($kecamatan ?? 'Labuapi') ?> menerangkan dengan sebenarnya, bahwa:</p>

        <table style="margin-left: 20px; width: 95%;">
            <tr>
                <td width="180">Nama Lengkap</td>
                <td>: <strong><?= strtoupper($request['user_full_name'] ?? '') ?></strong></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>: <?= $request['nik'] ?? '' ?></td>
            </tr>
            <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>: <?= strtoupper($request['birth_place'] ?? '') ?>, <?= $request['birth_date'] ?? '' ?></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>: <?= strtoupper($request['gender'] ?? '') ?></td>
            </tr>
            <tr>
                <td>Alamat Rumah</td>
                <td>: <?= strtoupper($request['address'] ?? '') ?></td>
            </tr>
            <tr>
                <td>Nama Orang Tua/Wali</td>
                <td>: <?= strtoupper($nama_ayah ?? '-') ?></td>
            </tr>
        </table>

        <p>Nama tersebut di atas adalah benar-benar warga Desa <?= htmlspecialchars($desa ?? 'Terong Tawah') ?> yang saat ini berstatus sebagai <strong>Pelajar/Mahasiswa</strong> aktif pada:</p>

        <table style="margin-left: 20px; width: 95%;">
            <tr>
                <td width="180">Asal Sekolah / Kampus</td>
                <td>: <strong><?= strtoupper($sekolah ?? '..............................') ?></strong></td>
            </tr>
            <tr>
                <td>NIS / NIM</td>
                <td>: <?= strtoupper($nis_nim ?? '-') ?></td>
            </tr>
            <tr>
                <td>Kelas / Semester</td>
                <td>: <?= strtoupper($semester ?? '-') ?></td>
            </tr>
            <tr>
                <td>Jurusan / Prodi</td>
                <td>: <?= strtoupper($jurusan ?? '-') ?></td>
            </tr>
        </table>

        <p>Sepanjang pengetahuan kami, anak tersebut dikenal berkelakuan baik, berprestasi, dan berasal dari keluarga yang layak untuk dibantu.</p>

        <p>Oleh karena itu, Pemerintah Desa <?= htmlspecialchars($desa ?? 'Terong Tawah') ?> memberikan <strong>REKOMENDASI</strong> kepada yang bersangkutan untuk mengikuti seleksi / mengajukan permohonan:</p>

        <div style="text-align: center; margin: 10px 0;">
            <h3 style="margin: 0; text-transform: uppercase;"><?= $nama_beasiswa ?? 'BEASISWA PENDIDIKAN' ?></h3>
        </div>

        <p>Demikian surat rekomendasi ini dibuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya.</p>

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

                <div style="position: relative; width: 100%; height: 80px; margin-bottom: 20px;">

                    <div style="position: absolute; left: 10px; top: -10px; width: 100px; height: 60px; opacity: 0.8;">
                        <img src="<?php echo __DIR__ . '/stempel.png'; ?>" style="width: 100%; height: auto;">
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
