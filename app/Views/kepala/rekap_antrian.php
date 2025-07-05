<!DOCTYPE html>
<html>
<head>
    <title>Rekap Antrian - Kepala PLT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 4px;
            text-decoration: none;
            background-color: #f0f0f0;
            color: #333;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .pagination a.active {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-primary fw-bold">Rekap Antrian</h2>
    <a href="<?= base_url('/kepala/dashboard') ?>" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>

    <!-- Filter Tanggal & Per Page -->
    <form method="get" action="<?= base_url('/kepala/rekap-antrian') ?>" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="start" class="form-label">Tanggal Awal</label>
            <input type="date" name="start" id="start" class="form-control" value="<?= esc($_GET['start'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label for="end" class="form-label">Tanggal Akhir</label>
            <input type="date" name="end" id="end" class="form-control" value="<?= esc($_GET['end'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label for="perPage" class="form-label">Jumlah Data</label>
            <select name="perPage" id="perPage" class="form-select" onchange="this.form.submit()">
                <option value="10" <?= ($perPage == 10) ? 'selected' : '' ?>>10</option>
                <option value="25" <?= ($perPage == 25) ? 'selected' : '' ?>>25</option>
                <option value="50" <?= ($perPage == 50) ? 'selected' : '' ?>>50</option>
                <option value="100" <?= ($perPage == 100) ? 'selected' : '' ?>>100</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
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
            <th>NIM</th>
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
            <tr><td colspan="10" class="text-center">Tidak ada data.</td></tr>
        <?php else: ?>
            <?php $no = ($currentPage - 1) * $perPage + 1; foreach ($laporan as $row): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= date('d-m-Y', strtotime($row['created_at'])) ?></td>
                    <td><?= esc($row['kategori']) ?></td>
                    <td>
                        <?php
                        if ($row['kategori'] === 'mahasiswa') {
                            echo esc($row['nim'] ?? '-');
                        } else {
                            echo '-';
                        }
                        ?>
                    </td>
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

    <!-- Pagination Number -->
    <div class="pagination">
        <?php
        $totalPages = ceil($totalData / $perPage);
        for ($i = 1; $i <= $totalPages; $i++): 
            $queryStr = http_build_query([
                'start' => $start,
                'end' => $end,
                'perPage' => $perPage,
                'page' => $i
            ]);
        ?>
            <a href="?<?= $queryStr ?>" class="<?= ($i == $currentPage) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
</div>
</body>
</html>
