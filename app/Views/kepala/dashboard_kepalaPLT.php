<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Kepala PLT</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <nav class="bg-dark text-white p-3 d-flex flex-column" style="width: 250px; height: 100vh;">
      <div class="text-center mb-4">
        <h4>Sistem Antrian</h4>
      </div>
      <div class="text-center mb-4">
        <img src="<?= base_url('/logo_itp.png') ?>" alt="Logo" width="80">
      </div>
      <ul class="nav flex-column mb-5">
        <li class="nav-item">
          <a class="nav-link text-white" href="<?= base_url('/kepala/dashboard') ?>">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="<?= base_url('/kepala/rekap-antrian') ?>">Rekap Antrian</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="<?= base_url('/kepala/rekap-kepuasan') ?>">Rekap Kepuasan</a>
        </li>
      </ul>
      <div class="mt-auto">
        <a href="<?= base_url('/kepala/logout') ?>" class="btn btn-outline-light w-100">Logout</a>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1 p-4">
      <h2 class="text-primary mb-4">Selamat Datang, Kepala PLT</h2>

      <!-- Ringkasan atau konten dashboard -->
      <p>Silakan gunakan menu di samping untuk melihat rekap data antrian dan kepuasan layanan.</p>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
