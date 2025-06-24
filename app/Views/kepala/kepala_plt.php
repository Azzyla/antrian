<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Antrian Kepala PLT</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
  <h2 class="text-center mb-4">Laporan Antrian Hari Ini</h2>
  <div class="text-end mb-3">
    <a href="/kepala/logout" class="btn btn-danger btn-sm">Logout</a>
  </div>

  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>No</th>
        <th>Pengunjung</th>
        <th>Nomor Antrian</th>
        <th>Status</th>
        <th>Durasi Pelayanan</th>
        <th>CS yang Melayani</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($laporan)): ?>
        <?php $no = 1; foreach ($laporan as $row): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= esc($row['kategori']) ?></td>
            <td><?= esc($row['nomor_antrian']) ?></td>
            <td><?= esc(ucfirst($row['status'])) ?></td>
            <td>
              <?php
                if (!empty($row['waktu_mulai']) && !empty($row['waktu_selesai'])) {
                    $mulai = \CodeIgniter\I18n\Time::parse($row['waktu_mulai']);
                    $selesai = \CodeIgniter\I18n\Time::parse($row['waktu_selesai']);
                    $durasi = $selesai->difference($mulai);
                    echo $durasi->getMinutes() . ' menit ' . $durasi->getSeconds() % 60 . ' detik';
                } else {
                    echo '-';
                }
              ?>
            </td>
            <td><?= esc($row['nama_cs']) ?? '-' ?></td>
          </tr>
        <?php endforeach ?>
      <?php else: ?>
        <tr>
          <td colspan="6" class="text-center">Tidak ada data antrian hari ini.</td>
        </tr>
      <?php endif ?>
    </tbody>
  </table>
</div>
</body>
</html>
