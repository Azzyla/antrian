<?php
$csRole = session('role'); // contoh: cs1, cs2, cs3
$csName = session('name'); // contoh: robi, riska, dll

// Format untuk ditampilkan: Robi CS3
$csRoleFormatted = strtoupper($csRole ?? '');
$csDisplay = ucfirst($csName ?? 'Guest') . ' ' . $csRoleFormatted;
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rekap Antrian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
      .cs-info {
        background-color: #6c757d;
        border-radius: 5px;
        padding: 6px;
        color: white;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: center;
      }
    </style>
  </head>
  <body>
    <div class="d-flex">
      <!-- Sidebar -->
      <div class="bg-dark text-white d-flex flex-column p-3 position-relative" style="width: 250px; height: 100vh;">
        <div class="text-center mb-3">
          <h4 class="text-white">Sistem Antrian</h4>
        </div>
        <div class="text-center mb-2">
          <img src="/logo_itp.png" alt="Logo" width="80">
        </div>

        <!-- Nama & Role CS -->
        <div class="cs-info">
          <?= $csDisplay ?>
        </div>

        <!-- Menu Navigasi -->
        <ul class="nav flex-column mb-5">
          <li class="nav-item">
            <a class="nav-link text-white" href="/dashboard">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="/panggilan">Panggil Antrian</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="/referensi">Referensi Tujuan</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="/layar">Layar Antrian</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="/rekap">Rekap Antrian</a>
          </li>
        </ul>

        <!-- Tombol Logout -->
        <div class="mt-auto position-absolute bottom-0 start-0 w-100 p-3">
          <a href="/logout" class="btn btn-outline-light w-100">Logout</a>
        </div>
      </div>

      <!-- Main Content (Rekap Antrian) -->
      <div class="flex-grow-1 p-4">
        <h3 class="mb-4">Rekap Antrian</h3>

        <!-- Form Filter -->
        <form method="get" action="<?= base_url('rekap') ?>" class="row g-3">
          <div class="col-md-3">
            <label>Range Tanggal</label>
            <input type="date" name="start_date" value="<?= $start_date ?>" class="form-control">
          </div>
          <div class="col-md-3">
            <label>s/d</label>
            <input type="date" name="end_date" value="<?= $end_date ?>" class="form-control">
          </div>
          <div class="col-md-3">
            <label>Status Pengunjung</label>
            <select name="status" class="form-control">
              <option value="Semua" <?= $status == 'Semua' ? 'selected' : '' ?>>Semua</option>
              <option value="Mahasiswa" <?= $status == 'Mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
              <option value="Dosen" <?= $status == 'Dosen' ? 'selected' : '' ?>>Dosen</option>
              <option value="Umum" <?= $status == 'Umum' ? 'selected' : '' ?>>Umum</option>
            </select>
          </div>
          <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary">Tampilkan</button>
          </div>
        </form>

        <!-- Tabel Rekap -->
        <table class="table table-bordered table-striped mt-4">
          <thead>
            <tr>
              <th>No</th>
              <th>Status Pengunjung</th>
              <th>Jumlah Antrian</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; foreach ($rekap as $row): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= esc(ucfirst($row->status_pengunjung)) ?></td>
              <td><?= esc($row->jumlah) ?></td>

              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  </body>
</html>
