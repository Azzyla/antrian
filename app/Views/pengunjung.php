<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Pengunjung</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
            font-weight: bold;
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
                input.maxLength = 10;
                input.required = true;
            } else if (pengguna === "dosen") {
                label.textContent = "NIDN";
                input.name = "nidn";
                input.placeholder = "Masukkan NIDN";
                input.maxLength = 10;
                input.required = true;
            } else if (pengguna === "umum") {
                label.textContent = "NIK";
                input.name = "nik";
                input.placeholder = "Masukkan NIK";
                input.maxLength = 16;
                input.required = true;
            } else {
                label.textContent = "Identitas";
                input.name = "identitas";
                input.placeholder = "Masukkan Identitas";
                input.required = false;
                input.maxLength = "";
            }

            input.value = '';
            toggleProdi();
        }

        function toggleProdi() {
            const pengguna = document.getElementById("pengguna").value;
            const prodiDiv = document.getElementById("prodi-group");
            prodiDiv.style.display = (pengguna === "mahasiswa" || pengguna === "dosen") ? "block" : "none";

            const prodiSelect = document.getElementById("prodi");
            prodiSelect.required = (pengguna === "mahasiswa" || pengguna === "dosen");
            if (!prodiSelect.required) {
                prodiSelect.selectedIndex = 0;
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            toggleProdi();

            // Validasi panjang input saat submit form
            document.querySelector("form").addEventListener("submit", function(e) {
                const pengguna = document.getElementById("pengguna").value;
                const identitas = document.getElementById("input-identitas").value.trim();

                let expectedLength = 0;
                if (pengguna === "mahasiswa" || pengguna === "dosen") {
                    expectedLength = 10;
                } else if (pengguna === "umum") {
                    expectedLength = 16;
                }

                if (expectedLength > 0 && identitas.length !== expectedLength) {
                    alert(`${pengguna.charAt(0).toUpperCase() + pengguna.slice(1)} harus mengisi ${expectedLength} digit.`);
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
</head>
<body>
<div class="container">
    <h3 class="text-center mb-3">Sistem Antrian PLT</h3>
    <p class="text-center text-muted mb-4">(Pusat Layanan Terpadu)</p>

    <div class="text-center mb-4">
        <img src="/logo_itp.png" alt="Logo ITP" width="100">
    </div>

    <form action="/pengunjung/submit" method="post">
        <div class="mb-3">
            <label for="pengguna" class="form-label">Pengguna</label>
            <select class="form-select" name="pengguna" id="pengguna" onchange="updateLabel()" required>
                <option value="" disabled selected>-- Pilih Jenis Pengguna --</option>
                <option value="mahasiswa">Mahasiswa</option>
                <option value="dosen">Dosen</option>
                <option value="umum">Umum</option>
            </select>
        </div>

        <div class="mb-3">
            <label id="label-identitas" for="input-identitas" class="form-label">Identitas</label>
            <input type="text" class="form-control" id="input-identitas" name="identitas" placeholder="Masukkan Identitas" required>
        </div>

        <div class="mb-3" id="prodi-group" style="display: none;">
            <label for="prodi" class="form-label">Program Studi</label>
            <select class="form-select" name="prodi" id="prodi">
                <option value="" disabled selected>-- Pilih Program Studi --</option>
                <option value="Teknik Informatika S1">Teknik Informatika Sarjana</option>
                <option value="Teknik Mesin S1">Teknik Mesin Sarjana</option>
                <option value="Teknik Mesin D3">Teknik Mesin Diploma</option>
                <option value="Teknik Elektro S1">Teknik Elektro Sarjana</option>
                <option value="TRIL D4">TRIL - Sarjana Terapan</option>
                <option value="Teknik Sipil S1">Teknik Sipil Sarjana</option>
                <option value="Teknik Sipil S2">Teknik Sipil Magister</option>
                <option value="TRKBG D4">TRKBG - Sarjana Terapan</option>
                <option value="Teknik Lingkungan S1">Teknik Lingkungan Sarjana</option>
                <option value="Teknik Geodesi S1">Teknik Geodesi</option>
            </select>
        </div>

        <button type="submit" class="btn btn-dark w-100">OK</button>
    </form>
</div>
</body>
</html>
