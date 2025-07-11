<?php
$csRole = session('role'); // cs1, cs2, cs3
$csName = session('name'); // robi, riska, dll

// Format untuk ditampilkan
$csRoleFormatted = strtoupper($csRole ?? '');
$csDisplay = ucfirst($csName ?? 'Guest') . ' ' . $csRoleFormatted;
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panggil Antrian</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
</head>

<body>
<div class="d-flex">
  <!-- Sidebar -->
  <nav class="bg-dark text-white p-3 d-flex flex-column" style="width: 250px; height: 100vh;">
    <div class="text-center mb-4">
      <h4 class="text-white">Sistem Antrian</h4>
      <img src="/logo_itp.png" alt="Logo" width="80" class="mb-2">
      <div class="bg-secondary rounded p-2 mt-2 text-white fw-bold">
        <?= $csDisplay ?>
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
    <h2 class="mb-4 text-center">Panggil Antrian Hari Ini</h2>

    <!-- Belum Dipanggil -->
    <div class="card mb-5">
      <div class="card-header bg-primary text-white"><strong>Belum Dipanggil</strong></div>
      <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Pengunjung</th>
              <th>Nomor Antrian</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; foreach ($belumDipanggil as $row): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= ucfirst($row['kategori']) ?></td>
                <td>
                  <?php
                    $huruf = $row['kategori'] === 'mahasiswa' ? 'A' : ($row['kategori'] === 'dosen' ? 'B' : 'C');
                    echo $huruf . str_pad($row['nomor_antrian'], 2, '0', STR_PAD_LEFT);
                  ?>
                </td>
                <td>
                  <a href="/panggilan/panggil/<?= $row['id'] ?>" class="btn btn-primary btn-sm"
                     onclick="panggil(this, 'panggil'); return false;">Panggil</a>
                </td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Sudah Dipanggil -->
    <div class="card">
      <div class="card-header bg-success text-white"><strong>Sudah Dipanggil</strong></div>
      <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-secondary">
            <tr>
              <th>No</th>
              <th>Pengunjung</th>
              <th>Nomor Antrian</th>
              <th>Status</th>
              <th>Durasi Pelayanan</th>
              <th>CS Role</th>
              <th>CS Name</th>
              <th>Aksi</th>
              <th>Panggil Ulang</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; foreach ($sudahDipanggil as $row):
              if (($row['cs_role'] ?? '') !== session('role')) continue;
            ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= ucfirst($row['kategori'] ?? '-') ?></td>
                <td>
                  <?php
                    $huruf = ($row['kategori'] ?? '') === 'mahasiswa' ? 'A' : (($row['kategori'] ?? '') === 'dosen' ? 'B' : 'C');
                    echo $huruf . str_pad($row['nomor_antrian'] ?? '0', 2, '0', STR_PAD_LEFT);
                  ?>
                </td>
                <td><?= ucfirst($row['status'] ?? '-') ?></td>
                <td>
                  <?php
                  if (!empty($row['waktu_mulai']) && !empty($row['waktu_selesai'])) {
                    $mulai = new DateTime($row['waktu_mulai']);
                    $selesai = new DateTime($row['waktu_selesai']);
                    $durasi = $mulai->diff($selesai);
                    echo $durasi->i . ' menit ' . $durasi->s . ' detik';
                  } elseif (!empty($row['waktu_mulai'])) {
                    $id = $row['id'];
                    echo '<span id="durasi-' . $id . '">0 detik</span>';
                    echo "<script>
                      const mulai$id = new Date('{$row['waktu_mulai']}');
                      function updateDurasi$id() {
                        const now = new Date();
                        const diff = Math.floor((now - mulai$id) / 1000);
                        const menit = Math.floor(diff / 60);
                        const detik = diff % 60;
                        const el = document.getElementById('durasi-$id');
                        if (el) el.innerText = menit + ' menit ' + detik + ' detik';
                      }
                      setInterval(updateDurasi$id, 1000);
                      updateDurasi$id();
                    </script>";
                  } else {
                    echo '-';
                  }
                  ?>
                </td>
                <td><?= $row['cs_role'] ?? '-' ?></td>
                <td><?= $row['cs_name'] ?? '-' ?></td>
                <td>
                  <?php if (($row['status'] ?? '') === 'dipanggil'): ?>
                    <a href="/panggilan/mulaiLayanan/<?= $row['id'] ?>" class="btn btn-warning btn-sm">Mulai Layanan</a>
                  <?php elseif (($row['status'] ?? '') === 'dilayani'): ?>
                    <a href="/panggilan/selesaiLayanan/<?= $row['id'] ?>" class="btn btn-success btn-sm">Selesai</a>
                  <?php else: ?>
                    <button class="btn btn-secondary btn-sm" disabled>Selesai</button>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if (($row['status'] ?? '') !== 'selesai'): ?>
                    <a href="/panggilan/ulang/<?= $row['id'] ?>" class="btn btn-outline-secondary btn-sm"
                       onclick="panggil(this, 'ulang'); return false;">Panggil Ulang</a>
                  <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled>Panggil Ulang</button>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>

<!-- Audio Script -->
<script>
  const loketNumber = "<?= $loketNumber ?>";
  let audioQueue = [];
  let isPlaying = false;
  const tombolAktif = new Map();
  let pendingRedirects = [];

  function panggil(button, jenis = 'panggil') {
    const row = button.closest("tr");
    const kategori = row.querySelector("td:nth-child(2)").innerText.trim().toLowerCase();
    const nomor = row.querySelector("td:nth-child(3)").innerText.trim();
    const id = getIdFromHref(button.href);

    let huruf = 'A';
    if (kategori === 'dosen') huruf = 'B';
    else if (kategori === 'umum') huruf = 'C';

    const role = "<?= session('role') ?>";
    let loket = '1';
    if (role === 'cs2') loket = '2';
    else if (role === 'cs3') loket = '3';

    const nomorInt = nomor.replace(/^\D+/g, '');
    const audioPath = jenis === 'panggil'
      ? `/sounds/nomor_antrian_${huruf}${nomorInt}_menuju_loket_${loket}.mp3`
      : `/sounds/nomor_antrian_${huruf}${nomorInt}_menuju_loket_${loket}_dipanggil_ulang.mp3`;

    if (!tombolAktif.has(id)) tombolAktif.set(id, button.innerHTML);
    button.innerHTML = '<i class="bi bi-arrow-repeat spinner-border spinner-border-sm"></i> Memutar...';
    button.disabled = true;

    audioQueue.push({ id, path: audioPath, href: button.getAttribute('href'), button });
    pendingRedirects.push(button.getAttribute('href'));

    if (!isPlaying) playNextAudio();
  }

  function playNextAudio() {
    if (audioQueue.length === 0) {
      isPlaying = false;
      return;
    }

    isPlaying = true;
    const next = audioQueue.shift();
    const audio = new Audio(next.path);
    audio.volume = 1.0;

    next.button.innerHTML = '<i class="bi bi-volume-up"></i> Memutar...';

    audio.play().catch(err => {
      console.error("Gagal memutar audio:", err);
      resetTombol(next.id, next.button);
      isPlaying = false;
      playNextAudio();
    });

    audio.onended = () => {
      resetTombol(next.id, next.button);
      isPlaying = false;
      const redirect = pendingRedirects.shift();
      if (redirect) {
        window.location.href = redirect;
      } else {
        playNextAudio();
      }
    };
  }

  function resetTombol(id, button) {
    if (tombolAktif.has(id)) {
      button.innerHTML = tombolAktif.get(id);
      button.disabled = false;
      tombolAktif.delete(id);
    }
  }

  function getIdFromHref(href) {
    const parts = href.split('/');
    return parts[parts.length - 1];
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
