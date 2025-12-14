<div class="content">
    <div style="text-align: center; margin-bottom: 20px;">
        <h3 style="margin: 0; text-decoration: underline; text-transform: uppercase;">SURAT REKOMENDASI BEASISWA</h3>
        <p style="margin: 0;">Nomor: <?= $nomor_surat ?? '...../...../...../.....' ?></p>
    </div>

    <p>Yang bertanda tangan dibawah ini Kepala Desa <?= htmlspecialchars($desa) ?>, Kecamatan <?= htmlspecialchars($kecamatan) ?> menerangkan dengan sebenarnya, bahwa:</p>

    <table style="margin-left: 20px; width: 95%;">
        <tr>
            <td width="180">Nama Lengkap</td>
            <td>: <strong><?= strtoupper($nama) ?></strong></td>
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
            <td>Jenis Kelamin</td>
            <td>: <?= strtoupper($jenis_kelamin) ?></td>
        </tr>
        <tr>
            <td>Alamat Rumah</td>
            <td>: <?= strtoupper($alamat) ?></td>
        </tr>
        <tr>
            <td>Nama Orang Tua/Wali</td>
            <td>: <?= strtoupper($nama_ayah ?? '-') ?></td>
        </tr>
    </table>

    <p>Nama tersebut di atas adalah benar-benar warga Desa <?= htmlspecialchars($desa) ?> yang saat ini berstatus sebagai <strong>Pelajar/Mahasiswa</strong> aktif pada:</p>

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

    <p>Oleh karena itu, Pemerintah Desa <?= htmlspecialchars($desa) ?> memberikan <strong>REKOMENDASI</strong> kepada yang bersangkutan untuk mengikuti seleksi / mengajukan permohonan:</p>
    
    <div style="text-align: center; margin: 10px 0;">
        <h3 style="margin: 0; text-transform: uppercase;"><?= $nama_beasiswa ?? 'BEASISWA PENDIDIKAN' ?></h3>
    </div>

    <p>Demikian surat rekomendasi ini dibuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya.</p>
</div>