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

            <div style="position: relative; width: 100%; height: 80px;">
                
                <img src="path/ke/stempel.png" 
                     style="position: absolute; 
                            left: 10px;        /* Geser stempel ke kiri teks nama */
                            top: -10px;        /* Geser stempel agak ke atas */
                            width: 100px;      /* Ukuran stempel */
                            height: auto; 
                            opacity: 0.8;      /* Transparansi agar terlihat menyatu */
                            z-index: -1;">     /* Agar tidak menutupi teks tanda tangan sepenuhnya */
                
                <br><br><br>
            </div>

            <div style="font-weight: bold; text-decoration: underline; margin-top: 10px;">
                <?= strtoupper($kepala_desa) ?>
            </div>
        </td>
    </tr>
</table>