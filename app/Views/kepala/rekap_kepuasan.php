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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Kepuasan Layanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .pagination {
            text-align: center;
            margin-top: 15px;
        }

        .page-btn {
            padding: 6px 12px;
            margin: 2px;
            border: none;
            background-color: #f0f0f0;
            cursor: pointer;
            border-radius: 4px;
        }

        .page-btn:hover {
            background-color: #007bff;
            color: white;
        }

        .page-btn.active {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-primary fw-bold">Rekap Kepuasan Layanan</h2>

    <!-- Tombol kembali -->
    <a href="<?= base_url('/kepala/dashboard') ?>" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>

    <!-- Form filter tanggal dan jumlah entri -->
    <form method="get" class="row g-3 align-items-end mb-3">
        <div class="col-md-3">
            <label for="start" class="form-label">Dari Tanggal</label>
            <input type="date" name="start" id="start" class="form-control" value="<?= esc($start ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label for="end" class="form-label">Sampai Tanggal</label>
            <input type="date" name="end" id="end" class="form-control" value="<?= esc($end ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label for="limitSelect" class="form-label">Jumlah Data</label>
            <select name="limit" id="limitSelect" class="form-select form-select-lg" onchange="changeLimit()">
                <?php foreach ([10, 25, 50, 100] as $opt): ?>
                    <option value="<?= $opt ?>" <?= isset($_GET['limit']) && $_GET['limit'] == $opt ? 'selected' : '' ?>><?= $opt ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Tampilkan</button>
            <a href="<?= base_url('/kepala/rekap-kepuasan') ?>" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <!-- Tabel -->
    <table class="table table-bordered table-striped" id="rekapTable">
        <thead class="table-dark text-center">
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
                    <td><?= esc($row['penilaian']) ?>/5 - <?= penilaianLabel($row['penilaian']) ?></td>
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

    <!-- Pagination -->
    <div id="pagination" class="pagination"></div>
</div>

<!-- Script Pagination -->
<script>
    const table = document.getElementById("rekapTable");
    const rows = table.querySelector("tbody").getElementsByTagName("tr");
    const limitSelect = document.getElementById("limitSelect");
    const pagination = document.getElementById("pagination");

    let currentPage = 1;
    let rowsPerPage = parseInt(limitSelect.value);

    function displayTable(page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        for (let i = 0; i < rows.length; i++) {
            rows[i].style.display = (i >= start && i < end) ? "" : "none";
        }

        displayPagination();
    }

    function displayPagination() {
        pagination.innerHTML = "";
        const totalPages = Math.ceil(rows.length / rowsPerPage);

        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement("button");
            btn.innerText = i;
            btn.className = "page-btn" + (i === currentPage ? " active" : "");
            btn.onclick = function () {
                currentPage = i;
                displayTable(currentPage);
            };
            pagination.appendChild(btn);
        }
    }

    function changeLimit() {
        document.querySelector("form").submit(); // langsung submit form untuk terapkan limit
    }

    // Inisialisasi saat halaman dimuat
    window.onload = function () {
        displayTable(currentPage);
    };
</script>
</body>
</html>
