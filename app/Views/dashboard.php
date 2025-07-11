<?php
$csRole = session('role'); // cs1, cs2, cs3
$csName = session('name'); // robi, riska, dll

// Ubah jadi huruf besar: cs3 → CS3
$csRoleFormatted = strtoupper($csRole ?? '');

// Gabung nama & role, contoh: Robi CS3
$csDisplay = ucfirst($csName ?? 'Guest') . ' ' . $csRoleFormatted;
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <nav class="bg-dark text-white p-3 d-flex flex-column" style="width: 250px; height: 100vh;">
      <div class="text-center mb-3">
        <h4>Sistem Antrian</h4>
      </div>
      <div class="text-center mb-3">
        <img src="/logo_itp.png" alt="Logo" width="80">
      </div>
      <div class="text-center mb-4">
        <div class="bg-secondary p-2 rounded small">
          <?= esc($csDisplay) ?>
        </div>
      </div>
      <ul class="nav flex-column mb-5">
        <li class="nav-item"><a class="nav-link text-white" href="/dashboard">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="/panggilan">Panggil Antrian</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="/layar">Layar Antrian</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="/rekap">Rekap Antrian</a></li>
      </ul>
      <div class="mt-auto">
        <a href="/logout" class="btn btn-outline-light w-100">Logout</a>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1 p-4">
      <h2 class="text-primary mb-4">Dashboard</h2>

      <div class="row">
        <!-- Jumlah Antrian Hari Ini -->
        <div class="col-md-4 mb-4">
          <div class="card text-center">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">Jumlah Antrian Hari Ini</h5>
            </div>
            <div class="card-body">
              <h4 id="jumlahAntrian"><?= esc($jumlahAntrian ?? 0) ?></h4>
            </div>
          </div>
        </div>

        <!-- Antrian Sudah Dilayani -->
        <div class="col-md-4 mb-4">
          <div class="card text-center">
            <div class="card-header bg-success text-white">
              <h5 class="mb-0">Antrian Sudah Dilayani</h5>
            </div>
            <div class="card-body">
              <h4 id="antrianSekarang"><?= esc($antrianSekarang ?? '-') ?></h4>
            </div>
          </div>
        </div>

        <!-- Antrian Selanjutnya -->
        <div class="col-md-4 mb-4">
          <div class="card text-center">
            <div class="card-header bg-warning text-dark">
              <h5 class="mb-0">Antrian Selanjutnya</h5>
            </div>
            <div class="card-body">
              <h4 id="antrianSelanjutnya"><?= esc($antrianSelanjutnya ?? '-') ?></h4>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
