<div class="content">
    <div style="text-align: center; margin-bottom: 20px;">
        <h3 style="margin: 0; text-decoration: underline; text-transform: uppercase;">SURAT IZIN KEGIATAN / KERAMAIAN</h3>
        <p style="margin: 0;">Nomor: <?= $nomor_surat ?? '...../...../...../.....' ?></p>
    </div>

    <p>Yang bertanda tangan dibawah ini Kepala Desa <?= htmlspecialchars($desa) ?>, Kecamatan <?= htmlspecialchars($kecamatan) ?> memberikan <strong>IZIN / REKOMENDASI</strong> kepada:</p>

    <table style="margin-left: 20px; width: 95%;">
        <tr>
            <td width="180">Nama Penanggung Jawab</td>
            <td>: <strong><?= strtoupper($nama) ?></strong></td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>: <?= $nik ?></td>
        </tr>
        <tr>
            <td>Umur</td>
            <td>: <?= $umur ?? '...' ?> Tahun</td>
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
            <td>: <?= strtoupper($tempat_kegiatan ?? $alamat) ?></td>
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