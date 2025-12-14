<div class="content">
    <div style="text-align: center; margin-bottom: 20px;">
        <h3 style="margin: 0; text-decoration: underline; text-transform: uppercase;">SURAT KETERANGAN TIDAK MAMPU</h3>
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
            <td>: <?= strtoupper($pekerjaan ?? 'BURUH HARIAN LEPAS') ?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: <?= strtoupper($alamat) ?></td>
        </tr>
    </table>

    <p>Berdasarkan pengamatan kami dan surat pengantar dari RT/RW setempat, nama tersebut diatas adalah benar warga Desa <?= htmlspecialchars($desa) ?> yang kondisi ekonominya tergolong <strong>TIDAK MAMPU (PRA SEJAHTERA)</strong>.</p>
    
    <p>Surat keterangan ini dibuat untuk keperluan:</p>

    <table style="margin-left: 20px; width: 95%;">
        <tr>
            <td width="180" style="vertical-align: top;">Keperluan</td>
            <td style="font-weight: bold; vertical-align: top;">: <?= strtoupper($keperluan ?? 'PERSYARATAN ADMINISTRASI') ?></td>
        </tr>
    </table>

    <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
</div>