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
    <form method="post" action="/layanan/simpan">
      <div class="mb-4">
        <select class="form-select" name="layanan" id="layananSelect" onchange="cekLainnya()">
          <option value="" selected disabled>Pilih layanan</option>
          <option value="kp_ta">Mengambil berita acara KP/TA</option>
          <option value="ijazah">Pengambilan ijazah</option>
          <option value="legalisir">Legalisir dokumen</option>
          <option value="lainnya">Lainnya</option>
        </select>
      </div>

      <div class="mb-4" id="lainnyaInputContainer" style="display: none;">
        <input type="text" class="form-control" name="layanan_lain" placeholder="Masukkan layanan lainnya...">
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-blue">OK</button>
      </div>
    </form>
  </div>

  <script>
    function cekLainnya() {
      const layanan = document.getElementById('layananSelect').value;
      const lainnyaInput = document.getElementById('lainnyaInputContainer');
      lainnyaInput.style.display = (layanan === 'lainnya') ? 'block' : 'none';
    }
  </script>

</body>
</html>
