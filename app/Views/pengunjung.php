<!DOCTYPE html>
<html>
<head>
    <title>Halaman Pengunjung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #007bff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 500px;
        }
        h3 {
            color: #007bff;
        }
        .btn-dark {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-dark:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        label, select, input {
            color: #333;
        }
    </style>

    <script>
        function updateLabel() {
            const pengguna = document.getElementById("pengguna").value;
            const label = document.getElementById("label-identitas");
            const input = document.getElementById("input-identitas");

            if (pengguna === "mahasiswa") {
                label.textContent = "NIM";
                input.name = "nim";
                input.placeholder = "Masukkan NIM";
            } else if (pengguna === "dosen") {
                label.textContent = "NIDN";
                input.name = "nidn";
                input.placeholder = "Masukkan NIDN";
            } else {
                label.textContent = "NIK";
                input.name = "nik";
                input.placeholder = "Masukkan NIK";
            }

            input.value = '';
            toggleProdi(); // Update tampilan prodi saat label berubah
        }

        function toggleProdi() {
            const pengguna = document.getElementById("pengguna").value;
            const prodiDiv = document.getElementById("prodi-group");

            if (pengguna === "umum") {
                prodiDiv.style.display = "none";
            } else {
                prodiDiv.style.display = "block";
            }
        }

        // Panggil saat pertama kali halaman dimuat
        document.addEventListener("DOMContentLoaded", function () {
            updateLabel();
        });
    </script>

</head>
<body>
<div class="container">
    <h3 class="text-center mb-4">Sistem Antrian PLT </p><p>(Pusat Layanan Terpadu)</h3>

    <div class="text-center mb-4">
        <img src="/logo_itp.png" alt="Logo" width="100">
    </div>

    <form action="/pengunjung/submit" method="post">
        <div class="mb-3">
            <label for="pengguna" class="form-label">Pengguna</label>
            <select class="form-select" name="pengguna" id="pengguna" onchange="updateLabel()">
                <option value="mahasiswa">Mahasiswa</option>
                <option value="dosen">Dosen</option>
                <option value="umum">Umum</option>
            </select>
        </div>

        <div class="mb-3">
            <label id="label-identitas" for="input-identitas" class="form-label">NIM</label>
            <input type="text" class="form-control" id="input-identitas" name="nim" placeholder="Masukkan NIM">
        </div>

        <div class="mb-3" id="prodi-group">
            <label for="prodi" class="form-label">Prodi</label>
            <select class="form-select" name="prodi" id="prodi">
                <option value="Teknik Informatika">Teknik Informatika</option>
                <option value="Teknik Mesin">Teknik Mesin</option>
                <option value="Teknik Sipil">Teknik Sipil</option>
                <option value="Teknik Lingkungan">Teknik Lingkungan</option>
                <option value="Teknik Elektro">Teknik Elektro</option>
                <option value="Teknik Geodesi">Teknik Geodesi</option>
                <option value="Tril-D4">Tril-D4</option>
                <option value="Teknik Sipil-D4">Teknik Sipil-D4</option>
            </select>
        </div>

        <button type="submit" class="btn btn-dark w-100">OK</button>
    </form>
</div>
</body>
</html>
