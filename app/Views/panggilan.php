<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <title>Panggil Antrian</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script>
    function panggil(button, jenis = 'panggil') {
      const row = button.closest("tr");
      const kategori = row.querySelector("td:nth-child(2)").innerText.toLowerCase();
      const nomor = row.querySelector("td:nth-child(3)").innerText;
      const audio = new Audio(`/audio/${jenis}.mp3`);
      const huruf = nomor[0];
      const angka = nomor.slice(1);

      const playQueue = [
        audio,
        new Audio(`/audio/${huruf}.mp3`),
        ...angka.split('').map(n => new Audio(`/audio/${n}.mp3`))
      ];

      let index = 0;
      function playNext() {
        if (index < playQueue.length) {
          playQueue[index].play();
          playQueue[index].onended = playNext;
          index++;
        }
      }
      playNext();
    }
  </script>
</head>
<body>
<div class="container my-4">
  <h2 class="mb-4 text-center">Panggil Antrian Hari Ini</h2>

  <!-- Belum Dipanggil -->
  <div class="card mb-5">
    <div class="card-header bg-primary text-white">
      <strong>Belum Dipanggil</strong>
    </div>
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
                $huruf = $row['kategori'] === 'mahasiswa' ? 'A' :
                         ($row['kategori'] === 'dosen' ? 'B' : 'C');
                echo $huruf . str_pad($row['nomor_antrian'], 2, '0', STR_PAD_LEFT);
              ?>
            </td>
            <td>
              <a href="/panggilan/panggil/<?= $row['id'] ?>" class="btn btn-primary btn-sm" onclick="panggil(this)">Panggil</a>
            </td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Sudah Dipanggil -->
  <div class="card">
    <div class="card-header bg-success text-white">
      <strong>Sudah Dipanggil</strong>
    </div>
    <div class="card-body table-responsive">
<table class="table table-bordered table-striped">
  <thead class="table-secondary">
    <tr>
      <th>No</th>
      <th>Pengunjung</th>
      <th>Nomor Antrian</th>
      <th>Status</th>
      <th>Durasi Pelayanan</th>
      <th>Aksi</th>
      <th>Panggil Ulang</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; foreach ($sudahDipanggil as $row): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= ucfirst($row['kategori']) ?></td>
      <td>
        <?php
          $huruf = $row['kategori'] === 'mahasiswa' ? 'A' :
                   ($row['kategori'] === 'dosen' ? 'B' : 'C');
          echo $huruf . str_pad($row['nomor_antrian'], 2, '0', STR_PAD_LEFT);
        ?>
      </td>
      <td><?= ucfirst($row['status']) ?></td>
      <td>
        <?php
          if (!empty($row['waktu_mulai']) && !empty($row['waktu_selesai'])) {
              $mulai = new DateTime($row['waktu_mulai']);
              $selesai = new DateTime($row['waktu_selesai']);
              $durasi = $mulai->diff($selesai);
              echo $durasi->i . ' menit ' . $durasi->s . ' detik';
          } elseif ($row['waktu_mulai']) {
    $id = $row['id'];
    echo '<span id="durasi-' . $id . '">0 detik</span>';
    echo '<script>
      const mulai' . $id . ' = new Date("' . $row['waktu_mulai'] . '");
      function updateDurasi' . $id . '() {
        const now = new Date();
        const diff = Math.floor((now - mulai' . $id . ') / 1000);
        const menit = Math.floor(diff / 60);
        const detik = diff % 60;
        const el = document.getElementById("durasi-' . $id . '");
        if (el) el.innerText = `${menit} menit ${detik} detik`;
      }
      setInterval(updateDurasi' . $id . ', 1000);
      updateDurasi' . $id . '();
    </script>';

          } else {
              echo '-';
          }
        ?>
      </td>
      <td>
        <?php if ($row['status'] === 'dipanggil'): ?>
          <a href="/panggilan/mulaiLayanan/<?= $row['id'] ?>" class="btn btn-warning btn-sm">Mulai Layanan</a>
        <?php elseif ($row['status'] === 'dilayani'): ?>
          <a href="/panggilan/selesaiLayanan/<?= $row['id'] ?>" class="btn btn-success btn-sm">Selesai</a>
        <?php else: ?>
          <button class="btn btn-secondary btn-sm" disabled>Selesai</button>
        <?php endif; ?>
      </td>
      <td>
        <a href="/panggilan/ulang/<?= $row['id'] ?>" class="btn btn-outline-secondary btn-sm" onclick="panggil(this, 'ulang')">Panggil Ulang</a>
      </td>
    </tr>
    <?php endforeach ?>
  </tbody>
</table>


    </div>
  </div>
</div>
</body>
</html>
