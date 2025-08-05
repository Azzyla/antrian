<?php
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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Kepuasan Layanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-primary fw-bold">Rekap Kepuasan Layanan</h2>

    <a href="<?= base_url('/kepala/dashboard') ?>" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>

    <!-- Form Filter -->
    <form method="get" class="row g-3 align-items-end mb-3">
        <div class="col-md-2">
            <label for="start" class="form-label">Dari Tanggal</label>
            <input type="date" name="start" id="start" class="form-control" value="<?= esc($start ?? '') ?>">
        </div>
        <div class="col-md-2">
            <label for="end" class="form-label">Sampai Tanggal</label>
            <input type="date" name="end" id="end" class="form-control" value="<?= esc($end ?? '') ?>">
        </div>
        <div class="col-md-2">
            <label for="bulan" class="form-label">Bulan</label>
            <select name="bulan" id="bulan" class="form-select">
                <option value="">Semua</option>
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= $i ?>" <?= (isset($bulan) && $bulan == $i) ? 'selected' : '' ?>>
                        <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label for="cs" class="form-label">Customer Service</label>
            <select name="cs" id="cs" class="form-select">
                <option value="">Semua</option>
                <?php if (!empty($listCS)) : ?>
                    <?php foreach ($listCS as $csItem): ?>
                <option value="<?= esc($csItem['nama']) ?>" <?= (isset($cs) && $cs == $csItem['nama']) ? 'selected' : '' ?>>
                 <?= esc($csItem['nama']) ?>
            </option>
            <?php endforeach; ?>

                <?php endif; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label for="limitSelect" class="form-label">Jumlah Data</label>
            <select name="limit" id="limitSelect" class="form-select">
                <?php foreach ([10, 25, 50, 100] as $opt): ?>
                    <option value="<?= $opt ?>" <?= (isset($limit) && $limit == $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <!-- Tabel Rekap -->
    <table class="table table-bordered table-striped" id="rekapTable">
        <thead class="table-dark text-center">
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>CS</th>
            <th>Penilaian</th>
            <th>Waktu Isi</th>
            <th>Durasi (menit)</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($kepuasan)): ?>
            <?php $no = 1; foreach ($kepuasan as $row): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= esc($row['nim'] ?? '-') ?></td>
                    <td><?= esc($row['nama_cs'] ?? '-') ?></td>

                    <td>
                        <?= esc($row['penilaian'] ?? '-') ?>/5 -
                        <?= penilaianLabel($row['penilaian'] ?? 0) ?>
                    </td>
                   
                    <td>
                        <?= !empty($row['created_at']) ? date('d-m-Y H:i', strtotime($row['created_at'])) : '-' ?>
                    </td>
                    <td>
                        <?php
                        if (!empty($row['waktu_mulai']) && !empty($row['waktu_selesai'])) {
                            try {
                                $mulai = new \DateTime($row['waktu_mulai']);
                                $selesai = new \DateTime($row['waktu_selesai']);
                                echo round(($selesai->getTimestamp() - $mulai->getTimestamp()) / 60) . ' menit';
                            } catch (\Exception $e) {
                                echo '-';
                            }
                        } else {
                            echo '-';
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center">Tidak ada data untuk filter yang dipilih.</td>
            </tr>
        <?php endif ?>
        </tbody>
    </table>
</div>
</body>
</html>
