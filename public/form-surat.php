<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Surat Desa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .animate-fade { animation: fadeIn 0.5s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center py-10">

<div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl overflow-hidden">

    <div class="bg-blue-700 text-white px-8 py-6 text-center">
        <h1 class="text-3xl font-bold">Layanan Surat Desa</h1>
        <p class="text-blue-100 mt-2">Isi formulir di bawah ini dengan data yang valid</p>
    </div>

    <form action="cetak-surat.php" method="post" class="p-8 space-y-6">

        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
            <label class="block font-bold text-gray-700 mb-2">Pilih Jenis Surat</label>
            <select name="jenis" id="jenis" onchange="toggleForm()" required
                class="w-full border-2 border-blue-300 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-600 font-semibold text-gray-700">
                <option value="">-- Silakan Pilih --</option>
                <option value="keterangan_domisili">Surat Keterangan Domisili</option>
                <option value="keterangan_tidak_mampu">Surat Keterangan Tidak Mampu (SKTM)</option>
                <option value="keterangan_usaha">Surat Keterangan Usaha (SKU)</option>
                <option value="izin_usaha">Surat Izin Usaha</option>
                <option value="belum_menikah">Surat Keterangan Belum Menikah</option>
                <option value="izin_kegiatan">Surat Izin Kegiatan / Keramaian</option>
                <option value="rekomendasi_beasiswa">Surat Rekomendasi Beasiswa</option>
            </select>
        </div>

        <div class="space-y-4">
            <h3 class="text-lg font-bold text-gray-800 border-b pb-2">Data Diri Pemohon</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="nama" required class="w-full border rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">NIK</label>
                    <input type="number" name="nik" required class="w-full border rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" required class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" required class="w-full border rounded px-3 py-2">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full border rounded px-3 py-2">
                        <option value="Laki-Laki">Laki-Laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Agama</label>
                    <select name="agama" class="w-full border rounded px-3 py-2">
                        <option value="Islam">Islam</option>
                        <option value="Kristen">Kristen</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Buddha">Buddha</option>
                        <option value="Konghucu">Konghucu</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pekerjaan</label>
                    <input type="text" name="pekerjaan" class="w-full border rounded px-3 py-2" placeholder="Contoh: Wiraswasta">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kewarganegaraan</label>
                    <input type="text" name="warganegara" value="WNI" class="w-full border rounded px-3 py-2">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Alamat Lengkap (Sesuai KTP)</label>
                <textarea name="alamat" rows="2" required class="w-full border rounded px-3 py-2"></textarea>
            </div>
        </div>

        <div id="section-keperluan" class="hidden animate-fade bg-gray-50 p-4 rounded border">
            <label class="block text-sm font-medium text-gray-700 mb-1">Keperluan Surat</label>
            <input type="text" name="keperluan" class="w-full border rounded px-3 py-2" placeholder="Contoh: Pengurusan Rekening Bank / Syarat Nikah">
        </div>

        <div id="section-domisili" class="hidden animate-fade bg-gray-50 p-4 rounded border space-y-3">
            <h4 class="font-bold text-blue-600">Alamat Domisili Sekarang</h4>
            <p class="text-xs text-gray-500">Isi jika alamat tinggal sekarang berbeda dengan KTP. Jika sama, kosongkan saja.</p>
            <textarea name="alamat_domisili" rows="2" class="w-full border rounded px-3 py-2" placeholder="Alamat tempat tinggal saat ini..."></textarea>
        </div>

        <div id="section-usaha" class="hidden animate-fade bg-gray-50 p-4 rounded border space-y-3">
            <h4 class="font-bold text-blue-600">Data Usaha</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="nama_usaha" placeholder="Nama Usaha (Contoh: Kios Bu Aminah)" class="w-full border rounded px-3 py-2">
                <input type="text" name="jenis_usaha" placeholder="Jenis Usaha (Contoh: Sembako)" class="w-full border rounded px-3 py-2">
            </div>
            <textarea name="alamat_usaha" rows="2" placeholder="Alamat Lokasi Usaha" class="w-full border rounded px-3 py-2"></textarea>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="mulai_usaha" placeholder="Mulai Usaha (Tahun/Tanggal)" class="w-full border rounded px-3 py-2">
                <input type="text" name="luas_usaha" placeholder="Luas Tempat (Opsional)" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div id="section-kegiatan" class="hidden animate-fade bg-gray-50 p-4 rounded border space-y-3">
            <h4 class="font-bold text-blue-600">Detail Acara / Kegiatan</h4>
            <input type="text" name="nama_kegiatan" placeholder="Nama Acara (Contoh: Resepsi Pernikahan)" class="w-full border rounded px-3 py-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="date" name="tanggal_kegiatan" class="w-full border rounded px-3 py-2">
                <input type="text" name="waktu_kegiatan" placeholder="Waktu (Contoh: 08.00 - Selesai)" class="w-full border rounded px-3 py-2">
            </div>
            <input type="text" name="tempat_kegiatan" placeholder="Tempat Pelaksanaan" class="w-full border rounded px-3 py-2">
            <input type="text" name="hiburan" placeholder="Hiburan (Jika ada, Contoh: Organ Tunggal)" class="w-full border rounded px-3 py-2">
        </div>

        <div id="section-beasiswa" class="hidden animate-fade bg-gray-50 p-4 rounded border space-y-3">
            <h4 class="font-bold text-blue-600">Data Pendidikan & Beasiswa</h4>
            <input type="text" name="nama_beasiswa" placeholder="Nama Program Beasiswa" class="w-full border rounded px-3 py-2">
            <input type="text" name="nama_ayah" placeholder="Nama Orang Tua (Ayah)" class="w-full border rounded px-3 py-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="sekolah" placeholder="Nama Sekolah / Kampus" class="w-full border rounded px-3 py-2">
                <input type="text" name="nis_nim" placeholder="NIS / NIM" class="w-full border rounded px-3 py-2">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="jurusan" placeholder="Jurusan / Prodi" class="w-full border rounded px-3 py-2">
                <input type="text" name="semester" placeholder="Kelas / Semester" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 rounded-lg shadow-lg transition duration-300">
            CETAK SURAT SEKARANG
        </button>

    </form>
</div>

<script>
    function toggleForm() {
        const jenis = document.getElementById('jenis').value;
        
        // Daftar ID section khusus
        const sections = [
            'section-keperluan', 
            'section-domisili', 
            'section-usaha', 
            'section-kegiatan', 
            'section-beasiswa'
        ];

        // Sembunyikan semua section dulu
        sections.forEach(id => document.getElementById(id).classList.add('hidden'));

        // Logika Menampilkan Section
        if (['keterangan_domisili', 'keterangan_tidak_mampu', 'belum_menikah'].includes(jenis)) {
            document.getElementById('section-keperluan').classList.remove('hidden');
        }

        if (jenis === 'keterangan_domisili') {
            document.getElementById('section-domisili').classList.remove('hidden');
        }

        if (jenis === 'keterangan_usaha' || jenis === 'izin_usaha') {
            document.getElementById('section-usaha').classList.remove('hidden');
        }

        if (jenis === 'izin_kegiatan') {
            document.getElementById('section-kegiatan').classList.remove('hidden');
        }

        if (jenis === 'rekomendasi_beasiswa') {
            document.getElementById('section-beasiswa').classList.remove('hidden');
        }
    }
</script>

</body>
</html>