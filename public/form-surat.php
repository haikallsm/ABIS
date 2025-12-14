<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Permohonan Surat</title>
    <link href="/public/assets/css/style.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen flex items-center justify-center">

<div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden">

    <!-- Header -->
    <div class="bg-blue-600 text-white px-8 py-6">
        <h1 class="text-2xl font-bold">Permohonan Surat Desa</h1>
        <p class="text-sm opacity-90 mt-1">
            Silakan lengkapi data berikut dengan benar
        </p>
    </div>

    <!-- Form -->
    <form action="cetak-surat.php" method="post" class="p-8 space-y-6">

        <!-- Jenis Surat -->
        <div>
            <label class="block font-semibold mb-1">Jenis Surat</label>
            <select name="jenis" id="jenis"
                class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-blue-300"
                onchange="toggleField()" required>
                <option value="">-- Pilih Jenis Surat --</option>
                <option value="keterangan_domisili">Keterangan Domisili</option>
                <option value="keterangan_tidak_mampu">Keterangan Tidak Mampu</option>
                <option value="keterangan_usaha">Keterangan Usaha</option>
                <option value="belum_menikah">Belum Menikah</option>
                <option value="izin_kegiatan">Izin Kegiatan</option>
                <option value="izin_usaha">Izin Usaha</option>
                <option value="rekomendasi_beasiswa">Rekomendasi Beasiswa</option>
            </select>
        </div>

        <!-- Divider -->
        <hr>

        <!-- Data Umum -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium">Nama Lengkap</label>
                <input type="text" name="nama"
                       class="w-full border rounded-lg px-4 py-2"
                       placeholder="Contoh: Andi Saputra" required>
            </div>

            <div>
                <label class="block font-medium">NIK</label>
                <input type="text" name="nik"
                       class="w-full border rounded-lg px-4 py-2"
                       placeholder="16 digit">
            </div>
        </div>

        <div>
            <label class="block font-medium">Alamat Lengkap</label>
            <textarea name="alamat"
                      class="w-full border rounded-lg px-4 py-2"
                      rows="3"></textarea>
        </div>

        <!-- Usaha -->
        <div id="usaha" class="hidden space-y-3 animate-fade">
            <h3 class="font-semibold text-blue-600">Data Usaha</h3>
            <input type="text" name="nama_usaha"
                   placeholder="Nama Usaha"
                   class="w-full border rounded-lg px-4 py-2">

            <input type="text" name="jenis_usaha"
                   placeholder="Jenis Usaha"
                   class="w-full border rounded-lg px-4 py-2">
        </div>

        <!-- Kegiatan -->
        <div id="kegiatan" class="hidden space-y-3 animate-fade">
            <h3 class="font-semibold text-blue-600">Data Kegiatan</h3>
            <input type="text" name="nama_kegiatan"
                   placeholder="Nama Kegiatan"
                   class="w-full border rounded-lg px-4 py-2">

            <input type="text" name="waktu"
                   placeholder="Waktu Pelaksanaan"
                   class="w-full border rounded-lg px-4 py-2">
        </div>

        <!-- Beasiswa -->
        <div id="beasiswa" class="hidden space-y-3 animate-fade">
            <h3 class="font-semibold text-blue-600">Data Pendidikan</h3>
            <input type="text" name="sekolah"
                   placeholder="Nama Sekolah"
                   class="w-full border rounded-lg px-4 py-2">
        </div>

        <!-- Submit -->
        <button
            class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold
                   hover:bg-blue-700 transition">
            Cetak Surat
        </button>

    </form>
</div>

<script>
function toggleField() {
    let jenis = document.getElementById('jenis').value;

    ['usaha', 'kegiatan', 'beasiswa'].forEach(id => {
        document.getElementById(id).classList.add('hidden');
    });

    if (jenis.includes('usaha')) {
        document.getElementById('usaha').classList.remove('hidden');
    }
    if (jenis === 'izin_kegiatan') {
        document.getElementById('kegiatan').classList.remove('hidden');
    }
    if (jenis === 'rekomendasi_beasiswa') {
        document.getElementById('beasiswa').classList.remove('hidden');
    }
}
</script>

</body>
</html>
