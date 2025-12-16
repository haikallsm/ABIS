<?php
/**
 * Admin Letter Request Detail View
 * Shows detailed information about a letter request
 */
?>

<div class="cream-card p-6">
    <div class="border-b pb-4 cream-border mb-6">
        <h1 class="text-2xl font-bold text-dark">Detail Pengajuan Surat</h1>
        <p class="text-gray-600 mt-1">Informasi lengkap pengajuan surat</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Request Information -->
        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-semibold text-dark mb-3">Informasi Pengajuan</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nomor Pengajuan:</span>
                        <span class="font-medium">#<?php echo htmlspecialchars($request['id']); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Pengajuan:</span>
                        <span class="font-medium"><?php echo date('d/m/Y H:i', strtotime($request['created_at'])); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jenis Surat:</span>
                        <span class="font-medium"><?php echo htmlspecialchars($letterType['name'] ?? 'Tidak diketahui'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            <?php
                            switch($request['status']) {
                                case STATUS_PENDING:
                                    echo 'bg-yellow-100 text-yellow-800';
                                    break;
                                case STATUS_APPROVED:
                                    echo 'bg-green-100 text-green-800';
                                    break;
                                case STATUS_REJECTED:
                                    echo 'bg-red-100 text-red-800';
                                    break;
                                default:
                                    echo 'bg-gray-100 text-gray-800';
                            }
                            ?>">
                            <?php
                            switch($request['status']) {
                                case STATUS_PENDING:
                                    echo 'Menunggu';
                                    break;
                                case STATUS_APPROVED:
                                    echo 'Disetujui';
                                    break;
                                case STATUS_REJECTED:
                                    echo 'Ditolak';
                                    break;
                                default:
                                    echo $request['status'];
                            }
                            ?>
                        </span>
                    </div>

                    <?php if ($request['status'] !== STATUS_PENDING): ?>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Diproses Pada:</span>
                        <span class="font-medium"><?php echo date('d/m/Y H:i', strtotime($request['approved_at'])); ?></span>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($request['letter_number'])): ?>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nomor Surat:</span>
                        <span class="font-medium"><?php echo htmlspecialchars($request['letter_number']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Request Data -->
            <?php if (!empty($request['request_data'])): ?>
            <div>
                <h3 class="text-lg font-semibold text-dark mb-3">Data Permohonan</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <?php
                    $requestData = json_decode($request['request_data'], true);
                    if ($requestData):
                    ?>
                    <div class="space-y-2">
                        <?php foreach ($requestData as $key => $value): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600 capitalize"><?php echo str_replace('_', ' ', $key); ?>:</span>
                            <span class="font-medium"><?php echo htmlspecialchars($value); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-gray-500">Tidak ada data tambahan</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Admin Notes -->
            <?php if (!empty($request['admin_notes'])): ?>
            <div>
                <h3 class="text-lg font-semibold text-dark mb-3">Catatan Admin</h3>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-blue-800"><?php echo nl2br(htmlspecialchars($request['admin_notes'])); ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- User Information -->
        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-semibold text-dark mb-3">Informasi Pemohon</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama Lengkap:</span>
                        <span class="font-medium"><?php echo htmlspecialchars($user['full_name'] ?? 'Tidak diketahui'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">NIK:</span>
                        <span class="font-medium"><?php echo htmlspecialchars($user['nik'] ?? '-'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-medium"><?php echo htmlspecialchars($user['email'] ?? '-'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">No. HP:</span>
                        <span class="font-medium"><?php echo htmlspecialchars($user['phone'] ?? '-'); ?></span>
                    </div>
                    <?php if (!empty($user['address'])): ?>
                    <div>
                        <span class="text-gray-600 block mb-1">Alamat:</span>
                        <span class="font-medium"><?php echo nl2br(htmlspecialchars($user['address'])); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Bergabung:</span>
                        <span class="font-medium"><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div>
                <h3 class="text-lg font-semibold text-dark mb-3">Aksi</h3>
                <div class="space-y-2">
                    <?php if ($request['status'] === STATUS_PENDING): ?>
                    <button onclick="approveRequest(<?php echo $request['id']; ?>)"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        ✅ Setujui Pengajuan
                    </button>
                    <button onclick="rejectRequest(<?php echo $request['id']; ?>)"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        ❌ Tolak Pengajuan
                    </button>
                    <?php endif; ?>

                    <?php if ($request['status'] === STATUS_APPROVED && !empty($request['generated_file'])): ?>
                    <a href="<?php echo BASE_URL; ?>/admin/requests/<?php echo $request['id']; ?>/download"
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors text-center block">
                        📄 Download PDF
                    </a>
                    <?php endif; ?>

                    <a href="<?php echo BASE_URL; ?>/admin/requests"
                       class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors text-center block">
                        ← Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Approve request function
function approveRequest(requestId) {
    if (!confirm('Apakah Anda yakin ingin menyetujui pengajuan surat ini?')) {
        return;
    }

    const notes = prompt('Catatan persetujuan (opsional):');
    if (notes === null) return; // User cancelled

    fetch(`<?php echo BASE_URL; ?>/admin/requests/${requestId}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `notes=${encodeURIComponent(notes)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Pengajuan surat berhasil disetujui!');
            location.reload();
        } else {
            alert('Gagal menyetujui pengajuan: ' + data.message);
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan: ' + error.message);
    });
}

// Reject request function
function rejectRequest(requestId) {
    const notes = prompt('Alasan penolakan (wajib):');
    if (!notes || notes.trim() === '') {
        alert('Alasan penolakan harus diisi!');
        return;
    }

    fetch(`<?php echo BASE_URL; ?>/admin/requests/${requestId}/reject`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `notes=${encodeURIComponent(notes)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Pengajuan surat berhasil ditolak!');
            location.reload();
        } else {
            alert('Gagal menolak pengajuan: ' + data.message);
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan: ' + error.message);
    });
}
</script>
