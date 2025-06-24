<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="d-flex">

      <!-- Sidebar -->
      <div class="bg-dark text-white d-flex flex-column p-3 position-relative" style="width: 250px; height: 100vh;">
        <div class="text-center mb-4">
          <h4 class="text-white">Sistem Antrian</h4>
        </div>
        <div class="text-center mb-4">
          <img src="/logo_itp.png" alt="Logo" width="80">
        </div>

        <ul class="nav flex-column mb-5">
          <li class="nav-item"><a class="nav-link text-white" href="/dashboard">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="/panggilan">Panggil Antrian</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="/referensi">Referensi Tujuan</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="/layar">Layar Antrian</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="/rekap">Rekap Antrian</a></li>
        </ul>

        <div class="mt-auto position-absolute bottom-0 start-0 w-100 p-3">
          <a href="/logout" class="btn btn-outline-light w-100">Logout</a>
        </div>
      </div>

      <!-- Main Content -->
      <div class="flex-grow-1 p-4">
        <h2 class="text-primary mb-4">Panggil Antrian</h2>

        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered text-center">
                <thead class="table-secondary">
                  <tr>
                    <th>No</th>
                    <th>Pengunjung</th>
                    <th>Nomor Antrian</th>
                    <th>Panggil</th>
                    <th>Panggil Ulang</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($antrian as $index => $row): ?>
                    <tr>
                      <td><?= $index + 1 ?></td>
                      <td><?= $row['kategori'] ?></td>
                      <td>
                        <?php
                          $huruf = '';
                          if ($row['kategori'] === 'mahasiswa') {
                            $huruf = 'A';
                          } elseif ($row['kategori'] === 'dosen') {
                            $huruf = 'B';
                          } elseif ($row['kategori'] === 'umum') {
                            $huruf = 'C';
                          }
                          echo $huruf . str_pad($row['nomor_antrian'], 2, '0', STR_PAD_LEFT);
                        ?>
                      </td>
                      <td>
                        <button class="btn btn-primary" onclick="panggil(this, 'panggil')">Panggil</button>
                      </td>
                      <td>
                        <button class="btn btn-outline-secondary" onclick="panggil(this, 'ulang')">Panggil Ulang</button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- Script untuk Audio -->
    <script>
      function panggil(button, type) {
        const tr = button.closest('tr');
        const kategori = tr.children[1].innerText.trim().toLowerCase(); // kolom kategori
        const kodeAntrian = tr.children[2].innerText.trim(); // sudah A01, B01, dll

        let loketNumber = '';

        if (kategori === 'mahasiswa') {
          loketNumber = '1';
        } else if (kategori === 'dosen') {
          loketNumber = '2';
        } else if (kategori === 'umum') {
          loketNumber = '3';
        } else {
          console.error('Kategori tidak dikenali');
          return;
        }

        let audioPath = '';
        if (type === 'panggil') {
          audioPath = /sounds/nomor antrian ${kodeAntrian} menuju loket ${loketNumber}.mp3;
        } else if (type === 'ulang') {
          audioPath = /sounds/nomor antrian ${kodeAntrian} menuju loket ${loketNumber} dipanggil ulang.mp3;
        }

        const audio = new Audio(audioPath);
        audio.play();

        if (type === 'panggil') {
          button.disabled = true;
          button.classList.remove('btn-primary');
          button.classList.add('btn-secondary');
          button.innerText = 'Sudah Dipanggil';
        }
      }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>