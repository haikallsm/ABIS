<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Rekomendasi Beasiswa</title>
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
            <h3 style="margin: 0; text-decoration: underline; text-transform: uppercase;">SURAT REKOMENDASI BEASISWA</h3>
            <p style="margin: 0;">Nomor: <?= $letter_number ?? '...../...../...../.....' ?></p>
        </div>

        <p>Yang bertanda tangan dibawah ini Kepala Desa <?= htmlspecialchars($desa ?? 'Kleteran') ?>, Kecamatan <?= htmlspecialchars($kecamatan ?? 'Grabag') ?> memberikan <strong>REKOMENDASI</strong> kepada:</p>

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
                <td>Asal Sekolah/Kampus</td>
                <td>: <?= strtoupper($sekolah ?? '') ?></td>
            </tr>
            <tr>
                <td>NIS/NIM</td>
                <td>: <?= $nis_nim ?? '' ?></td>
            </tr>
            <tr>
                <td>Jurusan/Prodi</td>
                <td>: <?= strtoupper($jurusan ?? '') ?></td>
            </tr>
            <tr>
                <td>Semester</td>
                <td>: <?= $semester ?? '' ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>: <?= strtoupper($alamat ?? '') ?></td>
            </tr>
        </table>

        <p>Untuk mengajukan beasiswa dengan rincian sebagai berikut:</p>

        <table style="margin-left: 20px; width: 95%;">
            <tr>
                <td width="180">Nama Beasiswa</td>
                <td>: <strong><?= strtoupper($nama_beasiswa ?? '') ?></strong></td>
            </tr>
            <tr>
                <td>Nama Orang Tua/Wali</td>
                <td>: <?= strtoupper($nama_ayah ?? '') ?></td>
            </tr>
        </table>

        <p>Demikian surat rekomendasi ini dibuat dengan sebenarnya untuk digunakan sebagaimana mestinya.</p>

        <div style="margin-top: 40px;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%;"></td>
                    <td style="text-align: center;">
                        <p><?= htmlspecialchars($desa ?? 'Kleteran') ?>, <?= date('d F Y') ?></p>
                        <p>Kepala Desa <?= htmlspecialchars($desa ?? 'Kleteran') ?></p>
                        <br><br><br><br>
                        <p><strong><u><?= htmlspecialchars($kepala_desa ?? 'MUHAMMAD WARIS ZAINAL, S.Pd.') ?></u></strong></p>
                    </td>
                </tr>
            </table>
        </div>

        <div style="position: absolute; bottom: 50px; right: 50px; text-align: center;">
            <img src="/templates/stempel.png" width="100" height="auto" alt="Stempel" style="opacity: 0.7;">
        </div>

        <div style="position: absolute; bottom: 20px; right: 50px; text-align: center;">
            <img src="/templates/ttd.png" width="120" height="auto" alt="Tanda Tangan" style="opacity: 0.8;">
        </div>

    </div>

</body>
</html>



