<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan</title>
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

        .letter-header {
            text-align: center;
            margin: 20px 0;
        }

        .letter-title {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 10px;
            text-decoration: underline;
        }

        .letter-body {
            text-align: justify;
            margin: 20px 0;
        }
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

    <!-- Nomor Surat -->
    <div class="letter-number" style="text-align: center; margin: 30px 0;">
        <strong><?php echo htmlspecialchars($letter_number ?? ''); ?></strong>
    </div>

    <!-- Judul Surat -->
    <div class="letter-header">
        <div class="letter-title">SURAT KETERANGAN</div>
        <div>Nomor: <?php echo htmlspecialchars($letter_number ?? ''); ?></div>
    </div>

    <!-- Isi Surat -->
    <div class="letter-body">
        <p>Yang bertanda tangan di bawah ini:</p>

        <table style="margin-left: 40px; margin-bottom: 20px;">
            <tr>
                <td width="150">Nama</td>
                <td width="20">:</td>
                <td><?php echo htmlspecialchars($request['user_full_name'] ?? ''); ?></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td><?php echo htmlspecialchars($request['nik'] ?? ''); ?></td>
            </tr>
            <tr>
                <td>Tempat/Tanggal Lahir</td>
                <td>:</td>
                <td><?php echo htmlspecialchars(($request['birth_place'] ?? '') . ', ' . ($request['birth_date'] ?? '')); ?></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td><?php echo htmlspecialchars($request['gender'] ?? ''); ?></td>
            </tr>
            <tr>
                <td>Agama</td>
                <td>:</td>
                <td><?php echo htmlspecialchars($request['religion'] ?? ''); ?></td>
            </tr>
            <tr>
                <td>Pekerjaan</td>
                <td>:</td>
                <td><?php echo htmlspecialchars($request['occupation'] ?? ''); ?></td>
            </tr>
            <tr>
                <td>Status Perkawinan</td>
                <td>:</td>
                <td><?php echo htmlspecialchars($request['marital_status'] ?? ''); ?></td>
            </tr>
            <tr>
                <td valign="top">Alamat</td>
                <td valign="top">:</td>
                <td><?php echo htmlspecialchars($request['address'] ?? ''); ?></td>
            </tr>
        </table>

        <p>Dengan ini menerangkan bahwa:</p>

        <p style="text-align: justify; padding-left: 40px;">
            Orang tersebut di atas benar merupakan warga Desa Penelokan, Kecamatan Manggis,
            Kabupaten Karangasem, Bali yang bertempat tinggal di alamat tersebut di atas.
        </p>

        <p style="text-align: justify; padding-left: 40px;">
            Surat keterangan ini dibuat berdasarkan permohonan yang bersangkutan dan dipergunakan
            untuk keperluan: <strong><?php echo htmlspecialchars($letter_type['name'] ?? ''); ?></strong>
        </p>

        <p style="text-align: justify; padding-left: 40px;">
            Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.
        </p>
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
