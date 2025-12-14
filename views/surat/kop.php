<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
.kop { text-align:center; border-bottom:3px solid #000; padding-bottom:10px; }
.kop h1 { margin:0; font-size:16px; }
.kop h2 { margin:0; font-size:14px; }
.kop p { margin:2px 0; }
.content { margin-top:20px; }
table { width:100%; }
td { padding:4px 0; vertical-align:top; }
</style>

<div class="kop">
    <h1>PEMERINTAH DESA <?= strtoupper($desa) ?></h1>
    <h2>KECAMATAN <?= strtoupper($kecamatan) ?>, KABUPATEN <?= strtoupper($kabupaten) ?></h2>
    <p><?= $alamat_desa ?></p>
</div>
