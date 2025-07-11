<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistem Antrian PLT</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #3399ff;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
    }

    .window {
      background: #fff;
      border-radius: 20px;
      padding: 40px 30px;
      width: 100%;
      max-width: 450px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
      text-align: center;
    }

    .window h5 {
      font-weight: bold;
      margin-bottom: 25px;
    }

    select.form-select, input.form-control {
      border-radius: 25px;
      height: 45px;
      font-size: 16px;
      text-align: center;
    }

    .btn-blue {
      background-color: #3399ff;
      color: white;
      border: none;
      border-radius: 25px;
      font-size: 18px;
      padding: 10px 0;
      transition: background-color 0.3s ease;
    }

    .btn-blue:hover {
      background-color: #007bff;
    }

    .header-bar {
      position: absolute;
      top: 30px;
      width: 100%;
      text-align: center;
      font-size: 20px;
      font-weight: bold;
      color: white;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body>

  <div class="header-bar">
    Sistem Antrian Pusat Layanan Terpadu PLT
  </div>

  <div class="window">
    <h5>Pilihan Layanan</h5>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
      </div>
    <?php endif; ?>

    <form method="post" action="/layanan/simpan">
      <div class="mb-4">
        <select class="form-select" name="layanan" id="layananSelect" onchange="cekLainnya()" required>
          <option value="" selected disabled>Pilih layanan</option>

          <!-- Mahasiswa -->
          <option value="registrasi" data-kategori="mahasiswa">Registrasi (KRS)</option>
          <option value="magang" data-kategori="mahasiswa">Magang / KP / PL</option>
          <option value="skripsi" data-kategori="mahasiswa">TA / PA / Thesis</option>
          <option value="wisuda" data-kategori="mahasiswa">Wisuda</option>
          <option value="pembayaran" data-kategori="mahasiswa">Pembayaran Uang Kuliah</option>

          <!-- Dosen -->
          <option value="perkuliahan" data-kategori="dosen">Perkuliahan</option>
          <option value="kepegawaian" data-kategori="dosen">Kepegawaian</option>
          <option value="keuangan" data-kategori="dosen">Keuangan</option>

          <!-- Umum -->
          <option value="pmb" data-kategori="umum">Penerimaan Mahasiswa Baru</option>

          <!-- Lainnya -->
          <option value="lainnya" data-kategori="mahasiswa dosen umum">Lainnya</option>
        </select>
      </div>

      <!-- Input jika pilih "Lainnya" -->
      <div class="mb-4" id="lainnyaInputContainer" style="display: none;">
        <input type="text" class="form-control" name="layanan_lain" placeholder="Masukkan layanan lainnya...">
      </div>

      <!-- Hidden kategori -->
      <input type="hidden" name="kategori" id="kategoriInput">

      <div class="d-grid">
        <button type="submit" class="btn btn-blue">OK</button>
      </div>
    </form>
  </div>

  <script>
    const kategori = "<?= strtolower(session('kategoriAktif') ?? '') ?>";

    function filterLayananByKategori() {
      const layananSelect = document.getElementById("layananSelect");
      const options = layananSelect.getElementsByTagName("option");

      for (let i = 0; i < options.length; i++) {
        const opt = options[i];
        const dataKategori = opt.getAttribute("data-kategori");

        if (!dataKategori || opt.value === "") {
          opt.style.display = "";
        } else if (kategori === "") {
          opt.style.display = "";
        } else if (dataKategori.includes(kategori)) {
          opt.style.display = "";
        } else {
          opt.style.display = "none";
        }
      }
    }

    function cekLainnya() {
      const selected = document.getElementById("layananSelect").value;
      const inputLainnya = document.getElementById("lainnyaInputContainer");
      inputLainnya.style.display = (selected === "lainnya") ? "block" : "none";

      const selectedOption = document.querySelector('#layananSelect option:checked');
      const kategoriVal = selectedOption ? selectedOption.getAttribute('data-kategori') : '';
      document.getElementById('kategoriInput').value = kategoriVal;
    }

    window.onload = function () {
      filterLayananByKategori();
      cekLainnya();
    };
  </script>

</body>
</html>
