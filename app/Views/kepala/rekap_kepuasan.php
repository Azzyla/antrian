<?php
// Fungsi untuk mengubah angka penilaian menjadi label
function penilaianLabel($nilai)
{
    switch ((int)$nilai) {
        case 1: return 'Sangat Buruk';
        case 2: return 'Buruk';
        case 3: return 'Cukup';
        case 4: return 'Baik';
        case 5: return 'Sangat Baik';
        default: return 'Tidak Diketahui';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rekap Kepuasan Layanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4 text-primary">Rekap Kepuasan Layanan</h2>

    <!-- Form Filter Tanggal -->
    <form method="get" class="row g-3 mb-3">
        <div class="col-md-4">
            <label for="start">Dari Tanggal</label>
            <input type="date" name="start" id="start" class="form-control" value="<?= esc($start ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label for="end">Sampai Tanggal</label>
            <input type="date" name="end" id="end" class="form-control" value="<?= esc($end ?? '') ?>">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">Tampilkan</button>
            <a href="<?= base_url('/kepala/rekap-kepuasan') ?>" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <a href="<?= base_url('/kepala/dashboard') ?>" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>

    <!-- Tabel Kepuasan -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>CS</th>
                <th>Penilaian</th>
                <th>Saran</th>
                <th>Waktu Isi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($kepuasan)): ?>
                <?php $no = 1; foreach ($kepuasan as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($row['nim']) ?></td>
                        <td><?= esc($row['cs']) ?></td>
                        <td>
                            <?= esc($row['penilaian']) ?>/5 - <?= penilaianLabel($row['penilaian']) ?>
                        </td>
                        <td><?= esc($row['saran']) ?></td>
                        <td><?= esc($row['created_at']) ?></td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data untuk rentang tanggal ini.</td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>
</div>
</body>
</html>
