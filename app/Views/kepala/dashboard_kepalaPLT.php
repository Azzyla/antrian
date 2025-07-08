<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Kepala PLT</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    body, html {
      height: 100%;
      margin: 0;
    }

    .sidebar {
      width: 250px;
      background-color: #343a40;
      color: white;
      padding: 1rem;
      min-height: 100vh;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      margin-bottom: 1rem;
    }

    .sidebar a:hover {
      text-decoration: underline;
    }

    .chart-container {
      position: relative;
      width: 100%;
      max-width: 500px; /* Membatasi lebar grafik */
      height: 200px;     /* Membatasi tinggi grafik */
      margin: auto;
    }

    .card-header {
      font-size: 0.95rem;
      padding: 0.6rem 1rem;
    }

    .card-body {
      padding: 0.6rem 1rem;
      display: flex;
      justify-content: center;
    }

    @media (max-width: 768px) {
      .chart-container {
        max-width: 100%;
        height: 180px;
      }

      .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
      }

      .sidebar {
        width: 100%;
        min-height: auto;
      }
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="text-center mb-4">
        <h4>Sistem Antrian</h4>
        <img src="<?= base_url('logo_itp.png') ?>" width="80" alt="Logo">
      </div>
      <a href="<?= base_url('kepala/dashboard') ?>">Dashboard</a>
      <a href="<?= base_url('kepala/rekap_antrian') ?>">Rekap Antrian</a>
      <a href="<?= base_url('kepala/rekap_kepuasan') ?>">Rekap Kepuasan</a>
      <a href="<?= base_url('kepala/logout') ?>" class="btn btn-outline-light w-100 mt-5">Logout</a>
    </div>

    <!-- Main Content -->
    <main class="flex-grow-1 p-4">
      <h2 class="text-primary mb-4">Selamat Datang, Kepala PLT</h2>

      <div class="row">
        <!-- Grafik Kepuasan -->
        <div class="col-md-6 mb-4">
          <div class="card shadow">
            <div class="card-header bg-primary text-white">Grafik Kepuasan Layanan</div>
            <div class="card-body">
              <div class="chart-container">
                <canvas id="grafikKepuasan"></canvas>
              </div>
            </div>
          </div>
        </div>

        <!-- Grafik Penilaian per Jenis CS -->
        <div class="col-md-6 mb-4">
          <div class="card shadow">
            <div class="card-header bg-secondary text-white">Grafik Penilaian per Jenis CS</div>
            <div class="card-body">
              <div class="chart-container">
                <canvas id="grafikPerCS"></canvas>
              </div>
            </div>
          </div>
        </div>

        <!-- Grafik CS Terbaik per Bulan -->
        <div class="col-md-6 mb-4">
          <div class="card shadow">
            <div class="card-header bg-success text-white">Grafik CS Terbaik per Bulan</div>
            <div class="card-body">
              <div class="chart-container">
                <canvas id="grafikCSTerbaik"></canvas>
              </div>
            </div>
          </div>
        </div>

        <!-- Grafik Jenis Layanan -->
        <div class="col-md-6 mb-4">
          <div class="card shadow">
            <div class="card-header bg-warning text-dark">Grafik Jenis Layanan</div>
            <div class="card-body">
              <div class="chart-container">
                <canvas id="grafikLayanan"></canvas>
              </div>
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

    const layananLabels = <?= json_encode($layananLabels ?? []) ?>;
    const layananValues = <?= json_encode($layananValues ?? []) ?>;

    new Chart(document.getElementById('grafikKepuasan'), {
      type: 'bar',
      data: {
        labels: kategoriLabels,
        datasets: [{
          label: 'Jumlah Penilaian',
          data: dataKepuasan,
          backgroundColor: kategoriColors,
          borderWidth: 1,
          barThickness: 20
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          title: { display: true, text: 'Grafik Kepuasan Layanan' }
        },
        scales: {
          y: { beginAtZero: true, ticks: { precision: 0 } }
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
        maintainAspectRatio: false,
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
        maintainAspectRatio: false,
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

    new Chart(document.getElementById('grafikLayanan'), {
      type: 'bar',
      data: {
        labels: layananLabels,
        datasets: [{
          label: 'Jumlah Pengambil Layanan',
          data: layananValues,
          backgroundColor: '#8e44ad',
          barThickness: 20
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          title: {
            display: true,
            text: 'Jumlah Pengambil per Jenis Layanan'
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { precision: 0 }
          }
        }
      }
    });
  </script>
</body>
</html>
