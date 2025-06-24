<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  </head>
  <body>
    <div class="d-flex">
      <!-- Sidebar -->
<!-- Sidebar -->
<div class="bg-dark text-white d-flex flex-column p-3 position-relative" style="width: 250px; height: 100vh;">
  <div class="text-center mb-4">
    <h4 class="text-white">Sistem Antrian</h4>
  </div>
  <div class="text-center mb-4">
        <img src="/logo_itp.png" alt="Logo" width="80">
    </div>

  <!-- Navigation Links -->
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

  <!-- Logout button at bottom -->
  <div class="mt-auto position-absolute bottom-0 start-0 w-100 p-3">
    <a href="/logout" class="btn btn-outline-light w-100">Logout</a>
  </div>
</div>


      <!-- Main Content (Referensi Audio) -->
<div class="flex-grow-1 p-4">
  <h2 class="text-primary mb-4">Referensi Audio</h2>

  <div class="card p-4">
    <!-- Tombol Tambah Data -->
    <div class="mb-4">
      <button class="btn btn-dark">
        <i class="bi bi-plus-lg"></i> Tambah Data
      </button>
    </div>

    <!-- Form Referensi Audio -->
    <form>
      <!-- Status Pengunjung -->
      <div class="mb-3 row align-items-center">
  <label class="col-sm-3 col-form-label">Status Pengunjung</label>
  <div class="col-sm-6">
    <select class="form-select">
      <option value="">-- Pilih Status --</option>
      <option value="Mahasiswa">Mahasiswa</option>
      <option value="Dosen">Dosen</option>
      <option value="Umum">Umum</option>
    </select>
  </div>
</div>


      <!-- File Audio -->
      <div class="mb-3 row align-items-center">
        <label class="col-sm-3 col-form-label">File Audio</label>
        <div class="col-sm-6 d-flex align-items-center">
        <button>
          <i class="bi bi-volume-up-fill fs-4 "></i>
        </button>  
        <!-- <input type="text" class="form-control me-2" placeholder="Nama file audio"> -->
        </div>
      </div>

      <!-- Tombol Tambah Audio -->
      <!-- <div class="mb-4">
        <button class="btn btn-dark">
          <i class="bi bi-plus-lg"></i> Tambah Audio
        </button>
      </div> -->

      <!-- Tombol Submit -->
      <button type="submit" class="btn btn-dark">Submit</button>
      <button  class="btn btn-dark"><a href="/referensi">Batal</a></button>
    </form>
  </div>
</div>


    <!-- Include Bootstrap 5 and Font Awesome for icons -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
  </body>
</html>
