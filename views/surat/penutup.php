<?php if (($mode ?? 'preview') === 'final'): ?>
    <br><br>
    <div style="text-align:right;">
        Kepala Desa <?= htmlspecialchars($desa) ?><br><br><br>
        <strong><?= htmlspecialchars($kepala_desa) ?></strong>
    </div>
<?php endif; ?>
