<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Kepala PLT</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <nav class="bg-dark text-white p-3 d-flex flex-column" style="width: 250px; height: 100vh;">
      <div class="text-center mb-4">
        <h4>Sistem Antrian</h4>
      </div>
      <div class="text-center mb-4">
        <img src="<?= base_url('logo_itp.png') ?>" alt="Logo" width="80">
      </div>
      <ul class="nav flex-column mb-5">
        <li class="nav-item">
          <a class="nav-link text-white" href="<?= base_url('kepala/dashboard') ?>">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="<?= base_url('kepala/rekap_antrian') ?>">Rekap Antrian</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="<?= base_url('kepala/rekap_kepuasan') ?>">Rekap Kepuasan</a>
        </li>
      </ul>
      <div class="mt-auto">
        <a href="<?= base_url('kepala/logout') ?>" class="btn btn-outline-light w-100">Logout</a>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1 p-4">
      <h2 class="text-primary mb-4">Selamat Datang, Kepala PLT</h2>

      <div class="row mt-5">
        <!-- Grafik Kepuasan -->
        <div class="col-md-4 mb-4">
          <div class="card shadow">
            <div class="card-header bg-primary text-white">Grafik Kepuasan Layanan</div>
            <div class="card-body">
              <canvas id="grafikKepuasan" height="200"></canvas>
            </div>
          </div>
        </div>

        <!-- Grafik per CS -->
        <div class="col-md-4 mb-4">
          <div class="card shadow">
            <div class="card-header bg-secondary text-white">Grafik Penilaian per Jenis CS</div>
            <div class="card-body">
              <canvas id="grafikPerCS" height="200"></canvas>
            </div>
          </div>
        </div>

        <!-- Grafik CS Terbaik -->
        <div class="col-md-4 mb-4">
          <div class="card shadow">
            <div class="card-header bg-success text-white">Grafik CS Terbaik per Bulan</div>
            <div class="card-body">
              <canvas id="grafikCSTerbaik" height="200"></canvas>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Script Chart -->
<script>
  const kategoriLabels = ['Sangat Buruk', 'Buruk', 'Cukup', 'Baik', 'Sangat Baik'];
  const kategoriColors = ['#e74c3c', '#e67e22', '#f1c40f', '#2ecc71', '#3498db'];
  const csLabels = ['CS 1', 'CS 2', 'CS 3'];

  const dataKepuasan = <?= json_encode([
    $penilaian['Sangat Buruk'] ?? 0,
    $penilaian['Buruk'] ?? 0,
    $penilaian['Cukup'] ?? 0,
    $penilaian['Baik'] ?? 0,
    $penilaian['Sangat Baik'] ?? 0
  ]) ?>;

  const dataPerCS = <?= json_encode($penilaianPerCS) ?>;

  const datasetsPerCS = kategoriLabels.map((kategori, idx) => ({
    label: kategori,
    data: csLabels.map(cs => dataPerCS[cs]?.[kategori] ?? 0),
    backgroundColor: kategoriColors[idx]
  }));

  const csTerbaikLabels = <?= json_encode($grafikBerjalanLabels ?? []) ?>;
  const grafikBerjalanValues = <?= json_encode($grafikBerjalanValues ?? []) ?>;

  const datasetsCSBerjalan = Object.keys(grafikBerjalanValues).map(cs => ({
    label: cs,
    data: grafikBerjalanValues[cs],
    backgroundColor:
      cs === 'CS 1' ? '#3498db' :
      cs === 'CS 2' ? '#2ecc71' : '#e67e22'
  }));

  new Chart(document.getElementById('grafikKepuasan'), {
    type: 'bar',
    data: {
      labels: kategoriLabels,
      datasets: [{
        label: 'Jumlah Penilaian',
        data: dataKepuasan,
        backgroundColor: kategoriColors,
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        title: { display: true, text: 'Grafik Kepuasan Layanan' }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { precision: 0 }
        }
      }
    }
  });

  new Chart(document.getElementById('grafikPerCS'), {
    type: 'bar',
    data: {
      labels: csLabels,
      datasets: datasetsPerCS
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: 'Jumlah Penilaian per Jenis CS dan Kategori'
        }
      },
      scales: {
        x: { stacked: true },
        y: { stacked: true, beginAtZero: true, ticks: { precision: 0 } }
      }
    }
  });

  new Chart(document.getElementById('grafikCSTerbaik'), {
    type: 'bar',
    data: {
      labels: csTerbaikLabels,
      datasets: datasetsCSBerjalan
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: 'Jumlah Nilai 5 (Sangat Baik) per CS tiap Bulan'
        }
      },
      scales: {
        x: { stacked: true },
        y: { stacked: true, beginAtZero: true, ticks: { precision: 0 } }
      }
    }
  });
</script>

</body>
</html>
