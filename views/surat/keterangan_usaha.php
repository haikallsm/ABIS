\<div class="content">
    <div style="text-align: center; margin-bottom: 20px;">
        <h3 style="margin: 0; text-decoration: underline; text-transform: uppercase;">SURAT KETERANGAN USAHA</h3>
        <p style="margin: 0;">Nomor: <?= $nomor_surat ?? '...../...../...../.....' ?></p>
    </div>

    <p>Yang bertanda tangan dibawah ini Kepala Desa <?= htmlspecialchars($desa) ?>, Kecamatan <?= htmlspecialchars($kecamatan) ?> menerangkan dengan sebenarnya, bahwa:</p>

    <table style="margin-left: 20px; width: 95%;">
        <tr>
            <td width="180">Nama</td>
            <td>: <?= strtoupper($nama) ?></td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>: <?= $nik ?></td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>: <?= strtoupper($jenis_kelamin) ?></td>
        </tr>
        <tr>
            <td>Tempat, Tanggal Lahir</td>
            <td>: <?= strtoupper($tempat_lahir) ?>, <?= $tanggal_lahir ?></td>
        </tr>
        <tr>
            <td>Warganegara / Agama</td>
            <td>: <?= strtoupper($warganegara ?? 'WNI') ?> / <?= strtoupper($agama) ?></td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td>: <?= strtoupper($pekerjaan ?? '-') ?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: <?= strtoupper($alamat) ?></td>
        </tr>
    </table>

    <p>Sesuai dengan keterangan yang bersangkutan benar nama tersebut diatas mempunyai usaha sebagai berikut :</p>

    <table style="margin-left: 20px; width: 95%;">
        <tr>
            <td width="180">Nama Usaha</td>
            <td>: <strong><?= strtoupper($nama_usaha) ?></strong></td>
        </tr>
        <tr>
            <td>Mulai Usaha Sejak</td>
            <td>: <?= $mulai_usaha ?? 'TAHUN ....' ?></td>
        </tr>
        <tr>
            <td>Alamat Usaha</td>
            <td>: <?= strtoupper($alamat_usaha) ?></td>
        </tr>
        <tr>
            <td>Tujuan</td>
            <td>: <?= strtoupper($tujuan ?? 'DOMISILI') ?></td>
        </tr>
    </table>

    <p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
</div>