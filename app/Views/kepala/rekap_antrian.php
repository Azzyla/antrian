<!DOCTYPE html>
<html>
<head>
    <title>Rekap Antrian - Kepala PLT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4 text-success">Rekap Antrian</h2>
    <a href="<?= base_url('/kepala/dashboard') ?>" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>

    <!-- Filter Tanggal -->
    <form method="get" action="<?= base_url('/kepala/rekap-antrian') ?>" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="start" class="form-label">Tanggal Awal</label>
            <input type="date" name="start" id="start" class="form-control" value="<?= esc($_GET['start'] ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label for="end" class="form-label">Tanggal Akhir</label>
            <input type="date" name="end" id="end" class="form-control" value="<?= esc($_GET['end'] ?? '') ?>">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>

    <!-- Tabel Rekap -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Nomor Antrian</th>
                <th>Status</th>
                <th>CS</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
                <th>Durasi (menit)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($laporan) === 0): ?>
                <tr><td colspan="9" class="text-center">Tidak ada data.</td></tr>
            <?php else: ?>
                <?php $no = 1; foreach ($laporan as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= date('d-m-Y', strtotime($row['created_at'])) ?></td>
                        <td><?= esc($row['kategori']) ?></td>
                        <td><?= esc($row['nomor_antrian'] ?? $row['id']) ?></td>
                        <td><?= esc($row['status'] ?? '-') ?></td>
                        <td><?= esc($row['nama_cs'] ?? '-') ?></td>
                        <td><?= $row['waktu_mulai'] ? date('H:i', strtotime($row['waktu_mulai'])) : '-' ?></td>
                        <td><?= $row['waktu_selesai'] ? date('H:i', strtotime($row['waktu_selesai'])) : '-' ?></td>
                        <td>
                            <?php
                            if ($row['waktu_mulai'] && $row['waktu_selesai']) {
                                $mulai = new \DateTime($row['waktu_mulai']);
                                $selesai = new \DateTime($row['waktu_selesai']);
                                $durasi = $selesai->getTimestamp() - $mulai->getTimestamp();
                                echo round($durasi / 60) . ' menit';
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
