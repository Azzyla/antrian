<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
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


      <!-- Main Content (Referensi Tujuan) -->
<div class="flex-grow-1 p-4">
  <h2 class="text-primary mb-4">Referensi Tujuan</h2>

  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <button  class="btn btn-dark mb-2 "><a href="referensi/audio">tambah audio</a></button>
        <table class="table table-bordered text-center align-middle">
          <thead class="table-secondary">
            <tr>
              <th>No</th>
              <th>Status Pengunjung</th>
              <th>File Audio</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Mahasiswa</td>
              <td><i class="bi bi-volume-up-fill fs-4"></i></td>
              <td>
                <button class="btn btn-sm btn-warning me-2">
                  <i class="bi bi-pencil-fill"></i>
                </button>
                <button class="btn btn-sm btn-danger">
                  <i class="bi bi-trash-fill"></i>
                </button>
              </td>
            </tr>
            <tr>
              <td>2</td>
              <td>Mahasiswa</td>
              <td><i class="bi bi-volume-up-fill fs-4"></i></td>
              <td>
                <button class="btn btn-sm btn-warning me-2">
                  <i class="bi bi-pencil-fill"></i>
                </button>
                <button class="btn btn-sm btn-danger">
                  <i class="bi bi-trash-fill"></i>
                </button>
              </td>
            </tr>
            <tr>
              <td>3</td>
              <td>Mahasiswa</td>
              <td><i class="bi bi-volume-up-fill fs-4"></i></td>
              <td>
                <button class="btn btn-sm btn-warning me-2">
                  <i class="bi bi-pencil-fill"></i>
                </button>
                <button class="btn btn-sm btn-danger">
                  <i class="bi bi-trash-fill"></i>
                </button>
              </td>
            </tr>
            <tr>
              <td>4</td>
              <td>Umum</td>
              <td><i class="bi bi-volume-up-fill fs-4"></i></td>
              <td>
                <button class="btn btn-sm btn-warning me-2">
                  <i class="bi bi-pencil-fill"></i>
                </button>
                <button class="btn btn-sm btn-danger">
                  <i class="bi bi-trash-fill"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


        
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
  </body>
</html>
