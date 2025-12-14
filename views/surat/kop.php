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

<div class="kop-container">
    <table>
        <tr>
            <td class="kop-logo"><img src="logo.png" width="80" height="auto" alt="Logo"></td>
            <td class="kop-text">
                <h1>PEMERINTAH KABUPATEN <?= strtoupper($kabupaten ?? 'LOMBOK BARAT') ?></h1>
                <h2>KECAMATAN <?= strtoupper($kecamatan ?? 'LABUAPI') ?></h2>
                <h3>DESA <?= strtoupper($desa ?? 'TERONG TAWAH') ?></h3>
                <p><?= $alamat_desa ?? 'Jl. TGH. Mansyur - Kode Pos 83361' ?></p>
            </td>
        </tr>
    </table>
</div>