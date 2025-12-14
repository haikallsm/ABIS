<div class="content">
    <div style="text-align: center; margin-bottom: 20px;">
        <h3 style="margin: 0; text-decoration: underline; text-transform: uppercase;">SURAT IZIN USAHA</h3>
        <p style="margin: 0;">Nomor: <?= $nomor_surat ?? '...../...../...../.....' ?></p>
    </div>

    <p>Yang bertanda tangan dibawah ini Kepala Desa <?= htmlspecialchars($desa) ?>, Kecamatan <?= htmlspecialchars($kecamatan) ?>, dengan ini memberikan <strong>IZIN USAHA</strong> kepada:</p>

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
            <td>Tempat, Tanggal Lahir</td>
            <td>: <?= strtoupper($tempat_lahir) ?>, <?= $tanggal_lahir ?></td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td>: <?= strtoupper($pekerjaan ?? 'WIRASWASTA') ?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: <?= strtoupper($alamat) ?></td>
        </tr>
    </table>

    <p>Untuk menjalankan kegiatan usaha di wilayah Desa <?= htmlspecialchars($desa) ?> dengan rincian identitas usaha sebagai berikut:</p>

    <table style="margin-left: 20px; width: 95%;">
        <tr>
            <td width="180">Nama Usaha</td>
            <td>: <strong><?= strtoupper($nama_usaha) ?></strong></td>
        </tr>
        <tr>
            <td>Jenis Usaha</td>
            <td>: <?= strtoupper($jenis_usaha) ?></td>
        </tr>
        <tr>
            <td>Lokasi / Alamat Usaha</td>
            <td>: <?= strtoupper($alamat_usaha) ?></td>
        </tr>
        <?php if (!empty($luas_usaha)): ?>
        <tr>
            <td>Luas Tempat Usaha</td>
            <td>: <?= $luas_usaha ?></td>
        </tr>
        <?php endif; ?>
    </table>

    <p>Pemberian izin ini disertai dengan ketentuan yang harus ditaati sebagai berikut:</p>
    <ol style="margin-top: 0;">
        <li>Wajib menjaga kebersihan, ketertiban, dan keamanan lingkungan tempat usaha.</li>
        <li>Tidak melakukan kegiatan yang bertentangan dengan hukum, norma agama, dan norma sosial masyarakat.</li>
        <li>Apabila dikemudian hari ternyata pemegang izin melanggar ketentuan di atas, maka izin ini dapat dicabut.</li>
    </ol>

    <p>Demikian Surat Izin Usaha ini diberikan agar dapat dipergunakan sebagaimana mestinya.</p>
</div>